<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrivaNET - Buscar publicaciones</title>
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
                    <input type="text" name="q" placeholder="Buscar publicaciones, usuarios..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>"
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
                <span style="color: var(--header-text); font-weight: 500; font-size: 0.9rem;">Hola, @usuario_demo</span>
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
        <section class="post-card" style="margin-bottom: 30px;">
            <h2 style="font-size: 1.3rem; margin-bottom: 15px; color: var(--text-main);">Explorar y Buscar Publicaciones</h2>
            <form action="/PrivaNet/buscar" method="GET" style="display: flex; gap: 15px;">
                <input type="text" name="q" placeholder="Escribe palabras clave, hashtags o @usuarios..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" style="flex: 1;">
                <button type="submit">Buscar</button>
            </form>
        </section>

        <section class="results-section">
            <h3 style="font-size: 1.1rem; color: var(--text-muted); margin-bottom: 20px;">Resultados de la búsqueda</h3>
            
            <div id="search-results-container" class="posts-container">
                <?php if (!empty($_GET['q'])): ?>
                    <div class="post-card" style="text-align: center; padding: 40px 20px;">
                        <p style="color: var(--text-main); margin-bottom: 10px;">No se encontraron publicaciones exactas para "<strong><?php echo htmlspecialchars($_GET['q']); ?></strong>".</p>
                        <span style="font-size: 0.85rem; color: var(--text-muted);">(Esta sección se conectará dinámicamente vía AJAX con el backend)</span>
                    </div>
                <?php else: ?>
                    <div class="post-card" style="text-align: center; padding: 40px 20px;">
                        <p style="color: var(--text-muted);">Utiliza la barra de búsqueda superior para encontrar contenido de tu interés.</p>
                    </div>
                <?php endif; ?>
            </div>
        </section>
    </main>

</body>

</html>
