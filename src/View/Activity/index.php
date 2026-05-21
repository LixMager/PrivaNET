<?php
$page_title = 'PrivaNET - Mi Actividad';
ob_start();
?>
<main class="container main-layout">
    <div class="post-card" style="margin-bottom: 20px;">
        <h2>Registro de Mi Actividad</h2>
        <p class="muted">Aquí puedes consultar todas las publicaciones con las que has interactuado recientemente.</p>
    </div>

    <div class="activity-tabs">
        <button type="button" class="activity-tab active" data-type="like">🤍 Me gusta</button>
        <button type="button" class="activity-tab" data-type="dislike">👎 No me gusta</button>
        <button type="button" class="activity-tab" data-type="favorite">★ Favoritos</button>
    </div>

    <div id="activity-content" class="posts-container">
        <div class="post-card" style="text-align: center; padding: 40px 20px;">
            <p class="muted">Cargando tu actividad...</p>
        </div>
    </div>
</main>

<?php
$page_content = ob_get_clean();
$page_scripts = '<script src="/PrivaNet/public/assets/js/interactions.js?v=' . time() . '"></script>';
include __DIR__ . '/../layouts/base.php';
?>
