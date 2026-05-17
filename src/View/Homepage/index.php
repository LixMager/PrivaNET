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

    <header class="main-header">
        <div class="container header-content">
            <h1 id="site-title">PrivaNET</h1>

            <div class="search-bar" style="flex: 1; max-width: 400px; margin: 0 20px;">
                <form action="#" method="GET" style="display: flex; width: 100%;">
                    <input type="text" name="q" placeholder="Buscar publicaciones, usuarios..."
                        style="width: 100%; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 20px; padding: 8px 15px; outline: none;">
                </form>
            </div>

            <div class="user-menu" style="display: flex; align-items: center; gap: 15px;">
                <span style="color: var(--header-text); font-weight: 500;">Hola, @usuario_demo</span>

                <!-- Botón de Logout -->
                <form method="POST" action="index.php" style="margin: 0;">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit"
                        style="background: transparent; border: 1px solid rgba(255,255,255,0.5); color: var(--header-text); font-size: 0.85rem; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: background 0.2s;"
                        onmouseover="this.style.background='rgba(255,255,255,0.1)'"
                        onmouseout="this.style.background='transparent'">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </header>

    <main class="container main-layout" style="grid-template-columns: 1fr; max-width: 800px; margin: 0 auto;">

        <section class="public-feed" id="public-feed">

            <!-- Caja de Creación de Posteo -->
            <div class="create-post-box">
                <form action="index.php" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="create_post">

                    <textarea name="post_text" placeholder="¿Qué estás pensando, @usuario_demo?"></textarea>

                    <div class="create-post-actions">
                        <div class="media-inputs">
                            <label class="media-btn">
                                📷 Añadir foto
                                <input type="file" name="post_image" id="post-image-input" accept="image/*">
                            </label>
                            <label class="media-btn">
                                🎵 Añadir audio
                                <input type="file" name="post_audio" id="post-audio-input" accept="audio/*">
                            </label>
                        </div>
                        <button type="submit" class="submit-post-btn">Publicar</button>
                    </div>

                    <!-- Contenedor dinámico donde JS inyectará la previsualización de Imagen -->
                    <div id="image-preview-container"
                        style="display: none; margin-top: 15px; border-top: 1px solid var(--border-color); padding-top: 15px;">
                        <img id="image-preview" src="" alt="Previsualización"
                            style="max-height: 200px; border-radius: 8px; border: 1px solid var(--border-color); object-fit: cover;">
                        <button type="button" id="remove-image-btn"
                            style="display: block; margin-top: 5px; background: transparent; border: none; color: #ef4444; font-size: 0.85rem; cursor: pointer;">❌
                            Quitar imagen</button>
                    </div>

                    <!-- Contenedor dinámico donde JS inyectará la previsualización de Audio -->
                    <div id="audio-preview-container"
                        style="display: none; margin-top: 15px; border-top: 1px solid var(--border-color); padding-top: 15px;">
                        <audio id="audio-preview" controls style="width: 100%; border-radius: var(--radius);"></audio>
                        <button type="button" id="remove-audio-btn"
                            style="display: block; margin-top: 5px; background: transparent; border: none; color: #ef4444; font-size: 0.85rem; cursor: pointer;">❌
                            Quitar audio</button>
                    </div>
                </form>
            </div>

            <h2>Últimos posteos</h2>

            <div id="posts-container" class="posts-container">

                <!-- Posteos Dinámicos (creados en la sesión actual) -->
                <?php if (isset($_SESSION['posteos']) && is_array($_SESSION['posteos'])): ?>
                    <?php foreach (array_reverse($_SESSION['posteos']) as $post): ?>
                        <article class="post-card">
                            <header class="post-header">
                                <h3>@usuario_demo</h3>
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