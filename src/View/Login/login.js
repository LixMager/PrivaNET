document.addEventListener('DOMContentLoaded', function () {
    var loginForm = document.getElementById('login-form');
    if (!loginForm) {
        return;
    }

    var loginStatus = document.getElementById('login-status');

    loginForm.addEventListener('submit', function (e) {
        e.preventDefault();

        if (loginStatus) {
            loginStatus.textContent = 'Iniciando sesión...';
            loginStatus.className = 'status-msg';
        }

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'index.php', true);
        
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        var result = JSON.parse(xhr.responseText);
                        if (result.success) {
                            if (loginStatus) {
                                loginStatus.textContent = result.message;
                                loginStatus.className = 'status-msg status-success';
                            }

                            var inputsAndButtons = loginForm.querySelectorAll('input, button');
                            for (var i = 0; i < inputsAndButtons.length; i++) {
                                inputsAndButtons[i].disabled = true;
                            }

                            setTimeout(function () {
                                window.location.href = 'index.php';
                            }, 1000);
                        } else {
                            if (loginStatus) {
                                loginStatus.textContent = result.message || 'Usuario o contraseña incorrectos.';
                                loginStatus.className = 'status-msg status-error';
                            }
                        }
                    } catch (err) {
                        if (loginStatus) {
                            loginStatus.textContent = 'Error de respuesta del servidor.';
                            loginStatus.className = 'status-msg status-error';
                        }
                    }
                } else {
                    if (loginStatus) {
                        loginStatus.textContent = 'Error de conexión con el servidor.';
                        loginStatus.className = 'status-msg status-error';
                    }
                }
            }
        };

        var formData = new FormData(loginForm);
        formData.append('action', 'login');
        xhr.send(formData);
    });
});
