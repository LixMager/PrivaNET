/**
 * PrivaNET - Control de Carga de Archivos (Imágenes y Audios)
 * 
 * Este script valida las dimensiones de las imágenes para auto-comprimirlas si superan 1600x1200 px,
 * y bloquea los audios de más de 30 segundos, mostrando notificaciones en itálica en la interfaz.
 */

// =========================================================================
// SECCIÓN DE IMÁGENES
// =========================================================================

// Elementos del DOM para la imagen
var imageInput = document.getElementById('post-image-input');
var imagePreviewContainer = document.getElementById('image-preview-container');
var imagePreview = document.getElementById('image-preview');
var removeImageBtn = document.getElementById('remove-image-btn');

// Si los elementos existen, configurar los eventos
if (imageInput && imagePreviewContainer && imagePreview && removeImageBtn) {
    
    // Al seleccionar una imagen
    imageInput.addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (!file) return;

        // Validar formato
        if (!file.type.startsWith('image/')) {
            limpiarImagen();
            mostrarMensajeImagen('❌ Por favor, selecciona un archivo de imagen válido.', true);
            return;
        }

        // Mostrar previsualización inicial del archivo original
        imagePreview.src = URL.createObjectURL(file);
        imagePreviewContainer.style.display = 'block';
        mostrarMensajeImagen('', false); // Limpiar avisos anteriores

        // Leer la imagen en memoria para revisar su tamaño
        var img = new Image();
        img.onload = function() {
            var originalWidth = img.width;
            var originalHeight = img.height;

            // Verificar si supera la resolución de 1600x1200 px
            if (originalWidth > 1600 || originalHeight > 1200) {
                // 1. Mostrar aviso en itálica notificando que se va a comprimir
                mostrarMensajeImagen('La imagen supera la resolución máxima de 1600x1200 px. Se la va a convertir/comprimir...', false);

                // Calcular escala proporcional para el ajuste de tamaño
                var scale = Math.min(1600 / originalWidth, 1200 / originalHeight);
                var newWidth = Math.round(originalWidth * scale);
                var newHeight = Math.round(originalHeight * scale);

                // Crear un canvas para redimensionar la imagen
                var canvas = document.createElement('canvas');
                canvas.width = newWidth;
                canvas.height = newHeight;
                
                var ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0, newWidth, newHeight);

                // Convertir el canvas a Blob y reemplazar el archivo
                canvas.toBlob(function(blob) {
                    if (blob) {
                        // Crear el nuevo archivo comprimido
                        var compressedFile = new File([blob], file.name, { type: 'image/jpeg' });
                        
                        // Cargar el archivo comprimido en el input
                        var dataTransfer = new DataTransfer();
                        dataTransfer.items.add(compressedFile);
                        imageInput.files = dataTransfer.files;

                        // Actualizar la previsualización con la imagen optimizada
                        imagePreview.src = URL.createObjectURL(blob);

                        // 2. Actualizar aviso a comprimido exitosamente
                        mostrarMensajeImagen('La imagen supera la resolución máxima de 1600x1200 px. Se ha comprimido y redimensionado automáticamente.', false);
                    }
                }, 'image/jpeg', 0.85);
            }
        };
        img.src = URL.createObjectURL(file);
    });

    // Al presionar el botón de quitar imagen
    removeImageBtn.addEventListener('click', function() {
        limpiarImagen();
    });
}

// Función simple para mostrar/ocultar avisos de imagen
function mostrarMensajeImagen(texto, esError) {
    var aviso = imagePreviewContainer.querySelector('.compression-notice');
    if (!aviso) {
        aviso = document.createElement('p');
        aviso.className = 'compression-notice';
        aviso.style.fontStyle = 'italic';
        aviso.style.fontSize = '0.85rem';
        aviso.style.marginTop = '8px';
        imagePreviewContainer.appendChild(aviso);
    }
    aviso.innerText = texto;
    aviso.style.color = esError ? '#ef4444' : '#eab308'; // Rojo para error, amarillo para compresión
    aviso.style.display = texto ? 'block' : 'none';
}

// Función para reiniciar el control de imagen
function limpiarImagen() {
    if (imageInput) imageInput.value = '';
    if (imagePreviewContainer) imagePreviewContainer.style.display = 'none';
    if (imagePreview) imagePreview.src = '';
    mostrarMensajeImagen('', false);
}


// =========================================================================
// SECCIÓN DE AUDIOS
// =========================================================================

// Elementos del DOM para el audio
var audioInput = document.getElementById('post-audio-input');
var audioPreviewContainer = document.getElementById('audio-preview-container');
var audioPreview = document.getElementById('audio-preview');
var removeAudioBtn = document.getElementById('remove-audio-btn');

// Si los elementos existen, configurar los eventos
if (audioInput && audioPreviewContainer && audioPreview && removeAudioBtn) {

    // Al seleccionar un archivo de audio
    audioInput.addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (!file) return;

        // Validar formato
        if (!file.type.startsWith('audio/')) {
            limpiarAudio();
            mostrarMensajeAudio('❌ Por favor, selecciona un archivo de audio válido.', true);
            return;
        }

        // Limpiar avisos anteriores
        mostrarMensajeAudio('', false);

        // Crear elemento de audio temporal para leer metadatos de duración
        var tempAudio = document.createElement('audio');
        tempAudio.src = URL.createObjectURL(file);

        tempAudio.onloadedmetadata = function() {
            var duration = tempAudio.duration;

            // Bloquear si tiene más de 30 segundos
            if (duration > 30) {
                limpiarAudio();
                
                // Mostrar aviso de restricción en itálica
                mostrarMensajeAudio('⚠️ El audio supera el límite de 30 segundos (duración: ' + duration.toFixed(1) + ' segundos) y no se permite cargar.', true);
            } else {
                // Cargar previsualización si cumple con el límite
                audioPreview.src = URL.createObjectURL(file);
                audioPreviewContainer.style.display = 'block';
                mostrarMensajeAudio('', false);
            }
        };
        
        tempAudio.onerror = function() {
            limpiarAudio();
            mostrarMensajeAudio('❌ No se pudieron cargar los metadatos del audio.', true);
        };
    });

    // Al presionar el botón de quitar audio
    removeAudioBtn.addEventListener('click', function() {
        limpiarAudio();
    });
}

// Función simple para mostrar/ocultar avisos de audio
function mostrarMensajeAudio(texto, esError) {
    var aviso = document.getElementById('audio-upload-notice');
    if (!aviso) {
        aviso = document.createElement('p');
        aviso.id = 'audio-upload-notice';
        aviso.style.fontStyle = 'italic';
        aviso.style.fontSize = '0.85rem';
        aviso.style.marginTop = '8px';
        // Se coloca justo después del contenedor de previsualización para que se lea aun si el reproductor está oculto
        audioPreviewContainer.parentNode.insertBefore(aviso, audioPreviewContainer.nextSibling);
    }
    aviso.innerText = texto;
    aviso.style.color = esError ? '#ef4444' : '#eab308'; // Rojo para error, amarillo/ámbar para otros
    aviso.style.display = texto ? 'block' : 'none';
}

// Función para reiniciar el control de audio
function limpiarAudio() {
    if (audioInput) audioInput.value = '';
    if (audioPreviewContainer) audioPreviewContainer.style.display = 'none';
    if (audioPreview) audioPreview.src = '';
    mostrarMensajeAudio('', false);
}
