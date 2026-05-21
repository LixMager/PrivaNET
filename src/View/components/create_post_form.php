<div class="create-post-box">
    <h2>Crear Nueva Publicación</h2>
    <form action="/PrivaNet/index.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="action" value="create_post">
        <textarea name="post_text" placeholder="¿Qué estás pensando, @<?php echo htmlspecialchars($_COOKIE['user_name'] ?? 'usuario'); ?>?"></textarea>
        <div class="create-post-actions">
            <div class="media-inputs">
                <label class="media-btn">📷 Añadir foto
                    <input type="file" name="post_image" id="post-image-input" accept="image/*"></label>
                <label class="media-btn">🎵 Añadir audio
                    <input type="file" name="post_audio" id="post-audio-input" accept="audio/*"></label>
            </div>
            <button type="submit" class="submit-post-btn">Publicar</button>
        </div>
        <div id="image-preview-container" style="display: none; margin-top: 15px; border-top: 1px solid var(--border-color); padding-top: 15px;">
            <img id="image-preview" src="" alt="Previsualización" style="max-height: 200px; border-radius: 8px; border: 1px solid var(--border-color); object-fit: cover;">
            <button type="button" id="remove-image-btn" style="display: block; margin-top: 5px; background: transparent; border: none; color: #ef4444; font-size: 0.85rem; cursor: pointer;">❌ Quitar imagen</button>
        </div>
        <div id="audio-preview-container" style="display: none; margin-top: 15px; border-top: 1px solid var(--border-color); padding-top: 15px;">
            <audio id="audio-preview" controls style="width: 100%; border-radius: var(--radius);"></audio>
            <button type="button" id="remove-audio-btn" style="display: block; margin-top: 5px; background: transparent; border: none; color: #ef4444; font-size: 0.85rem; cursor: pointer;">❌ Quitar audio</button>
        </div>
    </form>
</div>
