<?php
$page_title = 'PrivaNET - Panel de Control';

global $db;
$repository = new \App\Repositories\PublicationRepository($db);
$currentUserId = $_SESSION['user_id'] ?? null;
if (!$currentUserId) {
    header('Location: /PrivaNet/index.php');
    exit;
}
$posts = $repository->getPublicationsByUser((int)$currentUserId);

ob_start();
?>
<main class="container main-layout">
    <div class="post-card" style="margin-bottom: 20px; display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h2 style="font-size: 1.3rem; margin-bottom: 5px; color: var(--text-main);">Panel de Gestión de Publicaciones</h2>
            <p style="color: var(--text-muted); font-size: 0.9rem;">Administra tus posteos, realiza modificaciones de texto o elimina publicaciones de forma definitiva.</p>
        </div>
    </div>

    <div class="posts-container" id="dashboard-posts-container">
        <?php if (!empty($posts)): ?>
            <?php foreach ($posts as $post): ?>
                <?php 
                $isDashboard = true;
                include APP_PATH . '/View/components/post_card.php'; 
                ?>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="post-card" style="text-align: center; padding: 40px 20px;">
                <p style="color: var(--text-muted);">Aún no has realizado ninguna publicación.</p>
            </div>
        <?php endif; ?>
    </div>
</main>

<!-- Modal de Edición -->
<div id="edit-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Editar Publicación</h3>
            <button type="button" class="modal-close-btn" id="close-edit-modal">&times;</button>
        </div>
        <form id="edit-post-form">
            <input type="hidden" name="action" value="update_post">
            <input type="hidden" name="post_id" id="edit-post-id" value="">
            <div class="modal-body">
                <textarea name="post_text" id="edit-post-text" placeholder="¿Qué estás pensando?"></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="action-btn secondary-btn" id="cancel-edit-btn">Cancelar</button>
                <button type="submit" class="action-btn" id="save-edit-btn" style="background: var(--primary-color); color: white;">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal de Eliminación -->
<div id="delete-modal" class="modal-overlay">
    <div class="modal-content">
        <div class="modal-header">
            <h3>Confirmar Eliminación</h3>
            <button type="button" class="modal-close-btn" id="close-delete-modal">&times;</button>
        </div>
        <div class="modal-body">
            <p style="color: var(--text-main); margin-bottom: 10px; font-weight: 500;">¿Estás seguro de que deseas eliminar esta publicación?</p>
            <p style="color: var(--text-muted); font-size: 0.9rem; line-height: 1.4;">Esta acción es irreversible y eliminará de forma permanente el texto, las imágenes o audios asociados, así como también los me gusta, no me gusta y favoritos que haya recibido.</p>
        </div>
        <div class="modal-footer">
            <input type="hidden" id="delete-post-id" value="">
            <button type="button" class="action-btn secondary-btn" id="cancel-delete-btn">Cancelar</button>
            <button type="button" class="action-btn danger-btn" id="confirm-delete-btn">Eliminar Publicación</button>
        </div>
    </div>
</div>

<?php
$page_content = ob_get_clean();
$page_scripts = '<script src="/PrivaNet/public/assets/js/dashboard.js?v=' . time() . '"></script>';
include __DIR__ . '/../layouts/base.php';
?>
