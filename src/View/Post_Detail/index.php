<?php
$page_title = 'PrivaNET - Detalle de Publicación';

global $db;
$repository = new \App\Repositories\PublicationRepository($db);
$currentUserId = $_SESSION['user_id'] ?? null;
$postId = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$post = null;
if ($postId > 0) {
    $post = $repository->getById($postId, $currentUserId);
}

ob_start();
?>
<main class="container main-layout">
    <section class="detail-section">
        <div style="margin-bottom: 20px;">
            <a href="javascript:history.back()" class="action-btn" style="text-decoration: none; display: inline-flex; align-items: center; gap: 5px; color: var(--text-main);">
                ← Volver
            </a>
        </div>
        
        <div class="posts-container">
            <?php if ($post): ?>
                <?php include APP_PATH . '/View/components/post_card.php'; ?>
            <?php else: ?>
                <div class="post-card" style="text-align: center; padding: 40px 20px;">
                    <p style="color: var(--text-muted); margin-bottom: 5px;">La publicación no existe o no tienes permiso para verla.</p>
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
