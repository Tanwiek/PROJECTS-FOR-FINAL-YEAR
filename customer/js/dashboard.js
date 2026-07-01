// js/dashboard.js
document.addEventListener("DOMContentLoaded", () => {
    // 1. Vérifier l'authentification au chargement
    fetch('api/auth.php?action=check')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // L'utilisateur est authentifié
                initDashboard(data.user);
            } else {
                // Non authentifié
                window.location.href = 'login.html';
            }
        })
        .catch(error => {
            console.error("Auth check failed:", error);
            window.location.href = 'login.html';
        });

    function initDashboard(user) {
        // Masquer l'écran de chargement
        const overlay = document.getElementById('loadingOverlay');
        if (overlay) overlay.style.opacity = '0';
        setTimeout(() => overlay && overlay.remove(), 300);

        // Remplir les informations utilisateur
        document.getElementById('userNameDisplay').textContent = user.name;
        document.getElementById('userRoleDisplay').textContent = 
            user.role.charAt(0).toUpperCase() + user.role.slice(1);

        // Définir la bannière de héros par rôle
        const heroBanner = document.getElementById('roleHeroBanner');
        if (heroBanner && ['manager', 'employee', 'intern'].includes(user.role)) {
            heroBanner.style.backgroundImage = `url('images/${user.role}_hero.png')`;
            heroBanner.style.display = 'block';
        }

        // Appliquer les contraintes par rôle (ex: afficher les liens réservés aux managers)
        if (user.role === 'manager') {
            document.querySelectorAll('.manager-only').forEach(el => {
                // Afficher uniquement les éléments de navigation ; les sections sont gérées par la classe active
                if (el.tagName === 'A') {
                    el.style.display = 'flex';
                }
            });
        }
        if (user.role === 'intern') {
            // Exemples de contraintes pour les stagiaires
            const newClientBtn = document.getElementById('btnNewClient');
            if (newClientBtn) newClientBtn.innerHTML = '<i data-lucide="plus"></i> Demander un client'; // Les stagiaires ne peuvent que demander
            // Juste pour démonstration, la logique réelle sera appliquée côté serveur
            // Pour l'instant, limitons l'aspect visuel
        }

        // Initialiser les icônes Lucide si ce n'est pas déjà fait
        if (window.lucide) {
            lucide.createIcons();
        }

        // Configurer la fonctionnalité de déconnexion
        const logoutBtn = document.getElementById('logoutBtn');
        if (logoutBtn) {
            logoutBtn.addEventListener('click', () => {
                fetch('api/auth.php?action=logout')
                    .then(() => {
                        localStorage.removeItem('tmt_user');
                        window.location.href = 'login.html';
                    });
            });
        }

        // Configurer le changement d'onglet de navigation
        const navItems = document.querySelectorAll('.nav-item');
        const viewSections = document.querySelectorAll('.view-section');
        const pageTitle = document.getElementById('pageTitle');
        const mobileMenuBtn = document.getElementById('mobileMenuBtn');
        const sidebar = document.getElementById('sidebar');

        navItems.forEach(item => {
            item.addEventListener('click', (e) => {
                e.preventDefault();
                const targetId = item.getAttribute('data-target');
                
                // Mettre à jour la navigation active
                navItems.forEach(nav => nav.classList.remove('active'));
                item.classList.add('active');
                
                // Mettre à jour le titre de la page
                let title = item.textContent.trim();
                if (targetId === 'users-view') title = "Gestion des Utilisateurs";
                if (targetId === 'services-view') title = "Gestion des Services";
                pageTitle.textContent = title;
                
                // Changer de vue
                viewSections.forEach(section => {
                    if (section.id === targetId) {
                        section.classList.add('active');
                    } else {
                        section.classList.remove('active');
                    }
                });

                // Fermer la barre latérale mobile si elle est ouverte
                if (window.innerWidth <= 768 && sidebar.classList.contains('open')) {
                    sidebar.classList.remove('open');
                }
            });
        });

        // Configurer le menu mobile
        if (mobileMenuBtn) {
            mobileMenuBtn.addEventListener('click', () => {
                sidebar.classList.toggle('open');
            });
        }
    }
});
