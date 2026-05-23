<?php
/**
 * Tarjeta compacta para resultados de búsqueda.
 * Variables esperadas: $post (App\Models\Publication)
 */
$textRaw  = strip_tags($post->getText() ?? '');
$firstLine = mb_strimwidth($textRaw, 0, 120, '…');

$dateStr = '';
$utcIso = '';
if ($post->getCreatedAt()) {
    $d = new DateTime($post->getCreatedAt());
    $utcD = clone $d;
    $utcD->setTimezone(new DateTimeZone('UTC'));
    $utcIso = $utcD->format('c');
    $dateStr = $d->format('d/m/Y H:i');
}

$imageSrc = $post->getImage() ? '/PrivaNet/' . htmlspecialchars($post->getImage()) : '';
$audioSrc = $post->getAudio() ? '/PrivaNet/' . htmlspecialchars($post->getAudio()) : '';
$postText  = htmlspecialchars(\App\Helpers\SanitizerHelper::sanitize($post->getText() ?? ''));
?>
<article class="search-result-card"
         data-post-id="<?php echo $post->getId(); ?>"
         data-author="@<?php echo htmlspecialchars($post->getUsername() ?? 'usuario'); ?>"
         data-image="<?php echo $imageSrc; ?>"
         data-audio="<?php echo $audioSrc; ?>"
         data-text="<?php echo $postText; ?>"
         data-liked="<?php echo $post->getIsLiked() ? '1' : '0'; ?>"
         data-disliked="<?php echo $post->getIsDisliked() ? '1' : '0'; ?>"
         data-favorited="<?php echo $post->getIsFavorited() ? '1' : '0'; ?>"
         role="button"
         tabindex="0"
         title="Ver publicación completa">

    <div class="src-meta">
        <span class="src-author">@<?php echo htmlspecialchars($post->getUsername() ?? 'usuario'); ?></span>
        <?php if ($utcIso): ?>
            <time class="local-time src-date" data-utc="<?php echo $utcIso; ?>"><?php echo $dateStr; ?></time>
        <?php else: ?>
            <span class="src-date"><?php echo $dateStr; ?></span>
        <?php endif; ?>
    </div>

    <?php if (!empty($firstLine)): ?>
        <p class="src-preview"><?php echo htmlspecialchars($firstLine); ?></p>
    <?php endif; ?>

    <div class="src-indicators">
        <?php if ($imageSrc): ?>
            <span class="src-badge">🖼 Imagen</span>
        <?php endif; ?>
        <?php if ($audioSrc): ?>
            <span class="src-badge">🎵 Audio</span>
        <?php endif; ?>
    </div>
</article>
