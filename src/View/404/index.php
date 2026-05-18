<?php

http_response_code(404);

// Usar la constante BASE_PATH definida en index.php, o calcularla si se accede directo
if (!defined('BASE_PATH')) {
    $current_dir = str_replace('\\', '/', __DIR__);
    $doc_root = str_replace('\\', '/', $_SERVER['DOCUMENT_ROOT']);
    $project_path = str_replace($doc_root, '', $current_dir);
    $base = rtrim(str_replace('/public/view/404', '', $project_path), '/') . '/';
} else {
    $base = BASE_PATH;
}

$is_logged_in = isset($_SESSION['usuario_autenticado']) && $_SESSION['usuario_autenticado'] === true;
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrivaNET - Página no encontrada</title>
    <!-- Usamos la ruta base absoluta para que los estilos carguen siempre -->
    <link rel="stylesheet" href="<?php echo $base; ?>inicio.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="<?php echo $base; ?>private/view/Homepage/homepage.css?v=<?php echo time(); ?>">
</head>

<body>

    <header class="main-header">
        <div class="container header-content">
            <h1 id="site-title">PrivaNET</h1>

            <?php if ($is_logged_in): ?>
                <div class="search-bar" style="flex: 1; max-width: 400px; margin: 0 20px;">
                    <form action="#" method="GET" style="display: flex; width: 100%;">
                        <input type="text" name="q" placeholder="Buscar publicaciones, usuarios..." style="width: 100%; background: rgba(255,255,255,0.1); border: 1px solid rgba(255,255,255,0.2); color: white; border-radius: 20px; padding: 8px 15px; outline: none;">
                    </form>
                </div>

                <div class="user-menu" style="display: flex; align-items: center; gap: 15px;">
                    <span style="color: var(--header-text); font-weight: 500;">Hola, @usuario_demo</span>
                    <form method="POST" action="<?php echo $base; ?>index.php" style="margin: 0;">
                        <input type="hidden" name="action" value="logout">
                        <button type="submit" style="background: transparent; border: 1px solid rgba(255,255,255,0.5); color: var(--header-text); font-size: 0.85rem; padding: 6px 12px; border-radius: 6px; cursor: pointer; transition: background 0.2s;">Cerrar sesión</button>
                    </form>
                </div>
            <?php endif; ?>
        </div>
    </header>

    <main class="container main-layout" style="grid-template-columns: 1fr; max-width: 800px; margin: 0 auto;">
        
        <section class="public-feed">
            <div class="posts-container">
                <article class="post-card" style="text-align: center; padding: 60px 20px;">
                    <header class="post-header" style="justify-content: center; border-bottom: none;">
                        <h2 style="font-size: 5rem; color: var(--primary-color); margin: 0;">404</h2>
                    </header>

                    <div class="post-content" style="margin: 20px 0;">
                        <h3 style="font-size: 1.5rem; color: var(--text-main); margin-bottom: 10px;">¡Ups! Página no encontrada</h3>
                        <p class="post-text" style="font-size: 1.1rem; color: var(--text-muted);">
                            Lo sentimos, pero la página que estás buscando no existe o ha sido movida.
                        </p>
                    </div>

                    <footer class="post-actions" style="justify-content: center; border-top: none;">
                        <a href="<?php echo $base; ?>index.php" style="display: inline-block; background: var(--primary-color); color: white; padding: 12px 25px; border-radius: var(--radius); text-decoration: none; font-weight: 500;">
                            Volver al inicio
                        </a>
                    </footer>
                </article>
            </div>
        </section>

    </main>

</body>
</html>
