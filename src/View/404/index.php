<?php
http_response_code(404);
$page_title = 'PrivaNET - Página no encontrada';
ob_start();
?>
<main class="container main-layout">
    <section class="public-feed">
        <div class="posts-container">
            <article class="post-card error-page">
                <header class="post-header">
                    <h2 class="error-page-code">404</h2>
                </header>

                <div class="error-page-content">
                    <h3 class="error-page-title">¡Ups! Página no encontrada</h3>
                    <p class="post-text error-page-text">
                        Lo sentimos, pero la página que estás buscando no existe o ha sido movida.
                    </p>
                </div>

                <footer class="post-actions">
                    <a href="index.php" class="primary-btn">Volver al inicio</a>
                </footer>
            </article>
        </div>
    </section>
</main>

<?php
$page_content = ob_get_clean();
include __DIR__ . '/../layouts/base.php';
?>
