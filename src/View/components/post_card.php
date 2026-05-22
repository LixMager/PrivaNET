<?php
/**
 * Componente Reutilizable para Tarjetas de Publicación (Post Cards)
 * Variables esperadas:
 * - $post (App\Models\Publication)
 * - $isLoggedIn (bool, opcional, por defecto true)
 * - $isDashboard (bool, opcional, por defecto false)
 */
$isLoggedIn = $isLoggedIn ?? true;
$isDashboard = $isDashboard ?? false;

$isScheduled = false;
if ($post->getPublishedAt()) {
    $publishedTime = strtotime($post->getPublishedAt());
    if ($publishedTime > time()) {
        $isScheduled = true;
    }
}
?>
<article class="post-card" data-post-card-id="<?php echo $post->getId(); ?>">
    <header class="post-header">
        <h3>@<?php echo htmlspecialchars($post->getUsername() ?? 'usuario'); ?></h3>
        <span>
            <?php 
                if ($post->getCreatedAt()) {
                    $date = new DateTime($post->getCreatedAt());
                    echo htmlspecialchars($date->format('d/m/Y H:i'));
                } else {
                    echo 'Hace un momento';
                }
            ?>
        </span>
    </header>

    <?php if ($isDashboard && $isScheduled): ?>
        <div class="scheduled-badge" style="background: rgba(59, 130, 246, 0.1); border: 1px dashed var(--primary-color, #3b82f6); color: var(--primary-color, #3b82f6); padding: 8px 12px; border-radius: 6px; font-size: 0.85rem; font-weight: 500; display: flex; align-items: center; gap: 6px; margin: 10px 15px 5px 15px; width: fit-content;">
            <span>◷</span>
            <span>Programado para el <?php 
                $pubDate = new DateTime($post->getPublishedAt());
                echo htmlspecialchars($pubDate->format('d/m/Y \a \l\a\s H:i'));
            ?></span>
        </div>
    <?php endif; ?>

    <?php if (!empty($post->getText())): ?>
        <p class="post-text" data-post-text-content="<?php echo $post->getId(); ?>">
            <?php echo \App\Helpers\SanitizerHelper::sanitize($post->getText()); ?>
        </p>
    <?php endif; ?>

    <?php if (!empty($post->getImage())): ?>
        <div class="post-media image-media">
            <img src="/PrivaNet/<?php echo htmlspecialchars($post->getImage()); ?>" class="post-thumbnail" alt="Imagen del posteo">
        </div>
    <?php endif; ?>

    <?php if (!empty($post->getAudio())): ?>
        <div class="post-media audio-media">
            <audio controls style="width: 100%;">
                <source src="/PrivaNet/<?php echo htmlspecialchars($post->getAudio()); ?>" type="audio/mpeg">
                Tu navegador no soporta audio HTML5.
            </audio>
        </div>
    <?php endif; ?>

    <footer class="post-actions">
        <?php if ($isLoggedIn): ?>
            <?php if ($isDashboard): ?>
                <button type="button" class="action-btn edit-post-btn" data-post-id="<?php echo $post->getId(); ?>" data-post-text="<?php echo htmlspecialchars($post->getText()); ?>">
                    ✎ Editar
                </button>
                <button type="button" class="action-btn delete-post-btn" data-post-id="<?php echo $post->getId(); ?>">
                    ✕ Eliminar
                </button>
            <?php else: ?>
                <button type="button" class="action-btn like-btn <?php echo $post->getIsLiked() ? 'active' : ''; ?>" data-post-id="<?php echo $post->getId(); ?>">
                    <?php echo $post->getIsLiked() ? '▲ Te gusta' : '△ Me gusta'; ?>
                </button>
                <button type="button" class="action-btn dislike-btn <?php echo $post->getIsDisliked() ? 'active' : ''; ?>" data-post-id="<?php echo $post->getId(); ?>">
                    <?php echo $post->getIsDisliked() ? '▼ Te disgusta' : '▽ No me gusta'; ?>
                </button>
                <button type="button" class="action-btn fav-btn <?php echo $post->getIsFavorited() ? 'active' : ''; ?>" data-post-id="<?php echo $post->getId(); ?>">
                    <?php echo $post->getIsFavorited() ? '★ Favorito' : '☆ Favorito'; ?>
                </button>
            <?php endif; ?>
        <?php else: ?>
            <button type="button" class="action-btn" onclick="alert('Inicia sesión para interactuar')">△ Me gusta</button>
            <button type="button" class="action-btn" onclick="alert('Inicia sesión para interactuar')">▽ No me gusta</button>
            <button type="button" class="action-btn" onclick="alert('Inicia sesión para interactuar')">☆ Favorito</button>
        <?php endif; ?>
    </footer>
</article>
