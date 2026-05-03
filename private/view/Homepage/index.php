<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrivaNET - Inicio</title>
    <link rel="stylesheet" href="inicio.css?v=<?php echo time(); ?>">
</head>

<body>

    <header class="main-header">
        <div class="container header-content">
            <h1 id="site-title">PrivaNET</h1>

            <div class="search-bar" style="flex: 1; max-width: 400px; margin: 0 20px;">
                <form action="#" method="GET" style="display: flex; width: 100%;">
                    <input type="text" name="q" placeholder="Buscar publicaciones, usuarios..." style="width: 100%; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 20px; padding: 8px 15px; outline: none;">
                </form>
            </div>

            <div class="user-menu" style="display: flex; align-items: center; gap: 15px;">
                <span style="color: var(--text-main); font-weight: 500;">Hola, @usuario_demo</span>
                
                <!-- Botón de Logout -->
                <form method="POST" action="index.php" style="margin: 0;">
                    <input type="hidden" name="action" value="logout">
                    <button type="submit" style="background: transparent; border: 1px solid var(--border-color); color: var(--text-main); font-size: 0.85rem; padding: 6px 12px; border-radius: 6px; cursor: pointer;">Cerrar sesión</button>
                </form>
            </div>
        </div>
    </header>

    <main class="container main-layout" style="grid-template-columns: 1fr; max-width: 800px; margin: 0 auto;">

        <section class="public-feed" id="public-feed">
            <h2>Últimos posteos</h2>

            <div id="posts-container" class="posts-container">

                <article class="post-card" id="post-1">
                    <header class="post-header">
                        <h3>@usuario_demo</h3>
                        <span>Hace 10 minutos</span>
                    </header>

                    <p class="post-text">
                        ¡Increíble viaje por Trevelin! 🌷🏔️ Los campos de tulipanes en la Patagonia son verdaderamente un paraíso terrenal. Totalmente recomendado.
                    </p>

                    <div class="post-media image-media">
                        <img src="assets/uploads/users/1/posts/1/trevelin.jpg" alt="Campo de tulipanes en Trevelin" class="post-thumbnail">
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
</body>
</html>
