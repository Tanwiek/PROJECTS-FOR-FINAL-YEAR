// js/clients.js
let allClients = [];

document.addEventListener("DOMContentLoaded", () => {
    
    // Récupérer les clients uniquement si nous sommes sur le tableau de bord
    if (document.getElementById('clientsTable')) {
        loadClients();
    }

    // Configurer la logique de recherche
    const searchInput = document.getElementById('clientSearchInput');
    if (searchInput) {
        searchInput.addEventListener('input', (e) => {
            const term = e.target.value.toLowerCase();
            const filtered = allClients.filter(client => {
                const fullName = (`${client.first_name} ${client.last_name}`).toLowerCase();
                const email = (client.email || "").toLowerCase();
                const phone = (client.phone || "").toLowerCase();
                
                return fullName.includes(term) || email.includes(term) || phone.includes(term);
            });
            renderClients(filtered);
        });
    }

    const newClientForm = document.getElementById('newClientForm');
    if (newClientForm) {
        newClientForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = newClientForm.querySelector('button[type="submit"]');
            
            const clientData = {
                first_name: document.getElementById('clientFirstName').value,
                last_name: document.getElementById('clientLastName').value,
                email: document.getElementById('clientEmail').value,
                phone: document.getElementById('clientPhone').value,
                address: document.getElementById('clientAddress').value
            };

            btn.disabled = true;
            btn.textContent = 'Enregistrement...';

            try {
                const response = await fetch('api/clients.php?action=create', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(clientData)
                });

                const data = await response.json();
                
                if (response.ok && data.status === 'success') {
                    closeModal('clientModal');
                    newClientForm.reset();
                    loadClients(); // Recharger le tableau
                    
                    // Mettre à jour les statistiques si elles existent
                    const statClients = document.getElementById('statTotalClients');
                    if (statClients && statClients.textContent !== '...') {
                        statClients.textContent = parseInt(statClients.textContent) + 1;
                    }
                } else {
                    alert(data.message || "Erreur lors de l'enregistrement du client");
                }
            } catch (err) {
                console.error(err);
                alert("Erreur réseau lors de l'enregistrement du client");
            } finally {
                btn.disabled = false;
                btn.textContent = 'Enregistrer le Client';
            }
        });
    }

});

async function loadClients() {
    const tbody = document.querySelector('#clientsTable tbody');
    if (!tbody) return;

    try {
        const response = await fetch('api/clients.php?action=list');
        const data = await response.json();

        if (response.ok && data.status === 'success') {
            allClients = data.data;
            
            // Mettre à jour les statistiques
            const statClients = document.getElementById('statTotalClients');
            if (statClients) statClients.textContent = allClients.length;

            renderClients(allClients);
        } else {
            tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Échec du chargement des clients.</td></tr>';
        }
    } catch (err) {
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Erreur réseau.</td></tr>';
    }
}

function renderClients(clients) {
    const table = document.getElementById('clientsTable');
    const tbody = table.querySelector('tbody');
    const theadRow = table.querySelector('thead tr');
    if (!tbody) return;

    // Vérifier si l'utilisateur actuel est gestionnaire pour afficher une colonne supplémentaire
    let isManager = false;
    const tmtUserStr = localStorage.getItem('tmt_user');
    if (tmtUserStr) {
        try {
            const userObj = JSON.parse(tmtUserStr);
            isManager = (userObj.role === 'manager');
        } catch(e) {}
    }

    // Mettre à jour l'en-tête si ce n'est pas déjà fait
    if (isManager && !theadRow.querySelector('.creator-col')) {
        const creatorTh = document.createElement('th');
        creatorTh.className = 'creator-col';
        creatorTh.textContent = 'Ajouté par';
        theadRow.insertBefore(creatorTh, theadRow.children[4]); // Insérer avant les Actions
    }

    if (clients.length === 0) {
        tbody.innerHTML = `<tr><td colspan="${isManager ? 6 : 5}" class="text-center text-muted">Aucun client trouvé pour cette recherche.</td></tr>`;
        return;
    }

    tbody.innerHTML = clients.map(client => `
        <tr>
            <td><strong>${client.first_name} ${client.last_name}</strong></td>
            <td>${client.email || '<span class="text-muted">N/A</span>'}</td>
            <td>${client.phone || '<span class="text-muted">N/A</span>'}</td>
            <td>${new Date(client.created_at).toLocaleDateString()}</td>
            ${isManager ? `<td><span class="badge badge-progress">${client.creator_name || 'Inconnu'}</span></td>` : ''}
            <td>
                <button class="btn-icon" title="Modifier" onclick='openEditClientModal(${JSON.stringify(client)})'><i data-lucide="edit-3"></i></button>
                <button class="btn-icon" title="Supprimer" onclick="deleteClient(${client.id})" style="color: var(--danger);"><i data-lucide="trash-2"></i></button>
            </td>
        </tr>
    `).join('');

    // Réinitialiser les icônes pour les nouveaux éléments du DOM
    if (window.lucide) lucide.createIcons();
}

function openEditClientModal(client) {
    document.getElementById('editClientId').value = client.id;
    document.getElementById('editClientFirstName').value = client.first_name;
    document.getElementById('editClientLastName').value = client.last_name;
    document.getElementById('editClientEmail').value = client.email || '';
    document.getElementById('editClientPhone').value = client.phone || '';
    document.getElementById('editClientAddress').value = client.address || '';
    
    openModal('editClientModal');
}

// Ajouter un écouteur d'événement pour le formulaire de modification
document.addEventListener("DOMContentLoaded", () => {
    const editClientForm = document.getElementById('editClientForm');
    if (editClientForm) {
        editClientForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const btn = editClientForm.querySelector('button[type="submit"]');
            
            const clientData = {
                id: document.getElementById('editClientId').value,
                first_name: document.getElementById('editClientFirstName').value,
                last_name: document.getElementById('editClientLastName').value,
                email: document.getElementById('editClientEmail').value,
                phone: document.getElementById('editClientPhone').value,
                address: document.getElementById('editClientAddress').value
            };

            btn.disabled = true;
            btn.textContent = 'Mise à jour...';

            try {
                const response = await fetch('api/clients.php?action=update', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(clientData)
                });

                const data = await response.json();
                
                if (response.ok && data.status === 'success') {
                    closeModal('editClientModal');
                    loadClients();
                    alert(data.message || "Client mis à jour.");
                } else {
                    alert(data.message || "Erreur lors de la mise à jour");
                }
            } catch (err) {
                console.error(err);
                alert("Erreur réseau");
            } finally {
                btn.disabled = false;
                btn.textContent = "Mettre à jour le Client";
            }
        });
    }
});

async function deleteClient(id) {
    if (!confirm("Êtes-vous sûr de vouloir supprimer ce client ? Tous ses dossiers seront également supprimés.")) return;

    try {
        const response = await fetch('api/clients.php?action=delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        const data = await response.json();

        if (response.ok && data.status === 'success') {
            loadClients();
            alert(data.message);
        } else {
            alert(data.message || "Erreur lors de la suppression");
        }
    } catch (err) {
        console.error(err);
        alert("Erreur réseau");
    }
}

// Fonctions utilitaires pour les fenêtres modales
function openModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.style.display = 'flex';
        // Léger délai pour l'animation
        setTimeout(() => modal.classList.add('show'), 10);
    }
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) {
        modal.classList.remove('show');
        setTimeout(() => modal.style.display = 'none', 300);
    }
}
