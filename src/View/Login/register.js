document.addEventListener('DOMContentLoaded', function () {
    var registerForm = document.getElementById('register-form');
    if (!registerForm) {
        return;
    }

    var usernameInput = document.getElementById('register-user');
    var usernameStatus = document.getElementById('username-status');
    var registerStatus = document.getElementById('register-status');
    var passwordInput = document.getElementById('register-password');
    var birthdateInput = document.getElementById('register-birthdate');

    var isUsernameAvailable = false;
    var usernameTimeout = null;

    usernameInput.addEventListener('input', function () {
        clearTimeout(usernameTimeout);
        var username = usernameInput.value.trim();

        if (username.length === 0) {
            usernameStatus.textContent = '';
            usernameStatus.className = 'status-msg';
            isUsernameAvailable = false;
            return;
        }

        usernameStatus.textContent = 'Verificando disponibilidad...';
        usernameStatus.className = 'status-msg';

        usernameTimeout = setTimeout(function () {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'index.php', true);
            
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {
                            var result = JSON.parse(xhr.responseText);
                            if (result.available) {
                                usernameStatus.textContent = result.message || 'Usuario disponible';
                                usernameStatus.className = 'status-msg status-success';
                                isUsernameAvailable = true;
                            } else {
                                usernameStatus.textContent = result.message || 'El usuario ya está en uso';
                                usernameStatus.className = 'status-msg status-error';
                                isUsernameAvailable = false;
                            }
                        } catch (e) {
                            usernameStatus.textContent = 'Error al verificar usuario';
                            usernameStatus.className = 'status-msg status-error';
                            isUsernameAvailable = false;
                        }
                    } else {
                        usernameStatus.textContent = 'Error al verificar usuario';
                        usernameStatus.className = 'status-msg status-error';
                        isUsernameAvailable = false;
                    }
                }
            };

            var formData = new FormData();
            formData.append('action', 'check_username');
            formData.append('username', username);
            xhr.send(formData);
        }, 450);
    });

    registerForm.addEventListener('submit', function (e) {
        e.preventDefault();

        registerStatus.textContent = '';
        registerStatus.className = 'status-msg';

        if (!isUsernameAvailable) {
            registerStatus.textContent = 'Por favor, elige un nombre de usuario disponible.';
            registerStatus.className = 'status-msg status-error';
            usernameInput.focus();
            return;
        }

        var password = passwordInput.value;
        var alphanumericRegex = /^[a-zA-Z0-9]+$/;

        if (password.length < 8) {
            registerStatus.textContent = 'La contraseña debe tener al menos 8 caracteres.';
            registerStatus.className = 'status-msg status-error';
            passwordInput.focus();
            return;
        }

        if (!alphanumericRegex.test(password)) {
            registerStatus.textContent = 'La contraseña debe contener solo caracteres alfanuméricos (letras y números).';
            registerStatus.className = 'status-msg status-error';
            passwordInput.focus();
            return;
        }

        var birthdateVal = birthdateInput.value;
        if (!birthdateVal) {
            registerStatus.textContent = 'Por favor, ingresa tu fecha de nacimiento.';
            registerStatus.className = 'status-msg status-error';
            birthdateInput.focus();
            return;
        }

        var birthDate = new Date(birthdateVal);
        var today = new Date();
        var age = today.getFullYear() - birthDate.getFullYear();
        var m = today.getMonth() - birthDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }

        if (age <= 13) {
            registerStatus.textContent = 'Debes ser mayor de 13 años para poder registrarte.';
            registerStatus.className = 'status-msg status-error';
            birthdateInput.focus();
            return;
        }

        registerStatus.textContent = 'Registrando usuario...';
        registerStatus.className = 'status-msg';

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'index.php', true);

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        var result = JSON.parse(xhr.responseText);
                        if (result.success) {
                            registerStatus.textContent = result.message;
                            registerStatus.className = 'status-msg status-success';
                            
                            var inputsAndButtons = registerForm.querySelectorAll('input, button');
                            for (var i = 0; i < inputsAndButtons.length; i++) {
                                inputsAndButtons[i].disabled = true;
                            }
                            
                            setTimeout(function () {
                                window.location.href = 'index.php';
                            }, 1500);
                        } else {
                            registerStatus.textContent = result.message || 'Ocurrió un error en el registro.';
                            registerStatus.className = 'status-msg status-error';
                        }
                    } catch (e) {
                        registerStatus.textContent = 'Error al procesar el registro.';
                        registerStatus.className = 'status-msg status-error';
                    }
                } else {
                    registerStatus.textContent = 'Error de conexión con el servidor.';
                    registerStatus.className = 'status-msg status-error';
                }
            }
        };

        var formData = new FormData(registerForm);
        formData.append('action', 'register');
        xhr.send(formData);
    });
});
