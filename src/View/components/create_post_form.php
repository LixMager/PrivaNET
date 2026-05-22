<div class="create-post-box">
    <h2>Crear Nueva Publicación</h2>
    
    <?php if (session_status() === PHP_SESSION_NONE) { session_start(); } ?>
    <?php 
    $draftText = $_SESSION['post_text_draft'] ?? '';
    unset($_SESSION['post_text_draft']);
    ?>

<!-- Quill WYSIWYG Editor Assets -->
<link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>

    <form id="create-post-form" action="/PrivaNet/index.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="create_post">
        
        <input type="hidden" name="post_text" id="post-text-hidden" value="<?php echo htmlspecialchars($draftText); ?>">
        <input type="hidden" name="scheduled_date_utc" id="scheduled-date-utc-input">
        <div id="editor-container" data-placeholder="¿Qué estás pensando, @<?php echo htmlspecialchars($_COOKIE['user_name'] ?? 'usuario'); ?>?"></div>
        
        <div class="create-post-actions">
            <div class="media-inputs">
                <label class="media-btn">📷 Añadir foto
                    <input type="file" name="post_image" id="post-image-input" accept="image/*"></label>
                <label class="media-btn">🎵 Añadir audio
                    <input type="file" name="post_audio" id="post-audio-input" accept="audio/*"></label>
            </div>
            <button type="submit" class="submit-post-btn" id="submit-post-btn" disabled>Publicar</button>
        </div>

        <!-- Contenedor para programar publicación -->
        <div class="post-scheduler-section">
            <label class="schedule-checkbox-label">
                <input type="checkbox" name="is_scheduled" id="is-scheduled-checkbox">
                📅 Programar esta publicación
            </label>
            
            <div id="scheduled-date-container">
                <label for="scheduled-date-input" class="schedule-date-label">Fecha y hora de publicación (máximo 3 días en el futuro):</label>
                <input type="datetime-local" name="scheduled_date" id="scheduled-date-input">
            </div>
        </div>
        <div id="image-preview-container" class="preview-container">
            <img id="image-preview" src="" alt="Previsualización">
            <button type="button" id="remove-image-btn" class="remove-media-btn">❌ Quitar imagen</button>
            <p class="compression-notice upload-notice"></p>
        </div>
        <div id="audio-preview-container" class="preview-container">
            <audio id="audio-preview" controls></audio>
            <button type="button" id="remove-audio-btn" class="remove-media-btn">❌ Quitar audio</button>
        </div>
        
        <!-- Mensaje de error de publicación (PHP y JS) -->
        <div class="post-error-banner" style="display: <?php echo isset($_SESSION['post_error']) ? 'block' : 'none'; ?>;">
            <?php 
            if (isset($_SESSION['post_error'])) {
                echo htmlspecialchars($_SESSION['post_error']);
                unset($_SESSION['post_error']);
            }
            ?>
        </div>
    </form>
</div>
