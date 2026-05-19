<?php
$page_title = 'PrivaNET - Inicio';
ob_start();
?>
<main class="container main-layout">

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

<?php
$page_content = ob_get_clean();
include __DIR__ . '/../layouts/base.php';
?>
