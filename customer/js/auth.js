// js/auth.js

document.addEventListener("DOMContentLoaded", () => {
    // Vérifier si l'utilisateur est déjà connecté
    fetch('api/auth.php?action=check')
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                // Rediriger immédiatement si déjà autorisé
                window.location.href = 'index.html';
            }
        })
        .catch(() => { /* ne rien faire, rester sur la page de connexion */ });

    const loginForm = document.getElementById('loginForm');
    const loginError = document.getElementById('loginError');
    const loginBtn = document.getElementById('loginBtn');
    const spinner = loginBtn.querySelector('.spinner');
    const btnText = loginBtn.querySelector('span');

    if (loginForm) {
        loginForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            
            // Retour visuel de l'interface
            loginError.style.display = 'none';
            btnText.style.display = 'none';
            spinner.style.display = 'block';
            loginBtn.disabled = true;

            try {
                const response = await fetch('api/auth.php?action=login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({ email, password })
                });

                const data = await response.json();

                if (response.ok && data.status === 'success') {
                    // Enregistrer les informations utilisateur minimales (le jeton est géré via les cookies de session)
                    localStorage.setItem('tmt_user', JSON.stringify(data.user));
                    window.location.href = 'index.html';
                } else {
                    loginError.textContent = data.message || "Une erreur s'est produite lors de la connexion.";
                    loginError.style.display = 'flex';
                }
            } catch (error) {
                loginError.textContent = "Erreur réseau. Veuillez réessayer.";
                loginError.style.display = 'flex';
            } finally {
                btnText.style.display = 'inline';
                spinner.style.display = 'none';
                loginBtn.disabled = false;
            }
        });
    }
});
