/**
 * Lógica específica del Homepage (Entorno Privado)
 */

document.addEventListener('DOMContentLoaded', function() {
    
    // --- LÓGICA DE IMAGEN ---
    const imageInput = document.getElementById('post-image-input');
    const imagePreviewContainer = document.getElementById('image-preview-container');
    const previewImage = document.getElementById('image-preview');
    const removeImageBtn = document.getElementById('remove-image-btn');

    imageInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            if (file.type.startsWith('image/')) {
                const imageUrl = URL.createObjectURL(file);
                previewImage.src = imageUrl;
                imagePreviewContainer.style.display = 'block';
            } else {
                alert('Por favor, selecciona un archivo de imagen válido.');
                imageInput.value = '';
            }
        }
    });

    removeImageBtn.addEventListener('click', function() {
        imageInput.value = '';
        imagePreviewContainer.style.display = 'none';
        URL.revokeObjectURL(previewImage.src);
        previewImage.src = '';
    });


    // --- LÓGICA DE AUDIO ---
    const audioInput = document.getElementById('post-audio-input');
    const audioPreviewContainer = document.getElementById('audio-preview-container');
    const previewAudio = document.getElementById('audio-preview');
    const removeAudioBtn = document.getElementById('remove-audio-btn');

    audioInput.addEventListener('change', function(event) {
        const file = event.target.files[0];
        if (file) {
            if (file.type.startsWith('audio/')) {
                const audioUrl = URL.createObjectURL(file);
                previewAudio.src = audioUrl;
                audioPreviewContainer.style.display = 'block';
            } else {
                alert('Por favor, selecciona un archivo de audio válido.');
                audioInput.value = '';
            }
        }
    });

    removeAudioBtn.addEventListener('click', function() {
        audioInput.value = '';
        audioPreviewContainer.style.display = 'none';
        URL.revokeObjectURL(previewAudio.src);
        previewAudio.src = '';
    });

});
