<?php
global $db;
$repository = new \App\Repositories\PublicationRepository($db);
$posts = $repository->getLatestPublic(10);
?>
<!-- public: carpeta expuesta a la red -->
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrivaNET - Ingreso</title>
    <link rel="icon" type="image/svg+xml" href="public/favicon.svg">
    <link rel="stylesheet" href="public/assets/css/base.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="public/assets/css/header.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="public/assets/css/post.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="public/assets/css/modal.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="public/assets/css/lightbox.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="public/assets/css/activity.css?v=<?php echo time(); ?>">
    <!-- CSS Modular del componente Login -->
    <link rel="stylesheet" href="src/view/Login/login.css?v=<?php echo time(); ?>">
</head>

<body>

    <header class="main-header">
        <div class="header-content">
            <h1 id="site-title"><a href="/PrivaNet/">PrivaNET</a></h1>

            <section class="login-section">
                <form id="login-form" class="login-form" method="POST" action="index.php">
                    <h2>Iniciar sesión</h2>

                    <label for="login-user">Nombre de usuario</label>
                    <input type="text" id="login-user" name="login-user" placeholder="Ingrese su usuario" required>

                    <label for="login-password">Contraseña</label>
                    <div class="password-wrapper">
                        <input type="password" id="login-password" name="login-password" placeholder="Ingrese su contraseña" required>
                        <button type="button" id="toggle-login-password" class="toggle-password-btn" aria-label="Mostrar contraseña" title="Mostrar contraseña">○</button>
                    </div>

                    <button class="header-button" type="submit">Ingresar</button>
                    <div id="login-status" class="status-msg"></div>
                </form>
            </section>
        </div>
    </header>

    <div class="login-page-wrapper container">
        <main class="main-layout">
            <section class="public-feed" id="public-feed">
                <h2>Últimos posteos públicos</h2>

                <div id="posts-container" class="posts-container">
                    <?php if (!empty($posts)): ?>
                        <?php foreach ($posts as $post): ?>
                            <?php 
                            $isLoggedIn = false;
                            include APP_PATH . '/View/components/post_card.php'; 
                            ?>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <div class="post-card post-card--empty">
                            <p class="muted">Aún no hay publicaciones públicas en la base de datos.</p>
                        </div>
                    <?php endif; ?>
                </div>
            </section>
        </main>

        <aside class="register-section">
            <form id="register-form" class="register-form">
                <h2>Registro de nuevos usuarios</h2>

                <label for="register-user">Nombre de usuario</label>
                <input type="text" id="register-user" name="register-user" placeholder="Usuario único" required>
                <span id="username-status" class="status-msg"></span>

                <label for="register-password">Contraseña</label>
                <div class="password-wrapper">
                    <input type="password" id="register-password" name="register-password" placeholder="Mínimo 8 caracteres" minlength="8" required>
                    <button type="button" id="toggle-register-password" class="toggle-password-btn" aria-label="Mostrar contraseña" title="Mostrar contraseña">○</button>
                </div>
                <small class="form-hint">Debe contener al menos 8 caracteres.</small>

                <label for="register-email">Correo electrónico</label>
                <input type="email" id="register-email" name="register-email" placeholder="correo@ejemplo.com" required>

                <label for="register-birthdate">Fecha de nacimiento</label>
                <input type="date" id="register-birthdate" name="register-birthdate" required>
                <small class="form-hint">Debes ser mayor de 13 años.</small>

                <label for="register-country">País de residencia</label>
                <select id="register-country" name="register-country" required>
                    <option value="" disabled selected>Seleccione su país</option>
                    <?php
                    require_once APP_PATH . '/Helpers/countries.php';
                    foreach (getCountries() as $value => $label):
                    ?>
                        <option value="<?php echo htmlspecialchars($value); ?>">
                            <?php echo htmlspecialchars($label); ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <div id="register-status" class="status-msg"></div>
                <button type="submit">Registrarse</button>
            </form>
        </aside>
    </div>

    <!-- Lightbox Modal for Thumbnails -->
    <div id="lightbox-modal" class="lightbox-overlay">
        <div class="lightbox-content">
            <button type="button" class="lightbox-close-btn" id="lightbox-close">✕</button>
            <div class="lightbox-media-container">
                <img id="lightbox-image" class="lightbox-img" src="" alt="Vista ampliada">
            </div>
            <div id="lightbox-post-info" class="lightbox-post-info">
                <header class="lightbox-post-header">
                    <h3 id="lightbox-author">@username</h3>
                </header>
                <div class="lightbox-post-body">
                    <div id="lightbox-post-text" class="lightbox-post-text"></div>
                    <div id="lightbox-audio-container" class="lightbox-audio-container">
                        <audio id="lightbox-audio" controls></audio>
                    </div>
                </div>
                <footer id="lightbox-post-actions" class="lightbox-post-actions">
                </footer>
            </div>
        </div>
    </div>

    <script src="public/assets/js/timeformat.js?v=<?php echo time(); ?>"></script>
    <script src="public/assets/js/lightbox.js?v=<?php echo time(); ?>"></script>
    <script src="src/view/Login/register.js?v=<?php echo time(); ?>"></script>
    <script src="src/view/Login/login.js?v=<?php echo time(); ?>"></script>
</body>

</html>