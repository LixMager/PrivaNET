<!-- public: carpeta expuesta a la red -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrivaNET - Ingreso</title>
    <link rel="stylesheet" href="public/inicio.css?v=<?php echo time(); ?>">
    <!-- CSS Modular del componente Login -->
    <link rel="stylesheet" href="src/view/Login/login.css?v=<?php echo time(); ?>">
</head>

<body>

    <header class="main-header">
        <div class="container header-content">
            <h1 id="site-title">PrivaNET</h1>

            <section class="login-section">
                <!-- Se agregó method POST apuntando al router principal -->
                <form id="login-form" class="login-form" method="POST" action="index.php">
                    <h2>Iniciar sesión</h2>

                    <label for="login-user">Nombre de usuario</label>
                    <input type="text" id="login-user" name="login-user" placeholder="Ingrese su usuario" required>

                    <label for="login-password">Contraseña</label>
                    <input type="password" id="login-password" name="login-password" placeholder="Ingrese su contraseña"
                        required>

                    <button type="submit">Ingresar</button>
                    <div id="login-status" class="status-msg" style="position: absolute; right: 0; bottom: -20px; width: 100%; text-align: right; margin: 0; font-size: 0.8rem;"></div>
                </form>
            </section>
        </div>
    </header>

    <main class="container main-layout">
        <aside class="register-section">
            <form id="register-form" class="register-form">
                <h2>Registro de nuevos usuarios</h2>

                <label for="register-user">Nombre de usuario</label>
                <input type="text" id="register-user" name="register-user" placeholder="Usuario único" required>
                <span id="username-status" class="status-msg"></span>

                <label for="register-password">Contraseña</label>
                <input type="password" id="register-password" name="register-password" placeholder="Mínimo 8 caracteres"
                    minlength="8" required>
                <small class="form-hint">Debe contener al menos 8 caracteres.</small>

                <label for="register-email">Correo electrónico</label>
                <input type="email" id="register-email" name="register-email" placeholder="correo@ejemplo.com" required>

                <label for="register-birthdate">Fecha de nacimiento</label>
                <input type="date" id="register-birthdate" name="register-birthdate" required>
                <small class="form-hint">Debes ser mayor de 13 años.</small>

                <label for="register-country">País de residencia</label>
                <input type="text" id="register-country" name="register-country" placeholder="Ingrese su país" required>

                <div id="register-status" class="status-msg"></div>
                <button type="submit">Registrarse</button>
            </form>
        </aside>

        <section class="public-feed" id="public-feed">
            <h2>Últimos posteos públicos</h2>

            <div id="posts-container" class="posts-container">
                <article class="post-card" id="post-1">
                    <header class="post-header">
                        <h3>@usuario_demo</h3>
                        <span>Hace 10 minutos</span>
                    </header>

                    <p class="post-text">
                        ¡Increíble viaje por Trevelin! 🌷🏔️ Los campos de tulipanes en la Patagonia son verdaderamente
                        un paraíso terrenal. Totalmente recomendado.
                    </p>

                    <div class="post-media image-media">
                        <img src="assets/uploads/users/1/posts/1/trevelin.jpg" alt="Campo de tulipanes en Trevelin"
                            class="post-thumbnail">
                    </div>

                    <footer class="post-actions">
                        <button type="button" class="action-btn" onclick="alert('Inicia sesión para interactuar')">🤍 Me
                            gusta</button>
                        <button type="button" class="action-btn" onclick="alert('Inicia sesión para interactuar')">☆
                            Favorito</button>
                    </footer>
                </article>

                <article class="post-card" id="post-2">
                    <header class="post-header">
                        <h3>@otro_usuario</h3>
                        <span>Hace 30 minutos</span>
                    </header>

                    <p class="post-text">
                        Otro ejemplo de publicación visible para cualquier visitante no registrado.
                    </p>

                    <footer class="post-actions">
                        <button type="button" class="action-btn" onclick="alert('Inicia sesión para interactuar')">🤍 Me
                            gusta</button>
                        <button type="button" class="action-btn" onclick="alert('Inicia sesión para interactuar')">☆
                            Favorito</button>
                    </footer>
                </article>
            </div>
        </section>
    </main>

    <script src="src/view/Login/register.js?v=<?php echo time(); ?>"></script>
    <script src="src/view/Login/login.js?v=<?php echo time(); ?>"></script>
</body>

</html>