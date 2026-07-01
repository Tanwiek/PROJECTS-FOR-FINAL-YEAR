if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initUsersListeners);
} else {
    initUsersListeners();
}

function initUsersListeners() {
    // Vérifier si nous sommes sur le tableau de bord et si usersTable existe
    if (document.getElementById('usersTable')) {
        loadUsers();
    }

    const newUserForm = document.getElementById('newUserForm');
    if (newUserForm) {
        newUserForm.addEventListener('submit', handleNewUserSubmit);
    }

    const editUserForm = document.getElementById('editUserForm');
    if (editUserForm) {
        editUserForm.addEventListener('submit', handleEditUserSubmit);
    }
}

async function handleNewUserSubmit(e) {
    e.preventDefault();
    const newUserForm = e.target;
    const userData = {
        first_name: document.getElementById('userFirstName').value,
        last_name: document.getElementById('userLastName').value,
        email: document.getElementById('userEmail').value,
        password: document.getElementById('userPassword').value,
        role: document.getElementById('userRole').value
    };

    const btn = newUserForm.querySelector('button[type="submit"]');
    btn.disabled = true;
    btn.textContent = 'Création...';

    try {
        const response = await fetch('api/users.php?action=create', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(userData)
        });

        const data = await response.json();
        
        if (response.ok && data.status === 'success') {
            closeModal('userModal');
            newUserForm.reset();
            loadUsers(); // Recharger le tableau
            alert(data.message || "Utilisateur créé avec succès.");
        } else {
            alert(data.message || "Erreur lors de la création de l'utilisateur");
        }
    } catch (err) {
        console.error(err);
        alert("Erreur réseau lors de la création de l'utilisateur");
    } finally {
        btn.disabled = false;
        btn.textContent = "Ajouter l'utilisateur";
    }
}

async function handleEditUserSubmit(e) {
    e.preventDefault();
    const editUserForm = e.target;
    const btn = editUserForm.querySelector('button[type="submit"]');
    
    const userData = {
        id: document.getElementById('editUserId').value,
        first_name: document.getElementById('editUserFirstName').value,
        last_name: document.getElementById('editUserLastName').value,
        email: document.getElementById('editUserEmail').value,
        password: document.getElementById('editUserPassword').value,
        role: document.getElementById('editUserRole').value
    };

    btn.disabled = true;
    btn.textContent = 'Mise à jour...';

    try {
        const response = await fetch('api/users.php?action=update', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(userData)
        });

        const data = await response.json();
        
        if (response.ok && data.status === 'success') {
            closeModal('editUserModal');
            loadUsers();
            alert(data.message || "Utilisateur mis à jour.");
        } else {
            alert(data.message || "Erreur lors de la mise à jour");
        }
    } catch (err) {
        console.error(err);
        alert("Erreur réseau");
    } finally {
        btn.disabled = false;
        btn.textContent = "Mettre à jour l'utilisateur";
    }
}

async function loadUsers() {
    const tbody = document.querySelector('#usersTable tbody');
    if (!tbody) return;

    // Vérifier d'abord le rôle de l'utilisateur depuis le stockage local pour éviter les erreurs 403 non autorisées
    const tmtUserStr = localStorage.getItem('tmt_user');
    if (tmtUserStr) {
        try {
            const userObj = JSON.parse(tmtUserStr);
            if (userObj.role !== 'manager') {
                return; // Pas un gestionnaire, inutile de récupérer ou d'afficher les utilisateurs
            }
        } catch(e) {}
    }

    try {
        const response = await fetch('api/users.php?action=list');
        const data = await response.json();

        if (response.ok && data.status === 'success') {
            const users = data.data;

            if (users.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="text-center text-muted">Aucun utilisateur trouvé.</td></tr>';
                return;
            }

            tbody.innerHTML = users.map(user => `
                <tr>
                    <td><strong>${user.first_name} ${user.last_name}</strong></td>
                    <td>${user.email}</td>
                    <td><span class="badge ${getRoleBadgeClass(user.role)}">${formatRole(user.role)}</span></td>
                    <td>${new Date(user.created_at).toLocaleDateString()}</td>
                    <td>
                        <button class="btn-icon" title="Modifier" onclick='openEditUserModal(${JSON.stringify(user)})'><i data-lucide="edit-2"></i></button>
                        <button class="btn-icon" title="Supprimer" onclick="deleteUser(${user.id})" style="color: var(--danger);"><i data-lucide="trash-2"></i></button>
                    </td>
                </tr>
            `).join('');

            // Réinitialiser les icônes pour les nouveaux éléments du DOM
            if (window.lucide) lucide.createIcons();
        } else {
            tbody.innerHTML = `<tr><td colspan="5" class="text-center text-danger">${data.message || 'Échec du chargement.'}</td></tr>`;
        }
    } catch (err) {
        console.error(err);
        tbody.innerHTML = '<tr><td colspan="5" class="text-center text-danger">Erreur réseau.</td></tr>';
    }
}

function openEditUserModal(user) {
    document.getElementById('editUserId').value = user.id;
    document.getElementById('editUserFirstName').value = user.first_name;
    document.getElementById('editUserLastName').value = user.last_name;
    document.getElementById('editUserEmail').value = user.email;
    document.getElementById('editUserRole').value = user.role;
    document.getElementById('editUserPassword').value = ''; // Toujours commencer vide
    
    openModal('editUserModal');
}

async function deleteUser(id) {
    if (!confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ? Cette action est irréversible.")) return;

    try {
        const response = await fetch('api/users.php?action=delete', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ id })
        });
        const data = await response.json();

        if (response.ok && data.status === 'success') {
            loadUsers();
            alert(data.message);
        } else {
            alert(data.message || "Erreur lors de la suppression");
        }
    } catch (err) {
        console.error(err);
        alert("Erreur réseau");
    }
}

function formatRole(role) {
    switch(role) {
        case 'manager': return 'Administrateur';
        case 'employee': return 'Employé';
        case 'intern': return 'Stagiaire';
        default: return role;
    }
}

function getRoleBadgeClass(role) {
    switch(role) {
        case 'manager': return 'badge-completed';
        case 'employee': return 'badge-progress';
        case 'intern': return 'badge-pending';
        default: return '';
    }
}
