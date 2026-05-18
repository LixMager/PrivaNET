document.addEventListener('DOMContentLoaded', () => {
    const registerForm = document.getElementById('register-form');
    if (!registerForm) return;

    const usernameInput = document.getElementById('register-user');
    const usernameStatus = document.getElementById('username-status');
    const registerStatus = document.getElementById('register-status');
    const passwordInput = document.getElementById('register-password');
    const birthdateInput = document.getElementById('register-birthdate');

    let isUsernameAvailable = false;
    let usernameTimeout = null;

    // 1. Verificación AJAX en tiempo real del nombre de usuario
    usernameInput.addEventListener('input', () => {
        clearTimeout(usernameTimeout);
        const username = usernameInput.value.trim();

        if (username.length === 0) {
            usernameStatus.textContent = '';
            usernameStatus.className = 'status-msg';
            isUsernameAvailable = false;
            return;
        }

        usernameStatus.textContent = 'Verificando disponibilidad...';
        usernameStatus.className = 'status-msg';

        usernameTimeout = setTimeout(async () => {
            try {
                const formData = new FormData();
                formData.append('action', 'check_username');
                formData.append('username', username);

                const response = await fetch('index.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await response.json();

                if (result.available) {
                    usernameStatus.textContent = result.message || 'Usuario disponible';
                    usernameStatus.className = 'status-msg status-success';
                    isUsernameAvailable = true;
                } else {
                    usernameStatus.textContent = result.message || 'El usuario ya está en uso';
                    usernameStatus.className = 'status-msg status-error';
                    isUsernameAvailable = false;
                }
            } catch (error) {
                usernameStatus.textContent = 'Error al verificar usuario';
                usernameStatus.className = 'status-msg status-error';
                isUsernameAvailable = false;
            }
        }, 450); // Debounce de 450ms
    });

    // 2. Validación de Frontend y Envío del Formulario
    registerForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        registerStatus.textContent = '';
        registerStatus.className = 'status-msg';

        // Validar que el usuario esté disponible
        if (!isUsernameAvailable) {
            registerStatus.textContent = 'Por favor, elige un nombre de usuario disponible.';
            registerStatus.className = 'status-msg status-error';
            usernameInput.focus();
            return;
        }

        // Validar contraseña (al menos 8 caracteres)
        const password = passwordInput.value;

        if (password.length < 8) {
            registerStatus.textContent = 'La contraseña debe tener al menos 8 caracteres.';
            registerStatus.className = 'status-msg status-error';
            passwordInput.focus();
            return;
        }

        // Validar edad (> 13 años)
        const birthdateVal = birthdateInput.value;
        if (!birthdateVal) {
            registerStatus.textContent = 'Por favor, ingresa tu fecha de nacimiento.';
            registerStatus.className = 'status-msg status-error';
            birthdateInput.focus();
            return;
        }

        const birthDate = new Date(birthdateVal);
        const today = new Date();
        let age = today.getFullYear() - birthDate.getFullYear();
        const m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        if (age <= 13) {
            registerStatus.textContent = 'Debes ser mayor de 13 años para poder registrarte.';
            registerStatus.className = 'status-msg status-error';
            birthdateInput.focus();
            return;
        }

        // Enviar datos por AJAX al backend
        registerStatus.textContent = 'Registrando usuario...';
        registerStatus.className = 'status-msg';

        try {
            const formData = new FormData(registerForm);
            formData.append('action', 'register');

            const response = await fetch('index.php', {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                registerStatus.textContent = result.message;
                registerStatus.className = 'status-msg status-success';
                
                // Deshabilitar formulario y redirigir al dashboard
                registerForm.querySelectorAll('input, button').forEach(el => el.disabled = true);
                
                setTimeout(() => {
                    window.location.href = 'index.php';
                }, 1500);
            } else {
                registerStatus.textContent = result.message || 'Ocurrió un error en el registro.';
                registerStatus.className = 'status-msg status-error';
            }
        } catch (error) {
            registerStatus.textContent = 'Error de conexión con el servidor.';
            registerStatus.className = 'status-msg status-error';
        }
    });
});
