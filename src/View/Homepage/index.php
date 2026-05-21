<?php
$page_title = 'PrivaNET - Inicio';

// Obtener las publicaciones reales de la base de datos
global $db;
$repository = new \App\Repositories\PublicationRepository($db);
$currentUserId = $_SESSION['user_id'] ?? null;
$posts = $repository->getLatestPublic(10, $currentUserId);

ob_start();
?>
<main class="container main-layout">

    <section class="public-feed" id="public-feed">

        <!-- Mensaje de Bienvenida -->
        <div class="welcome-box post-card" style="margin-bottom: 25px; background: linear-gradient(135deg, var(--bg-surface), var(--bg-body)); border-left: 4px solid var(--primary-color);">
            <h2 style="font-size: 1.5rem; color: var(--text-main); margin-bottom: 8px;">¡Bienvenido a PrivaNET, @<?php echo htmlspecialchars($_COOKIE['user_name'] ?? 'usuario'); ?>!</h2>
            <p style="color: var(--text-muted); font-size: 0.95rem; line-height: 1.5; margin: 0;">Tu último login fue el: <?php echo htmlspecialchars($_SESSION['mostrar_ultimo_acceso'] ?? 'Esta es tu primera visita en este dispositivo'); ?></p>
        </div>

        <h2>Últimos posteos</h2>

        <div id="posts-container" class="posts-container">

            <?php if (!empty($posts)): ?>
                <?php foreach ($posts as $post): ?>
                    <?php include APP_PATH . '/View/components/post_card.php'; ?>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="post-card" style="text-align: center; padding: 40px 20px;">
                    <p class="muted">Aún no hay publicaciones en la base de datos.</p>
                </div>
            <?php endif; ?>

        </div>
    </section>
</main>

<?php
$page_content = ob_get_clean();
$page_scripts = '<script src="/PrivaNet/public/assets/js/interactions.js?v=' . time() . '"></script>';
include __DIR__ . '/../layouts/base.php';
?>
