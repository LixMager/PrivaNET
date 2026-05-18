document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('login-form');
    if (!loginForm) return;

    const loginStatus = document.getElementById('login-status');

    loginForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        if (loginStatus) {
            loginStatus.textContent = 'Iniciando sesión...';
            loginStatus.className = 'status-msg';
        }

        try {
            const formData = new FormData(loginForm);
            formData.append('action', 'login');

            const response = await fetch('index.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                if (loginStatus) {
                    loginStatus.textContent = result.message;
                    loginStatus.className = 'status-msg status-success';
                }

                loginForm.querySelectorAll('input, button').forEach(el => el.disabled = true);

                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 1000);
            } else {
                if (loginStatus) {
                    loginStatus.textContent = result.message || 'Usuario o contraseña incorrectos.';
                    loginStatus.className = 'status-msg status-error';
                }
            }
        } catch (error) {
            if (loginStatus) {
                loginStatus.textContent = 'Error de conexión con el servidor.';
                loginStatus.className = 'status-msg status-error';
            }
        }
    });
});
