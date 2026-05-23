<?php
$page_title = 'PrivaNET - Buscar publicaciones';

global $db;
$repository = new \App\Repositories\PublicationRepository($db);
$currentUserId = $_SESSION['user_id'] ?? null;
$queryText = trim($_GET['q'] ?? '');

$posts = [];
if (!empty($queryText)) {
    $posts = $repository->search($queryText, $currentUserId);
}

ob_start();
?>
<main class="container main-layout">
    <section class="results-section">
        <h2 style="font-size: 1.3rem; margin-bottom: 20px; color: var(--text-main);">
            <?php if (!empty($queryText)): ?>
                Resultados para "<strong><?php echo htmlspecialchars($queryText); ?></strong>"
            <?php else: ?>
                Buscar publicaciones
            <?php endif; ?>
        </h2>
        
        <div id="search-results-container" class="posts-container">
            <?php if (!empty($queryText)): ?>
                <?php if (!empty($posts)): ?>
                    <?php foreach ($posts as $post): ?>
                        <?php include APP_PATH . '/View/components/search_result_card.php'; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <div class="post-card" style="text-align: center; padding: 40px 20px;">
                        <p style="color: var(--text-muted); margin-bottom: 5px;">No se encontraron publicaciones que coincidan con tu búsqueda.</p>
                        <span class="muted small" style="font-size: 0.85rem; color: var(--text-muted);">Prueba con otras palabras clave o términos más generales.</span>
                    </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="post-card" style="text-align: center; padding: 40px 20px;">
                    <p class="muted">Utiliza la barra de búsqueda superior para encontrar contenido de tu interés.</p>
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
