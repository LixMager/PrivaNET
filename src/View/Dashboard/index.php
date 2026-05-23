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
    <div class="post-card panel-header">
        <div>
            <h2>Panel de Gestión de Publicaciones</h2>
            <p>Administra tus posteos, realiza modificaciones de texto o elimina publicaciones de forma definitiva.</p>
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
            <div class="post-card post-card--empty">
                <p>Aún no has realizado ninguna publicación.</p>
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
    <div class="modal-content modal-content--wide">
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
                
                <div class="modal-media-section">
                    <!-- Image Edit Area -->
                    <div class="media-edit-group">
                        <label class="media-edit-label">Imagen de la publicación:</label>
                        <div id="edit-image-preview-container" class="media-preview-container">
                            <img id="edit-image-preview" src="" class="media-preview-img" alt="Preview">
                            <label class="media-delete-label">
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
                        <label class="media-edit-label">Audio de la publicación:</label>
                        <div id="edit-audio-preview-container" class="media-preview-container">
                            <audio id="edit-audio-preview" controls class="media-preview-audio"></audio>
                            <br>
                            <label class="media-delete-label">
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
                <div id="edit-post-error-banner" class="post-error-banner modal-error-banner"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="action-btn secondary-btn" id="cancel-edit-btn">Cancelar</button>
                <button type="submit" class="action-btn modal-btn-primary" id="save-edit-btn">Guardar Cambios</button>
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
            <p class="modal-confirm-text">¿Estás seguro de que deseas eliminar esta publicación?</p>
            <p class="modal-confirm-sub">Esta acción es irreversible y eliminará de forma permanente el texto, las imágenes o audios asociados, así como también los me gusta, no me gusta y favoritos que haya recibido.</p>
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
