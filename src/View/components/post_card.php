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

    <?php if (!empty($post->getText())): ?>
        <p class="post-text" data-post-text-content="<?php echo $post->getId(); ?>">
            <?php echo htmlspecialchars($post->getText()); ?>
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
                    ✏️ Editar
                </button>
                <button type="button" class="action-btn delete-post-btn" data-post-id="<?php echo $post->getId(); ?>">
                    🗑️ Eliminar
                </button>
            <?php else: ?>
                <button type="button" class="action-btn like-btn <?php echo $post->getIsLiked() ? 'active' : ''; ?>" data-post-id="<?php echo $post->getId(); ?>">
                    <?php echo $post->getIsLiked() ? '❤️ Te gusta' : '🤍 Me gusta'; ?>
                </button>
                <button type="button" class="action-btn dislike-btn <?php echo $post->getIsDisliked() ? 'active' : ''; ?>" data-post-id="<?php echo $post->getId(); ?>">
                    <?php echo $post->getIsDisliked() ? '👎 Te disgusta' : '👎 No me gusta'; ?>
                </button>
                <button type="button" class="action-btn fav-btn <?php echo $post->getIsFavorited() ? 'active' : ''; ?>" data-post-id="<?php echo $post->getId(); ?>">
                    <?php echo $post->getIsFavorited() ? '★ Favorito' : '☆ Favorito'; ?>
                </button>
            <?php endif; ?>
        <?php else: ?>
            <button type="button" class="action-btn" onclick="alert('Inicia sesión para interactuar')">🤍 Me gusta</button>
            <button type="button" class="action-btn" onclick="alert('Inicia sesión para interactuar')">👎 No me gusta</button>
            <button type="button" class="action-btn" onclick="alert('Inicia sesión para interactuar')">☆ Favorito</button>
        <?php endif; ?>
    </footer>
</article>
