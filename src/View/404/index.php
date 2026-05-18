<?php
http_response_code(404);
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PrivaNET - Página no encontrada</title>
    <link rel="stylesheet" href="public/inicio.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="src/view/Homepage/homepage.css?v=<?php echo time(); ?>">
</head>

<body>

    <header class="main-header">
        <div class="container header-content">
            <h1 id="site-title">PrivaNET</h1>
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
                        <a href="index.php" style="display: inline-block; background: var(--primary-color); color: white; padding: 12px 25px; border-radius: var(--radius); text-decoration: none; font-weight: 500;">
                            Volver al inicio
                        </a>
                    </footer>
                </article>
            </div>
        </section>

    </main>

</body>
</html>
