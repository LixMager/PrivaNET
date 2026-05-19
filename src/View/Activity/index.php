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
        <button type="button" class="tab primary">🤍 Me gusta</button>
        <button type="button" class="tab">👎 No me gusta</button>
        <button type="button" class="tab">☆ Favoritos</button>
    </div>

    <div id="activity-content" class="posts-container">
        <div class="post-card" style="text-align: center; padding: 40px 20px;">
            <p class="muted">Aún no hay interacciones registradas en esta sección.</p>
            <span class="muted small">(Esta vista se conectará dinámicamente vía AJAX con la base de datos)</span>
        </div>
    </div>
</main>

<?php
$page_content = ob_get_clean();
include __DIR__ . '/../layouts/base.php';
?>
