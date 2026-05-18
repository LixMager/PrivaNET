<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrivaNET - Inicio</title>
    <link rel="stylesheet" href="public/inicio.css?v=<?php echo time(); ?>">
    <!-- CSS Modular del componente Homepage -->
    <link rel="stylesheet" href="src/view/Homepage/homepage.css?v=<?php echo time(); ?>">
</head>

<body>

    <header class="main-header" style="padding: 12px 0;">
        <div class="header-content" style="display: flex; align-items: center; justify-content: space-between; gap: 20px; padding: 0 30px; width: 100%;">
            <!-- 1. Logo (Izquierda del todo) -->
            <div class="logo-container" style="flex-shrink: 0;">
                <h1 id="site-title" style="margin: 0;"><a href="/PrivaNet/" style="color: var(--header-text); text-decoration: none;">PrivaNET</a></h1>
            </div>

            <!-- 2. Buscador (A la derecha del logo, flexible con min y max) -->
            <div class="search-bar" style="flex: 1; min-width: 150px; max-width: 450px; margin: 0 10px;">
                <form action="/PrivaNet/buscar" method="GET" style="margin: 0; display: flex; width: 100%;">
                    <input type="text" name="q" placeholder="Buscar publicaciones, usuarios..."
                        style="width: 100%; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 20px; padding: 8px 15px; outline: none;">
                </form>
            </div>

            <!-- 3. Navegación (Centro-Derecha) -->
            <nav class="main-nav" style="display: flex; align-items: center; gap: 15px; flex-shrink: 0;">
                <a href="/PrivaNet/publicar" style="color: var(--header-text); text-decoration: none; font-size: 0.95rem; font-weight: 500;">Realizar post</a>
                <span style="color: rgba(255,255,255,0.3);">│</span>
                <a href="/PrivaNet/actividad" style="color: var(--header-text); text-decoration: none; font-size: 0.95rem; font-weight: 500;">Mi actividad</a>
                <span style="color: rgba(255,255,255,0.3);">│</span>
                <a href="/PrivaNet/panel" style="color: var(--header-text); text-decoration: none; font-size: 0.95rem; font-weight: 500;">Panel de control</a>
            </nav>

            <!-- 4. Usuario y Logout (Derecha del todo) -->
            <div class="user-menu" style="display: flex; flex-direction: column; align-items: flex-end; gap: 5px; flex-shrink: 0;">
                <span style="color: var(--header-text); font-weight: 500; font-size: 0.9rem;">Hola, @<?php echo htmlspecialchars($_COOKIE['user_name'] ?? 'usuario'); ?></span>
                <form method="POST" action="/PrivaNet/index.php" style="margin: 0;">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit"
                        style="background: transparent; border: 1px solid rgba(255,255,255,0.5); color: var(--header-text); font-size: 0.8rem; padding: 4px 10px; border-radius: 6px; cursor: pointer; transition: background 0.2s;"
                        onmouseover="this.style.background='rgba(255,255,255,0.1)'"
                        onmouseout="this.style.background='transparent'">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </header>

    <main class="container main-layout" style="grid-template-columns: 1fr; max-width: 800px; margin: 0 auto;">

        <section class="public-feed" id="public-feed">

            <!-- Mensaje de Bienvenida -->
            <div class="welcome-box post-card" style="margin-bottom: 25px; background: linear-gradient(135deg, var(--bg-surface), var(--bg-body)); border-left: 4px solid var(--primary-color);">
                <h2 style="font-size: 1.5rem; color: var(--text-main); margin-bottom: 8px;">¡Bienvenido a PrivaNET, @<?php echo htmlspecialchars($_COOKIE['user_name'] ?? 'usuario'); ?>!</h2>
                <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.5; margin: 0;">Tu último login fue el (...)</p>
            </div>

            <h2>Últimos posteos</h2>

            <div id="posts-container" class="posts-container">

                <!-- Posteos Dinámicos (creados en la sesión actual) -->
                <?php if (isset($_SESSION['posteos']) && is_array($_SESSION['posteos'])): ?>
                    <?php foreach (array_reverse($_SESSION['posteos']) as $post): ?>
                        <article class="post-card">
                            <header class="post-header">
                                <h3>@<?php echo htmlspecialchars($_COOKIE['user_name'] ?? 'usuario'); ?></h3>
                                <span>Hace un momento</span>
                            </header>

                            <?php if (!empty($post['text'])): ?>
                                <p class="post-text">
                                    <?php echo htmlspecialchars($post['text']); ?>
                                </p>
                            <?php endif; ?>

                            <?php if (!empty($post['image'])): ?>
                                <div class="post-media image-media">
                                    <img src="<?php echo htmlspecialchars($post['image']); ?>" class="post-thumbnail">
                                </div>
                            <?php endif; ?>

                            <?php if (!empty($post['audio'])): ?>
                                <div class="post-media audio-media">
                                    <audio controls>
                                        <source src="<?php echo htmlspecialchars($post['audio']); ?>" type="audio/mpeg">
                                        Tu navegador no soporta audio HTML5.
                                    </audio>
                                </div>
                            <?php endif; ?>

                            <footer class="post-actions">
                                <button type="button" class="action-btn like-btn">🤍 Me gusta</button>
                                <button type="button" class="action-btn fav-btn">☆ Favorito</button>
                            </footer>
                        </article>
                    <?php endforeach; ?>
                <?php endif; ?>

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

                    <div class="post-media audio-media">
                        <audio controls>
                            <source src="#" type="audio/mpeg">
                            Tu navegador no soporta audio HTML5.
                        </audio>
                    </div>

                    <footer class="post-actions">
                        <button type="button" class="action-btn like-btn">🤍 Me gusta</button>
                        <button type="button" class="action-btn fav-btn">☆ Favorito</button>
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
                        <button type="button" class="action-btn like-btn">🤍 Me gusta</button>
                        <button type="button" class="action-btn fav-btn">☆ Favorito</button>
                    </footer>
                </article>
            </div>
        </section>
    </main>

    <!-- Script modular de la página de inicio -->
    <script src="src/view/Homepage/homepage.js?v=<?php echo time(); ?>"></script>
</body>

</html>