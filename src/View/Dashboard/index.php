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

<!-- Quill WYSIWYG Editor Assets para Modal de Edición -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

<style>
    .ql-toolbar.ql-snow {
        border: 1px solid var(--border-color, #ccc) !important;
        border-top-left-radius: 8px;
        border-top-right-radius: 8px;
        background: var(--bg-hover, rgba(0,0,0,0.02)) !important;
        padding: 8px 12px !important;
    }
    .ql-container.ql-snow {
        border: 1px solid var(--border-color, #ccc) !important;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
        border-top: none !important;
        font-family: inherit !important;
        font-size: 0.95rem !important;
        background: var(--bg-card, #fff) !important;
    }
    .ql-editor {
        min-height: 120px;
        max-height: 250px;
        color: var(--text-color, #333) !important;
    }
    .ql-editor.ql-blank::before {
        color: var(--text-muted, #777) !important;
        font-style: italic !important;
        left: 15px !important;
    }
</style>

<!-- Modal de Edición -->
<div id="edit-modal" class="modal-overlay">
    <div class="modal-content" style="max-width: 600px;">
        <div class="modal-header">
            <h3>Editar Publicación</h3>
            <button type="button" class="modal-close-btn" id="close-edit-modal">✕</button>
        </div>
        <form id="edit-post-form" enctype="multipart/form-data">
            <input type="hidden" name="action" value="update_post">
            <input type="hidden" name="post_id" id="edit-post-id" value="">
            <div class="modal-body">
                <input type="hidden" name="post_text" id="edit-post-text-hidden">
                <div id="edit-editor-container" data-placeholder="¿Qué estás pensando?"></div>
                
                <div class="modal-media-section" style="margin-top: 15px; border-top: 1px solid var(--border-color, #ccc); padding-top: 15px;">
                    <!-- Image Edit Area -->
                    <div class="media-edit-group" style="margin-bottom: 15px;">
                        <label style="font-weight: 500; font-size: 0.9rem; display: block; margin-bottom: 5px; color: var(--text-main);">Imagen de la publicación:</label>
                        <div id="edit-image-preview-container" style="display: none; margin-bottom: 8px;">
                            <img id="edit-image-preview" src="" style="max-height: 120px; border-radius: 6px; display: block; margin-bottom: 5px;" alt="Preview">
                            <label style="display: inline-flex; align-items: center; gap: 5px; font-size: 0.85rem; color: #ff4d4d; cursor: pointer;">
                                <input type="checkbox" name="delete_image" id="edit-delete-image-checkbox" value="1">
                                Eliminar imagen actual
                            </label>
                        </div>
                        <div class="media-inputs">
                            <label class="media-btn">▣ ⛶ Cambiar foto
                                <input type="file" name="post_image" id="edit-image-input" accept="image/*">
                            </label>
                        </div>
                    </div>
                    
                    <!-- Audio Edit Area -->
                    <div class="media-edit-group">
                        <label style="font-weight: 500; font-size: 0.9rem; display: block; margin-bottom: 5px; color: var(--text-main);">Audio de la publicación:</label>
                        <div id="edit-audio-preview-container" style="display: none; margin-bottom: 8px;">
                            <audio id="edit-audio-preview" controls style="width: 100%; max-width: 300px; margin-bottom: 5px;"></audio>
                            <br>
                            <label style="display: inline-flex; align-items: center; gap: 5px; font-size: 0.85rem; color: #ff4d4d; cursor: pointer;">
                                <input type="checkbox" name="delete_audio" id="edit-delete-audio-checkbox" value="1">
                                Eliminar audio actual
                            </label>
                        </div>
                        <div class="media-inputs">
                            <label class="media-btn">♬ Cambiar audio
                                <input type="file" name="post_audio" id="edit-audio-input" accept="audio/*">
                            </label>
                        </div>
                    </div>
                </div>
                <div id="edit-post-error-banner" class="post-error-banner" style="display: none; margin-top: 10px;"></div>
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
            <button type="button" class="modal-close-btn" id="close-delete-modal">✕</button>
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
