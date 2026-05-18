<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrivaNET - Realizar publicación</title>
    <link rel="stylesheet" href="public/inicio.css?v=<?php echo time(); ?>">
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
                <a href="/PrivaNet/publicar" style="color: var(--header-text); text-decoration: underline; font-size: 0.95rem; font-weight: 600;">Realizar post</a>
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

    <main class="container main-layout" style="grid-template-columns: 1fr; max-width: 700px; margin: 0 auto;">
        <div class="create-post-box">
            <h2 style="font-size: 1.3rem; margin-bottom: 15px; color: var(--text-main); border-bottom: 1px solid var(--border-color); padding-bottom: 10px;">Crear Nueva Publicación</h2>
            <form action="/PrivaNet/index.php" method="POST" enctype="multipart/form-data">
                <input type="hidden" name="action" value="create_post">

                <textarea name="post_text" placeholder="¿Qué estás pensando, @<?php echo htmlspecialchars($_COOKIE['user_name'] ?? 'usuario'); ?>?"></textarea>

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

                <div id="image-preview-container" style="display: none; margin-top: 15px; border-top: 1px solid var(--border-color); padding-top: 15px;">
                    <img id="image-preview" src="" alt="Previsualización" style="max-height: 200px; border-radius: 8px; border: 1px solid var(--border-color); object-fit: cover;">
                    <button type="button" id="remove-image-btn" style="display: block; margin-top: 5px; background: transparent; border: none; color: #ef4444; font-size: 0.85rem; cursor: pointer;">❌ Quitar imagen</button>
                </div>

                <div id="audio-preview-container" style="display: none; margin-top: 15px; border-top: 1px solid var(--border-color); padding-top: 15px;">
                    <audio id="audio-preview" controls style="width: 100%; border-radius: var(--radius);"></audio>
                    <button type="button" id="remove-audio-btn" style="display: block; margin-top: 5px; background: transparent; border: none; color: #ef4444; font-size: 0.85rem; cursor: pointer;">❌ Quitar audio</button>
                </div>
            </form>
        </div>
    </main>

    <script src="src/view/Homepage/homepage.js?v=<?php echo time(); ?>"></script>
</body>

</html>
