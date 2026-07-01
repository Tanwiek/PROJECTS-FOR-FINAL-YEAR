// js/files.js
document.addEventListener("DOMContentLoaded", () => {
    
    // Vérifier si sur le tableau de bord
    if (document.getElementById('filesTable')) {
        loadFiles();
        loadDropdowns();
    }

    const newFileForm = document.getElementById('newFileForm');
    if (newFileForm) {
        newFileForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const fileData = {
                client_id: document.getElementById('fileClientSelect').value,
                service_id: document.getElementById('fileServiceSelect').value,
                remarks: document.getElementById('fileRemarks').value
            };

            const btn = newFileForm.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.textContent = 'Enregistrement...';

            try {
                const response = await fetch('api/files.php?action=create', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(fileData)
                });

                const data = await response.json();
                
                if (response.ok && data.message) {
                    closeModal('fileModal');
                    newFileForm.reset();
                    loadFiles(); // Recharger le tableau
                    
                    // Mettre à jour les statistiques
                    const statPending = document.getElementById('statPendingFiles');
                    if (statPending && statPending.textContent !== '...') {
                        statPending.textContent = parseInt(statPending.textContent) + 1;
                    }
                } else {
                    alert(data.message || "Erreur lors de l'enregistrement du dossier");
                }
            } catch (err) {
                console.error(err);
                alert("Erreur réseau lors de l'enregistrement du dossier");
            } finally {
                btn.disabled = false;
                btn.textContent = 'Créer le Dossier';
            }
        });
    }

});

async function loadFiles() {
    const tbody = document.querySelector('#filesTable tbody');
    if (!tbody) return;

    try {
        const response = await fetch('api/files.php?action=list');
        const data = await response.json();

        if (response.ok && data.status === 'success') {
            const files = data.data;
            
            // Statistiques
            const pendingCount = files.filter(f => f.status === 'pending').length;
            const completedCount = files.filter(f => f.status === 'completed').length;
            
            const statPending = document.getElementById('statPendingFiles');
            const statCompleted = document.getElementById('statCompletedFiles');
            
            if (statPending) statPending.textContent = pendingCount;
            if (statCompleted) statCompleted.textContent = completedCount;

            if (files.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Aucun dossier suivi pour le moment.</td></tr>';
                return;
            }

            tbody.innerHTML = files.map(file => {
                let badgeClass = 'badge-pending';
                if(file.status === 'in_progress') badgeClass = 'badge-progress';
                if(file.status === 'completed') badgeClass = 'badge-completed';
                
                const statusLabel = file.status.replace('_', ' ').toUpperCase();

                return `<tr>
                    <td><strong>${file.first_name} ${file.last_name}</strong></td>
                    <td>${file.service_name}</td>
                    <td><span class="badge ${badgeClass}">${statusLabel}</span></td>
                    <td>${new Date(file.created_at).toLocaleDateString()}</td>
                    <td>
                        <button class="btn-icon" title="Gérer le Dossier" onclick='openEditFileModal(${JSON.stringify(file)})'><i data-lucide="edit-3"></i></button>
                    </td>
                </tr>`;
            }).join('');

            if (window.lucide) lucide.createIcons();
        } else {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Échec du chargement des dossiers.</td></tr>';
        }
    } catch (err) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Erreur réseau.</td></tr>';
    }
}

// Ajouter un crochet pour rafraîchir les listes déroulantes à l'ouverture de la fenêtre modale
window.openFileModal = function() {
    loadDropdowns();
    openModal('fileModal');
};

window.openEditFileModal = function(file) {
    document.getElementById('editFileId').value = file.id;
    document.getElementById('editFileStatus').value = file.status;
    document.getElementById('editFileRemarks').value = file.remarks || '';
    openModal('editFileModal');
};

document.addEventListener("DOMContentLoaded", () => {
    const editFileForm = document.getElementById('editFileForm');
    if (editFileForm) {
        editFileForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = editFileForm.querySelector('button[type="submit"]');
            btn.disabled = true;
            btn.textContent = 'Mise à jour...';

            const fileData = {
                id: document.getElementById('editFileId').value,
                status: document.getElementById('editFileStatus').value,
                remarks: document.getElementById('editFileRemarks').value
            };

            try {
                const response = await fetch('api/files.php?action=update', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(fileData)
                });
                const data = await response.json();
                if (data.status === 'success') {
                    closeModal('editFileModal');
                    loadFiles();
                    alert("Dossier mis à jour !");
                } else {
                    alert(data.message || "Erreur lors de la mise à jour");
                }
            } catch (err) {
                alert("Erreur réseau");
            } finally {
                btn.disabled = false;
                btn.textContent = 'Mettre à jour le Dossier';
            }
        });
    }
});

async function loadDropdowns() {
    // Charger les Clients
    try {
        const resClients = await fetch('api/clients.php?action=list');
        const dataClients = await resClients.json();
        if (dataClients.status === 'success') {
            const clientSelect = document.getElementById('fileClientSelect');
            if(clientSelect) {
                clientSelect.innerHTML = '<option value="">Sélectionnez un Client...</option>' + 
                    dataClients.data.map(c => `<option value="${c.id}">${c.first_name} ${c.last_name}</option>`).join('');
            }
        }
    } catch(e) { console.error("Could not load clients for dropdown", e); }

    // Charger les Services
    try {
        const resServices = await fetch('api/files.php?action=services');
        const dataServices = await resServices.json();
        if (dataServices.status === 'success') {
            const serviceSelect = document.getElementById('fileServiceSelect');
            if(serviceSelect) {
                serviceSelect.innerHTML = '<option value="">Sélectionnez un Service...</option>' + 
                    dataServices.data.map(s => `<option value="${s.id}">${s.name}</option>`).join('');
            }
        }
    } catch(e) { console.error("Could not load services for dropdown", e); }
}
