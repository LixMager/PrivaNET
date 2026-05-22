/**
 * PrivaNET - Control de Carga de Archivos (Imágenes y Audios) y Texto Enriquecido
 * 
 * Este script valida las dimensiones de las imágenes para auto-comprimirlas si superan 1600x1200 px,
 * bloquea los audios de más de 30 segundos, e inicializa/valida el editor de texto enriquecido Quill.js.
 */

// =========================================================================
// INICIALIZACIÓN DE QUILL.JS
// =========================================================================
var editorContainer = document.getElementById('editor-container');
var postTextHidden = document.getElementById('post-text-hidden');
var quillEditor = null;

if (editorContainer && postTextHidden) {
    var placeholder = editorContainer.getAttribute('data-placeholder') || 'Escribe algo...';
    quillEditor = new Quill('#editor-container', {
        theme: 'snow',
        placeholder: placeholder,
        modules: {
            toolbar: [
                ['bold', 'italic'],
                [{ 'color': [] }],
                ['link']
            ]
        }
    });
    // Cargar borrador si está pre-poblado por errores de validación del servidor
    if (postTextHidden.value) {
        quillEditor.root.innerHTML = postTextHidden.value;
    }

    // Habilitar/Deshabilitar botón de publicar según contenido de texto
    var submitBtn = document.getElementById('submit-post-btn');
    function actualizarEstadoBotonPublicar() {
        if (!submitBtn) return;
        var plainText = quillEditor.getText().trim();
        submitBtn.disabled = (plainText.length === 0);
    }

    quillEditor.on('text-change', function() {
        actualizarEstadoBotonPublicar();
    });
    // Validar estado inicial por si viene con borrador pre-cargado
    actualizarEstadoBotonPublicar();
}

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
            mostrarMensajeErrorPost('Por favor, selecciona un archivo de imagen válido.');
            return;
        }

        // Mostrar previsualización inicial del archivo original
        imagePreview.src = URL.createObjectURL(file);
        imagePreviewContainer.style.display = 'block';
        mostrarMensajeImagen('', false); // Limpiar avisos anteriores
        mostrarMensajeErrorPost(''); // Limpiar errores anteriores

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
        mostrarMensajeErrorPost('');
    });
}

// Función simple para mostrar/ocultar avisos de imagen
function mostrarMensajeImagen(texto, esError) {
    var aviso = imagePreviewContainer.querySelector('.compression-notice');
    if (!aviso) return;
    aviso.innerText = texto;
    if (texto) {
        aviso.style.display = 'block';
        if (esError) {
            aviso.className = 'compression-notice upload-notice error';
        } else {
            aviso.className = 'compression-notice upload-notice warning';
        }
    } else {
        aviso.style.display = 'none';
    }
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
            mostrarMensajeErrorPost('Por favor, selecciona un archivo de audio válido.');
            return;
        }

        // Limpiar errores anteriores
        mostrarMensajeErrorPost('');

        // Crear elemento de audio temporal para leer metadatos de duración
        var tempAudio = document.createElement('audio');
        tempAudio.src = URL.createObjectURL(file);

        tempAudio.onloadedmetadata = function() {
            var duration = tempAudio.duration;

            // Bloquear si tiene más de 30 segundos
            if (duration > 30) {
                limpiarAudio();
                
                // Mostrar aviso de restricción
                mostrarMensajeErrorPost('El audio supera el límite de 30 segundos (duración: ' + duration.toFixed(1) + ' segundos) y no se permite cargar.');
            } else {
                // Cargar previsualización si cumple con el límite
                audioPreview.src = URL.createObjectURL(file);
                audioPreviewContainer.style.display = 'block';
                mostrarMensajeErrorPost('');
            }
        };
        
        tempAudio.onerror = function() {
            limpiarAudio();
            mostrarMensajeErrorPost('No se pudieron cargar los metadatos del audio.');
        };
    });

    // Al presionar el botón de quitar audio
    removeAudioBtn.addEventListener('click', function() {
        limpiarAudio();
        mostrarMensajeErrorPost('');
    });
}

// Función para reiniciar el control de audio
function limpiarAudio() {
    if (audioInput) audioInput.value = '';
    if (audioPreviewContainer) audioPreviewContainer.style.display = 'none';
    if (audioPreview) audioPreview.src = '';
}

// =========================================================================
// SECCIÓN DE PROGRAMACIÓN DE PUBLICACIONES (SCHEDULING)
// =========================================================================

var isScheduledCheckbox = document.getElementById('is-scheduled-checkbox');
var scheduledDateContainer = document.getElementById('scheduled-date-container');
var scheduledDateInput = document.getElementById('scheduled-date-input');
var createPostForm = document.getElementById('create-post-form');

if (isScheduledCheckbox && scheduledDateContainer && scheduledDateInput) {
    // 1. Escuchar el cambio del checkbox para mostrar/ocultar
    isScheduledCheckbox.addEventListener('change', function(e) {
        if (e.target.checked) {
            scheduledDateContainer.style.display = 'block';
            inicializarLimitesFecha();
        } else {
            scheduledDateContainer.style.display = 'none';
            scheduledDateInput.value = '';
            // Ocultar banner de error si estuviera visible al desactivar
            mostrarMensajeErrorPost('');
        }
    });

    // 2. Inicializar límites de fecha/hora dinámica (min y max)
    function inicializarLimitesFecha() {
        var ahora = new Date();
        var minDate = formatearFechaLocal(ahora);
        
        // 3 días en el futuro
        var tresDiasDespues = new Date(ahora.getTime() + (3 * 24 * 60 * 60 * 1000));
        var maxDate = formatearFechaLocal(tresDiasDespues);
        
        scheduledDateInput.min = minDate;
        scheduledDateInput.max = maxDate;
        
        // Si no hay valor anterior, asignar ahora + 2 minutos (para evitar que al truncar los segundos sea un valor ya pasado)
        if (!scheduledDateInput.value) {
            var dosMinutosDespues = new Date(ahora.getTime() + (2 * 60 * 1000));
            scheduledDateInput.value = formatearFechaLocal(dosMinutosDespues);
        }
    }

    function formatearFechaLocal(fecha) {
        var anio = fecha.getFullYear();
        var mes = padZero(fecha.getMonth() + 1);
        var dia = padZero(fecha.getDate());
        var horas = padZero(fecha.getHours());
        var minutos = padZero(fecha.getMinutes());
        
        return anio + '-' + mes + '-' + dia + 'T' + horas + ':' + minutos;
    }

    function padZero(num) {
        return (num < 10 ? '0' : '') + num;
    }
}

// 3. Interceptar envío para validar límites en cliente
if (createPostForm) {
    createPostForm.addEventListener('submit', function(e) {
        // A. Validar y sincronizar texto enriquecido si Quill está activo
        if (quillEditor && postTextHidden) {
            var htmlContent = quillEditor.root.innerHTML;
            // quillEditor.getText() devuelve el texto plano, que siempre termina con un salto de línea \n
            var plainText = quillEditor.getText().trim();

            if (plainText.length > 255) {
                e.preventDefault();
                mostrarMensajeErrorPost('El texto de la publicación no puede superar los 255 caracteres.');
                return;
            }

            // Sincronizar el contenido HTML con el input oculto
            postTextHidden.value = htmlContent;
        }

        // B. Validar programación si corresponde
        if (isScheduledCheckbox && isScheduledCheckbox.checked && scheduledDateInput) {
            var selectedVal = scheduledDateInput.value;
            if (!selectedVal) {
                e.preventDefault();
                mostrarMensajeErrorPost('Por favor, selecciona una fecha y hora para programar la publicación.');
                return;
            }

            // Parsear localmente para evitar discrepancias de zona horaria (new Date('YYYY-MM-DDTHH:mm') suele interpretarse en UTC)
            var parts = selectedVal.split('T');
            var dateParts = parts[0].split('-');
            var timeParts = parts[1].split(':');
            var selectedDate = new Date(
                parseInt(dateParts[0], 10),
                parseInt(dateParts[1], 10) - 1,
                parseInt(dateParts[2], 10),
                parseInt(timeParts[0], 10),
                parseInt(timeParts[1], 10)
            );
            var ahora = new Date();
            var tresDiasDespues = new Date(ahora.getTime() + (3 * 24 * 60 * 60 * 1000));

            // Al menos 1 minuto en el futuro (toleramos hasta 50 segundos por posibles retrasos al hacer clic)
            var limiteMinimo = ahora.getTime() + 50000;
            if (selectedDate.getTime() < limiteMinimo) {
                e.preventDefault();
                mostrarMensajeErrorPost('La fecha y hora de programación debe ser al menos 1 minuto en el futuro.');
                return;
            }

            if (selectedDate.getTime() > tresDiasDespues.getTime()) {
                e.preventDefault();
                mostrarMensajeErrorPost('La publicación no se puede programar con más de 3 días de anticipación.');
                return;
            }

            // Guardar la fecha en UTC ISO format en el input oculto
            var utcInput = document.getElementById('scheduled-date-utc-input');
            if (utcInput) {
                utcInput.value = selectedDate.toISOString();
            }
        } else {
            // Asegurar que el input oculto se limpie si no se está programando
            var utcInput = document.getElementById('scheduled-date-utc-input');
            if (utcInput) {
                utcInput.value = '';
            }
        }
    });
}

// Función unificada para mostrar/ocultar el banner de errores de publicación
function mostrarMensajeErrorPost(texto) {
    var errorBanner = document.querySelector('.post-error-banner');
    if (errorBanner) {
        errorBanner.innerHTML = texto;
        errorBanner.style.display = texto ? 'block' : 'none';
        if (texto) {
            errorBanner.scrollIntoView({ behavior: 'smooth' });
        }
    }
}
