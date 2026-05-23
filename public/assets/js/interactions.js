document.addEventListener('DOMContentLoaded', function () {
    // 1. Delegación de eventos para interacciones con las publicaciones
    document.addEventListener('click', function (event) {
        var button = event.target;
        
        // En caso de que se haya hecho click en un elemento interno del botón (texto, emojis)
        while (button && button !== document && !button.classList.contains('action-btn')) {
            button = button.parentNode;
        }
        
        if (button && button !== document && button.classList.contains('action-btn')) {
            var postId = button.getAttribute('data-post-id');
            if (!postId) {
                return; // Ignorar botones no interactivos (ej: para usuarios no registrados)
            }
            
            var actionType = '';
            var postAction = '';
            
            if (button.classList.contains('like-btn')) {
                actionType = 'like';
                postAction = 'toggle_like';
            } else if (button.classList.contains('dislike-btn')) {
                actionType = 'dislike';
                postAction = 'toggle_dislike';
            } else if (button.classList.contains('fav-btn')) {
                actionType = 'fav';
                postAction = 'toggle_favorite';
            } else {
                return;
            }
            
            event.preventDefault();
            
            // Petición AJAX nativa mediante XMLHttpRequest
            var xhr = new XMLHttpRequest();
            xhr.open('POST', '/PrivaNet/index.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    if (xhr.status === 200) {
                        try {
                            var response = JSON.parse(xhr.responseText);
                            if (response.success) {
                                // Buscar todos los botones relacionados con esta publicación en la página para mantenerlos sincronizados (card del feed y modal/lightbox)
                                var buttonsToUpdate = document.querySelectorAll('[data-post-id="' + postId + '"]');
                                
                                for (var i = 0; i < buttonsToUpdate.length; i++) {
                                    var btn = buttonsToUpdate[i];
                                    if (actionType === 'like') {
                                        if (btn.classList.contains('like-btn')) {
                                            if (response.active) {
                                                btn.classList.add('active');
                                                btn.innerHTML = '▲ Te gusta';
                                            } else {
                                                btn.classList.remove('active');
                                                btn.innerHTML = '△ Me gusta';
                                            }
                                        } else if (btn.classList.contains('dislike-btn')) {
                                            if (response.active) {
                                                btn.classList.remove('active');
                                                btn.innerHTML = '▽ No me gusta';
                                            }
                                        }
                                    } else if (actionType === 'dislike') {
                                        if (btn.classList.contains('dislike-btn')) {
                                            if (response.active) {
                                                btn.classList.add('active');
                                                btn.innerHTML = '▼ Te disgusta';
                                            } else {
                                                btn.classList.remove('active');
                                                btn.innerHTML = '▽ No me gusta';
                                            }
                                        } else if (btn.classList.contains('like-btn')) {
                                            if (response.active) {
                                                btn.classList.remove('active');
                                                btn.innerHTML = '△ Me gusta';
                                            }
                                        }
                                    } else if (actionType === 'fav') {
                                        if (btn.classList.contains('fav-btn')) {
                                            if (response.active) {
                                                btn.classList.add('active');
                                                btn.innerHTML = '★ Favorito';
                                            } else {
                                                btn.classList.remove('active');
                                                btn.innerHTML = '☆ Favorito';
                                            }
                                        }
                                    }
                                }
                            } else {
                                alert(response.message || 'Ocurrió un error al procesar la acción.');
                            }
                        } catch (e) {
                            console.error('Error parseando respuesta JSON:', e);
                        }
                    } else {
                        alert('Error de red al intentar interactuar con la publicación.');
                    }
                }
            };
            
            xhr.send('action=' + encodeURIComponent(postAction) + '&post_id=' + encodeURIComponent(postId));
        }
    });

    // 2. Manejo dinámico de pestañas para la sección de Mi Actividad
    var activityContent = document.getElementById('activity-content');
    if (activityContent) {
        var tabs = document.querySelectorAll('.activity-tab');
        
        function loadActivity(type, tabButton) {
            // Desactivar visualmente todas las pestañas y actualizar sus iconos a vacíos
            for (var i = 0; i < tabs.length; i++) {
                tabs[i].classList.remove('active');
                var tType = tabs[i].getAttribute('data-type');
                if (tType === 'like') {
                    tabs[i].innerHTML = '△ Me gusta';
                } else if (tType === 'dislike') {
                    tabs[i].innerHTML = '▽ No me gusta';
                } else if (tType === 'favorite') {
                    tabs[i].innerHTML = '☆ Favoritos';
                }
            }
            // Activar la pestaña cliqueada y actualizar su icono a sólido
            if (tabButton) {
                tabButton.classList.add('active');
                var tType = tabButton.getAttribute('data-type');
                if (tType === 'like') {
                    tabButton.innerHTML = '▲ Me gusta';
                } else if (tType === 'dislike') {
                    tabButton.innerHTML = '▼ No me gusta';
                } else if (tType === 'favorite') {
                    tabButton.innerHTML = '★ Favoritos';
                }
            }
            
            // Verificar si es la primera carga (tiene la tarjeta inicial de "Cargando tu actividad...")
            var isFirstLoad = false;
            var initialMutedText = activityContent.querySelector('.muted');
            if (initialMutedText && initialMutedText.textContent.indexOf('Cargando tu actividad') !== -1) {
                isFirstLoad = true;
            }
            
            // Si es primera carga, sí mostramos el cargando dentro.
            // Si ya hay publicaciones, solo bajamos opacidad para evitar saltos bruscos (CLS)
            if (isFirstLoad) {
                activityContent.innerHTML = '<div class="post-card" style="text-align: center; padding: 40px 20px;"><p class="muted">Cargando actividad...</p></div>';
            } else {
                activityContent.classList.add('loading');
            }
            
            var xhr = new XMLHttpRequest();
            xhr.open('GET', '/PrivaNet/index.php?action=get_user_activity&type=' + encodeURIComponent(type), true);
            
            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    activityContent.classList.remove('loading');
                    if (xhr.status === 200) {
                        activityContent.innerHTML = xhr.responseText;
                    } else {
                        activityContent.innerHTML = '<div class="post-card" style="text-align: center; padding: 40px 20px;"><p class="muted" style="color: #ef4444;">Error al cargar las publicaciones de esta sección.</p></div>';
                    }
                }
            };
            
            xhr.send();
        }
        
        // Agregar manejadores de eventos clics directos para los botones de pestañas
        for (var i = 0; i < tabs.length; i++) {
            (function (tab) {
                tab.addEventListener('click', function () {
                    var type = tab.getAttribute('data-type');
                    loadActivity(type, tab);
                });
            })(tabs[i]);
        }
        
        // Cargar por defecto la primera pestaña ("like") al entrar
        var defaultTab = document.querySelector('.activity-tab[data-type="like"]');
        if (defaultTab) {
            loadActivity('like', defaultTab);
        }
    }
});
