<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrivaNET</title>
    <link rel="stylesheet" href="inicio.css?v=<?php echo time(); ?>">
</head>

<body>

    <header class="main-header">
        <div class="container header-content">
            <h1 id="site-title">PrivaNET</h1>

            <section class="login-section">
                <form id="login-form" class="login-form">
                    <h2>Iniciar sesión</h2>

                    <label for="login-user">Nombre de usuario</label>
                    <input type="text" id="login-user" name="login-user" placeholder="Ingrese su usuario" required>

                    <label for="login-password">Contraseña</label>
                    <input type="password" id="login-password" name="login-password" placeholder="Ingrese su contraseña"required>

                    <button type="submit">Ingresar</button>
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

                <label for="register-password">Contraseña</label>
                <input type="password" id="register-password" name="register-password" placeholder="Mínimo 8 caracteres"
                    minlength="8" required>

                <label for="register-email">Correo electrónico</label>
                <input type="email" id="register-email" name="register-email" placeholder="correo@ejemplo.com" required>

                <label for="register-birthdate">Fecha de nacimiento</label>
                <input type="date" id="register-birthdate" name="register-birthdate" required>

                <label for="register-country">País de residencia</label>
                <input type="text" id="register-country" name="register-country" placeholder="Ingrese su país" required>

                <button type="submit">Registrarse</button>
            </form>
        </aside>

        <section class="public-feed" id="public-feed">
            <h2>Últimos 10 posteos públicos</h2>

            <div id="posts-container" class="posts-container">

                <article class="post-card" id="post-1">
                    <header class="post-header">
                        <h3>@usuario_demo</h3>
                        <span>Hace 10 minutos</span>
                    </header>

                    <p class="post-text">
                        Este es un ejemplo de publicación pública con texto descriptivo de hasta 255 caracteres.
                    </p>

                    <div class="post-media image-media">
                        <img src="https://via.placeholder.com/150" alt="Imagen de ejemplo del post"
                            class="post-thumbnail">
                    </div>

                    <div class="post-media audio-media">
                        <audio controls>
                            <source src="#" type="audio/mpeg">
                            Tu navegador no soporta audio HTML5.
                        </audio>
                    </div>
                </article>

                <article class="post-card" id="post-2">
                    <header class="post-header">
                        <h3>@otro_usuario</h3>
                        <span>Hace 30 minutos</span>
                    </header>

                    <p class="post-text">
                        Otro ejemplo de publicación visible para cualquier visitante no registrado.
                    </p>
                </article>
            </div>
        </section>
    </main>
</body>
</html>


