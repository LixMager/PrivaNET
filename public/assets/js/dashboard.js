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
    var editPostTextInput = document.getElementById('edit-post-text');

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
                editPostIdInput.value = postId;
                editPostTextInput.value = postText;
                openModal(editModal);
            } else if (button.classList.contains('delete-post-btn')) {
                event.preventDefault();
                deletePostIdInput.value = postId;
                openModal(deleteModal);
            }
        }
    });

    // Formulario de edición (Guardar Cambios)
    if (editForm) {
        editForm.addEventListener('submit', function (event) {
            event.preventDefault();

            var postId = editPostIdInput.value;
            var text = editPostTextInput.value.trim();

            if (!text) {
                alert('El texto del post no puede estar vacío.');
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/PrivaNet/index.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                // Actualizar el texto en el DOM
                                var postTextElement = document.querySelector('[data-post-text-content="' + postId + '"]');
                                if (postTextElement) {
                                    postTextElement.textContent = response.text;
                                }

                                // Actualizar el atributo data-post-text del botón de edición para futuros clics
                                var editButton = document.querySelector('.edit-post-btn[data-post-id="' + postId + '"]');
                                if (editButton) {
                                    editButton.setAttribute('data-post-text', response.text);
                                }

                                closeModal(editModal);
                            } else {
                                alert(response.message || 'Error al actualizar el posteo.');
                            }
                        } catch (e) {
                            console.error('Error al parsear respuesta JSON:', e);
                        }
                    } else {
                        alert('Error de red al intentar actualizar la publicación.');
                    }
                }
            };

            xhr.send('action=update_post&post_id=' + encodeURIComponent(postId) + '&post_text=' + encodeURIComponent(text));
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
