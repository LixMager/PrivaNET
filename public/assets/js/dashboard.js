document.addEventListener('DOMContentLoaded', function () {
    // Referencias a los modales y sus elementos
    var editModal = document.getElementById('edit-modal');
    var deleteModal = document.getElementById('delete-modal');

    // Botones de cierre y cancelación
    var closeEditModal = document.getElementById('close-edit-modal');
    var cancelEditBtn = document.getElementById('cancel-edit-btn');
    var closeDeleteModal = document.getElementById('close-delete-modal');
    var cancelDeleteBtn = document.getElementById('cancel-delete-btn');

    // Formularios e inputs de modales
    var editForm = document.getElementById('edit-post-form');
    var editPostIdInput = document.getElementById('edit-post-id');
    var editPostTextHidden = document.getElementById('edit-post-text-hidden');

    // Inicializar Quill para el modal de edición
    var editEditorContainer = document.getElementById('edit-editor-container');
    var editQuillEditor = null;

    if (editEditorContainer && editPostTextHidden) {
        editQuillEditor = new Quill('#edit-editor-container', {
            theme: 'snow',
            placeholder: '¿Qué estás pensando?',
            modules: {
                toolbar: [
                    ['bold', 'italic'],
                    [{ 'color': [] }],
                    ['link']
                ]
            }
        });
    }

    var deletePostIdInput = document.getElementById('delete-post-id');
    var confirmDeleteBtn = document.getElementById('confirm-delete-btn');

    // Función para abrir modal
    function openModal(modal) {
        if (modal) {
            modal.classList.add('open');
        }
    }

    // Función para cerrar modal
    function closeModal(modal) {
        if (modal) {
            modal.classList.remove('open');
            // Pausar el audio del panel de edición al cerrar
            var audPreview = modal.querySelector('audio');
            if (audPreview) {
                audPreview.pause();
                audPreview.currentTime = 0;
            }
        }
    }

    // Cerrar edit modal
    if (closeEditModal) {
        closeEditModal.addEventListener('click', function () {
            closeModal(editModal);
        });
    }
    if (cancelEditBtn) {
        cancelEditBtn.addEventListener('click', function () {
            closeModal(editModal);
        });
    }

    // Cerrar delete modal
    if (closeDeleteModal) {
        closeDeleteModal.addEventListener('click', function () {
            closeModal(deleteModal);
        });
    }
    if (cancelDeleteBtn) {
        cancelDeleteBtn.addEventListener('click', function () {
            closeModal(deleteModal);
        });
    }

    // Cerrar modales al hacer click en el overlay (fuera del contenido del modal)
    window.addEventListener('click', function (event) {
        if (event.target === editModal) {
            closeModal(editModal);
        }
        if (event.target === deleteModal) {
            closeModal(deleteModal);
        }
    });

    // Delegación de eventos para botones de Editar y Eliminar en las tarjetas de post
    document.addEventListener('click', function (event) {
        var button = event.target;

        // Subir en el DOM si el click fue dentro del botón
        while (button && button !== document && !button.classList.contains('action-btn')) {
            button = button.parentNode;
        }

        if (button && button !== document && button.classList.contains('action-btn')) {
            var postId = button.getAttribute('data-post-id');
            if (!postId) {
                return;
            }

            if (button.classList.contains('edit-post-btn')) {
                event.preventDefault();
                // Rellenar datos
                var postText = button.getAttribute('data-post-text') || '';
                var postImage = button.getAttribute('data-post-image') || '';
                var postAudio = button.getAttribute('data-post-audio') || '';
                
                editPostIdInput.value = postId;
                if (editQuillEditor) {
                    editQuillEditor.root.innerHTML = postText;
                }
                
                // Mostrar previsualización de imagen actual
                var imgContainer = document.getElementById('edit-image-preview-container');
                var imgPreview = document.getElementById('edit-image-preview');
                var deleteImgCheckbox = document.getElementById('edit-delete-image-checkbox');
                if (imgContainer && imgPreview && deleteImgCheckbox) {
                    deleteImgCheckbox.checked = false;
                    if (postImage) {
                        imgPreview.src = '/PrivaNet/' + postImage;
                        imgContainer.style.display = 'block';
                    } else {
                        imgPreview.src = '';
                        imgContainer.style.display = 'none';
                    }
                }
                
                // Mostrar previsualización de audio actual
                var audContainer = document.getElementById('edit-audio-preview-container');
                var audPreview = document.getElementById('edit-audio-preview');
                var deleteAudCheckbox = document.getElementById('edit-delete-audio-checkbox');
                if (audContainer && audPreview && deleteAudCheckbox) {
                    deleteAudCheckbox.checked = false;
                    if (postAudio) {
                        audPreview.src = '/PrivaNet/' + postAudio;
                        audContainer.style.display = 'block';
                    } else {
                        audPreview.src = '';
                        audContainer.style.display = 'none';
                    }
                }
                
                // Limpiar inputs de archivos y banner de error
                var imgInput = document.getElementById('edit-image-input');
                var audInput = document.getElementById('edit-audio-input');
                if (imgInput) imgInput.value = '';
                if (audInput) audInput.value = '';
                
                var errBanner = document.getElementById('edit-post-error-banner');
                if (errBanner) {
                    errBanner.innerText = '';
                    errBanner.style.display = 'none';
                }

                openModal(editModal);
            } else if (button.classList.contains('delete-post-btn')) {
                event.preventDefault();
                deletePostIdInput.value = postId;
                openModal(deleteModal);
            }
        }
    });

    // Eventos de validación en tiempo real para el modal de edición
    var editImgInput = document.getElementById('edit-image-input');
    var editAudInput = document.getElementById('edit-audio-input');
    var editErrBanner = document.getElementById('edit-post-error-banner');

    function mostrarErrorEdit(texto) {
        if (editErrBanner) {
            editErrBanner.innerText = texto;
            editErrBanner.style.display = texto ? 'block' : 'none';
        }
    }

    if (editImgInput) {
        editImgInput.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (!file) return;
            
            if (!file.type.startsWith('image/')) {
                editImgInput.value = '';
                mostrarErrorEdit('Por favor, selecciona un archivo de imagen válido.');
                return;
            }
            mostrarErrorEdit('');
            
            var img = new Image();
            img.onload = function() {
                var w = img.width;
                var h = img.height;
                if (w > 1600 || h > 1200) {
                    mostrarErrorEdit('La imagen supera los 1600x1200 px. Se comprimirá automáticamente.');
                    
                    var scale = Math.min(1600 / w, 1200 / h);
                    var newW = Math.round(w * scale);
                    var newH = Math.round(h * scale);
                    
                    var canvas = document.createElement('canvas');
                    canvas.width = newW;
                    canvas.height = newH;
                    var ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, newW, newH);
                    
                    canvas.toBlob(function(blob) {
                        if (blob) {
                            var compressedFile = new File([blob], file.name, { type: 'image/jpeg' });
                            var dataTransfer = new DataTransfer();
                            dataTransfer.items.add(compressedFile);
                            editImgInput.files = dataTransfer.files;
                            mostrarErrorEdit('La imagen se ha comprimido automáticamente.');
                        }
                    }, 'image/jpeg', 0.85);
                }
            };
            img.src = URL.createObjectURL(file);
        });
    }

    if (editAudInput) {
        editAudInput.addEventListener('change', function(e) {
            var file = e.target.files[0];
            if (!file) return;
            
            if (!file.type.startsWith('audio/')) {
                editAudInput.value = '';
                mostrarErrorEdit('Por favor, selecciona un archivo de audio válido.');
                return;
            }
            mostrarErrorEdit('');
            
            var tempAudio = document.createElement('audio');
            tempAudio.src = URL.createObjectURL(file);
            tempAudio.onloadedmetadata = function() {
                if (tempAudio.duration > 30) {
                    editAudInput.value = '';
                    mostrarErrorEdit('El audio supera el límite de 30 segundos.');
                }
            };
        });
    }

    // Formulario de edición (Guardar Cambios)
    if (editForm) {
        editForm.addEventListener('submit', function (event) {
            event.preventDefault();

            var postId = editPostIdInput.value;
            
            var text = '';
            var plainText = '';
            if (editQuillEditor) {
                text = editQuillEditor.root.innerHTML;
                plainText = editQuillEditor.getText().trim();
            }

            if (plainText.length > 255) {
                mostrarErrorEdit('El texto de la publicación no puede superar los 255 caracteres.');
                return;
            }

            // Crear FormData para enviar campos y archivos
            var formData = new FormData(editForm);
            formData.append('post_text', text);

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/PrivaNet/index.php', true);

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                // 1. Actualizar el texto en el DOM
                                var postTextElement = document.querySelector('[data-post-text-content="' + postId + '"]');
                                if (postTextElement) {
                                    postTextElement.innerHTML = response.text;
                                }

                                // 2. Encontrar la tarjeta del post
                                var card = document.querySelector('.post-card[data-post-card-id="' + postId + '"]');
                                if (card) {
                                    // 3. Actualizar o eliminar imagen
                                    var imgMedia = card.querySelector('.image-media');
                                    if (response.image) {
                                        if (imgMedia) {
                                            var imgEl = imgMedia.querySelector('img');
                                            if (imgEl) imgEl.src = '/PrivaNet/' + response.image;
                                        } else {
                                            // Crear el div media para la imagen
                                            var newImgMedia = document.createElement('div');
                                            newImgMedia.className = 'post-media image-media';
                                            newImgMedia.innerHTML = '<img src="/PrivaNet/' + response.image + '" class="post-thumbnail" alt="Imagen del posteo">';
                                            var footer = card.querySelector('.post-actions');
                                            card.insertBefore(newImgMedia, footer);
                                        }
                                    } else {
                                        if (imgMedia) {
                                            imgMedia.parentNode.removeChild(imgMedia);
                                        }
                                    }

                                    // 4. Actualizar o eliminar audio
                                    var audMedia = card.querySelector('.audio-media');
                                    if (response.audio) {
                                        if (audMedia) {
                                            var audEl = audMedia.querySelector('source');
                                            var audioTag = audMedia.querySelector('audio');
                                            if (audEl && audioTag) {
                                                audEl.src = '/PrivaNet/' + response.audio;
                                                audioTag.load(); // Recargar el player de audio
                                            }
                                        } else {
                                            // Crear el div media para el audio
                                            var newAudMedia = document.createElement('div');
                                            newAudMedia.className = 'post-media audio-media';
                                            newAudMedia.innerHTML = '<audio controls style="width: 100%;"><source src="/PrivaNet/' + response.audio + '" type="audio/mpeg">Tu navegador no soporta audio HTML5.</audio>';
                                            var footer = card.querySelector('.post-actions');
                                            card.insertBefore(newAudMedia, footer);
                                        }
                                    } else {
                                        if (audMedia) {
                                            audMedia.parentNode.removeChild(audMedia);
                                        }
                                    }
                                }

                                // 5. Actualizar los atributos del botón para futuras ediciones
                                var editButton = document.querySelector('.edit-post-btn[data-post-id="' + postId + '"]');
                                if (editButton) {
                                    editButton.setAttribute('data-post-text', response.text);
                                    editButton.setAttribute('data-post-image', response.image || '');
                                    editButton.setAttribute('data-post-audio', response.audio || '');
                                }

                                closeModal(editModal);
                            } else {
                                mostrarErrorEdit(response.message || 'Error al actualizar el posteo.');
                            }
                        } catch (e) {
                            console.error('Error al parsear respuesta JSON:', e);
                        }
                    } else {
                        mostrarErrorEdit('Error de red al intentar actualizar la publicación.');
                    }
                }
            };

            xhr.send(formData);
        });
    }

    // Botón de confirmar eliminación
    if (confirmDeleteBtn) {
        confirmDeleteBtn.addEventListener('click', function () {
            var postId = deletePostIdInput.value;
            if (!postId) return;

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/PrivaNet/index.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                // Cerrar el modal inmediatamente
                                closeModal(deleteModal);

                                // Encontrar la tarjeta en el DOM y aplicar efecto de desaparición
                                var card = document.querySelector('.post-card[data-post-card-id="' + postId + '"]');
                                if (card) {
                                    card.classList.add('fade-out');
                                    
                                    // Remover del DOM después de que termine la animación
                                    setTimeout(function () {
                                        if (card.parentNode) {
                                            card.parentNode.removeChild(card);
                                        }
                                        
                                        // Si ya no quedan posts, mostrar un mensaje de lista vacía
                                        var container = document.getElementById('dashboard-posts-container');
                                        if (container && container.querySelectorAll('.post-card').length === 0) {
                                            container.innerHTML = '<div class="post-card" style="text-align: center; padding: 40px 20px;"><p style="color: var(--text-muted);">Aún no has realizado ninguna publicación.</p></div>';
                                        }
                                    }, 400); // Coincide con la duración en la clase .post-card.fade-out
                                }
                            } else {
                                alert(response.message || 'Error al eliminar el posteo.');
                            }
                        } catch (e) {
                            console.error('Error al parsear respuesta JSON:', e);
                        }
                    } else {
                        alert('Error de red al intentar eliminar la publicación.');
                    }
                }
            };

            xhr.send('action=delete_post&post_id=' + encodeURIComponent(postId));
        });
    }
});
