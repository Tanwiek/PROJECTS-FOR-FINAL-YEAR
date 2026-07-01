if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initServicesListeners);
} else {
    initServicesListeners();
}

function initServicesListeners() {
    const navItem = document.querySelector('[data-target="services-view"]');
    if (navItem) {
        navItem.addEventListener('click', loadServices);
    }

    const newServiceForm = document.getElementById('newServiceForm');
    if (newServiceForm) {
        newServiceForm.addEventListener('submit', handleNewService);
    }

    const editServiceForm = document.getElementById('editServiceForm');
    if (editServiceForm) {
        editServiceForm.addEventListener('submit', handleUpdateService);
    }
}

async function loadServices() {
    try {
        const response = await fetch('api/files.php?action=services');
        const data = await response.json();
        if (data.status === 'success') {
            renderServices(data.data);
        }
    } catch (err) {
        console.error("Échec du chargement des services :", err);
    }
}

function renderServices(services) {
    const tbody = document.querySelector('#servicesTable tbody');
    if (!tbody) return;

    if (services.length === 0) {
        tbody.innerHTML = '<tr><td colspan="3" class="text-center">Aucun service trouvé.</td></tr>';
        return;
    }

    tbody.innerHTML = services.map(service => `
        <tr>
            <td><strong>${service.name}</strong></td>
            <td>${service.description || '<span class="text-muted">Pas de description</span>'}</td>
            <td>
                <button class="btn-icon" title="Modifier" onclick='openEditServiceModal(${JSON.stringify(service)})'><i data-lucide="edit-3"></i></button>
                <button class="btn-icon" title="Supprimer" onclick="deleteService(${service.id})" style="color: var(--danger);"><i data-lucide="trash-2"></i></button>
            </td>
        </tr>
    `).join('');

    if (window.lucide) lucide.createIcons();
}

function openEditServiceModal(service) {
    document.getElementById('editServiceId').value = service.id;
    document.getElementById('editServiceName').value = service.name;
    document.getElementById('editServiceDescription').value = service.description || '';
    openModal('editServiceModal');
}

async function handleNewService(e) {
    e.preventDefault();
    const name = document.getElementById('serviceName').value;
    const description = document.getElementById('serviceDescription').value;

    try {
        const response = await fetch('api/files.php?action=service_create', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ name, description })
        });
        const data = await response.json();
        if (data.status === 'success') {
            closeModal('serviceModal');
            loadServices();
            alert("Service créé !");
            e.target.reset();
        } else {
            alert(data.message);
        }
    } catch (err) {
        alert("Erreur réseau");
    }
}

async function handleUpdateService(e) {
    e.preventDefault();
    const id = document.getElementById('editServiceId').value;
    const name = document.getElementById('editServiceName').value;
    const description = document.getElementById('editServiceDescription').value;

    try {
        const response = await fetch('api/files.php?action=service_update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id, name, description })
        });
        const data = await response.json();
        if (data.status === 'success') {
            closeModal('editServiceModal');
            loadServices();
            alert("Service mis à jour !");
        } else {
            alert(data.message);
        }
    } catch (err) {
        alert("Erreur réseau");
    }
}

async function deleteService(id) {
    if (!confirm("Supprimer ce service ? Cela pourrait affecter les dossiers existants.")) return;

    try {
        const response = await fetch('api/files.php?action=service_delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        const data = await response.json();
        if (data.status === 'success') {
            loadServices();
            alert("Service supprimé.");
        } else {
            alert(data.message);
        }
    } catch (err) {
        alert("Erreur réseau");
    }
}
