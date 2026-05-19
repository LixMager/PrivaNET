<?php
http_response_code(404);
$page_title = 'PrivaNET - Página no encontrada';
ob_start();
?>
<main class="container main-layout">
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
