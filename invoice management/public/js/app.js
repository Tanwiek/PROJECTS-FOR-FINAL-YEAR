/**
 * App.js - Minimal Offline Fallback for Bootstrap Interactions
 */
document.addEventListener('DOMContentLoaded', function() {
    // Dropdown Toggles
    const dropdownToggles = document.querySelectorAll('.dropdown-toggle');
    dropdownToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const menu = this.nextElementSibling;
            if (menu && menu.classList.contains('dropdown-menu')) {
                menu.classList.toggle('show');
            }
        });
    });

    // Close Dropdowns on outside click
    window.addEventListener('click', function(e) {
        if (!e.target.matches('.dropdown-toggle')) {
            document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
                menu.classList.remove('show');
            });
        }
    });

    // Alert Dismissals
    const closeButtons = document.querySelectorAll('.btn-close');
    closeButtons.forEach(btn => {
        btn.addEventListener('click', function() {
            const alert = this.closest('.alert');
            if (alert) {
                alert.style.display = 'none';
            }
        });
    });
});
