document.addEventListener('DOMContentLoaded', function() {
    var lightboxModal        = document.getElementById('lightbox-modal');
    var lightboxImage        = document.getElementById('lightbox-image');
    var lightboxCloseBtn     = document.getElementById('lightbox-close');
    var lightboxAudioContainer = document.getElementById('lightbox-audio-container');
    var lightboxAudio        = document.getElementById('lightbox-audio');
    var lightboxAuthor       = document.getElementById('lightbox-author');
    var lightboxPostText     = document.getElementById('lightbox-post-text');
    var lightboxActions      = document.getElementById('lightbox-post-actions');
    var mediaContainer       = lightboxImage ? lightboxImage.closest('.lightbox-media-container') : null;

    if (!lightboxModal || !lightboxImage || !lightboxCloseBtn) {
        return;
    }

    // =========================================================
    // openLightbox — única función que muestra el modal
    // Parámetros:
    //   opts.imageSrc  — URL de la imagen (string o vacío)
    //   opts.audioSrc  — URL del audio   (string o vacío)
    //   opts.author    — texto del autor
    //   opts.textHtml  — HTML del cuerpo del post
    //   opts.actions   — nodo HTML o string con los botones de interacción
    // =========================================================
    function openLightbox(opts) {
        // ── Autor ─────────────────────────────────────────────
        if (lightboxAuthor) {
            lightboxAuthor.textContent = opts.author || '';
        }

        // ── Texto ─────────────────────────────────────────────
        if (lightboxPostText) {
            lightboxPostText.innerHTML = opts.textHtml || '';
        }

        // ── Imagen ────────────────────────────────────────────
        if (opts.imageSrc) {
            lightboxImage.setAttribute('src', opts.imageSrc);
            if (mediaContainer) mediaContainer.style.display = '';
            lightboxModal.classList.remove('text-only-mode');
        } else {
            lightboxImage.setAttribute('src', '');
            if (mediaContainer) mediaContainer.style.display = 'none';
            lightboxModal.classList.add('text-only-mode');
        }

        // ── Audio ─────────────────────────────────────────────
        if (opts.audioSrc && lightboxAudioContainer && lightboxAudio) {
            // Reemplazar la fuente y recargar
            lightboxAudio.innerHTML = '<source src="' + opts.audioSrc + '" type="audio/mpeg">';
            lightboxAudio.load();
            lightboxAudioContainer.style.display = 'block';

            // Autoplay al abrir (después de que la transición CSS inicie)
            lightboxAudio.play().catch(function() {
                // El navegador puede bloquear autoplay sin interacción previa;
                // en ese caso simplemente no se reproduce automáticamente.
            });
        } else if (lightboxAudioContainer) {
            lightboxAudioContainer.style.display = 'none';
            if (lightboxAudio) {
                lightboxAudio.pause();
                lightboxAudio.innerHTML = '';
            }
        }

        // ── Botones de interacción ────────────────────────────
        if (lightboxActions) {
            if (typeof opts.actions === 'string') {
                lightboxActions.innerHTML = opts.actions;
            } else if (opts.actions && opts.actions.nodeType) {
                lightboxActions.innerHTML = opts.actions.innerHTML;
            } else {
                lightboxActions.innerHTML = '';
            }
        }

        // Abrir modal
        lightboxModal.classList.add('open');
    }

    // =========================================================
    // closeLightbox
    // =========================================================
    function closeLightbox() {
        lightboxModal.classList.remove('open');

        // Detener audio inmediatamente
        if (lightboxAudio) {
            lightboxAudio.pause();
            lightboxAudio.currentTime = 0;
        }

        // Limpiar contenido después de la transición de salida (0.3s)
        setTimeout(function() {
            if (!lightboxModal.classList.contains('open')) {
                lightboxImage.setAttribute('src', '');
                if (lightboxAuthor)   lightboxAuthor.textContent  = '';
                if (lightboxPostText) lightboxPostText.innerHTML   = '';
                if (lightboxActions)  lightboxActions.innerHTML    = '';
                if (lightboxAudio)    lightboxAudio.innerHTML      = '';
                if (lightboxAudioContainer) lightboxAudioContainer.style.display = 'none';
            }
        }, 300);
    }

    // =========================================================
    // Delegación de eventos — click en .post-thumbnail
    // =========================================================
    document.addEventListener('click', function(event) {

        // ── Post card con imagen ──────────────────────────────
        if (event.target && event.target.classList.contains('post-thumbnail')) {
            var img      = event.target;
            var postCard = img.closest('.post-card');
            if (!postCard) return;

            var author   = postCard.querySelector('.post-header h3').textContent.trim();
            var textEl   = postCard.querySelector('.post-text');
            var textHtml = textEl ? textEl.innerHTML : '';

            // Audio: buscar la fuente dentro de .audio-media
            var audioSrc = '';
            var audioMedia = postCard.querySelector('.audio-media audio source');
            if (audioMedia) audioSrc = audioMedia.getAttribute('src') || '';

            // Botones: clonar el footer sin editar/eliminar
            var actionsFooter = postCard.querySelector('.post-actions');
            var actionsClone  = null;
            if (actionsFooter) {
                actionsClone = actionsFooter.cloneNode(true);
                actionsClone.querySelectorAll('.edit-post-btn, .delete-post-btn').forEach(function(el) {
                    el.remove();
                });
            }

            openLightbox({
                imageSrc : img.getAttribute('src') || '',
                audioSrc : audioSrc,
                author   : author,
                textHtml : textHtml,
                actions  : actionsClone
            });
        }

        // ── Search result card ────────────────────────────────
        var card = event.target.closest('.search-result-card');
        if (card) {
            var postId   = card.getAttribute('data-post-id') || '';
            var liked    = card.getAttribute('data-liked')     === '1';
            var disliked = card.getAttribute('data-disliked')  === '1';
            var fav      = card.getAttribute('data-favorited') === '1';

            var buttonsHtml =
                '<button type="button" class="action-btn like-btn '    + (liked    ? 'active' : '') + '" data-post-id="' + postId + '">' + (liked    ? '▲ Te gusta'    : '△ Me gusta')    + '</button>' +
                '<button type="button" class="action-btn dislike-btn ' + (disliked ? 'active' : '') + '" data-post-id="' + postId + '">' + (disliked ? '▼ Te disgusta' : '▽ No me gusta') + '</button>' +
                '<button type="button" class="action-btn fav-btn '     + (fav      ? 'active' : '') + '" data-post-id="' + postId + '">' + (fav      ? '★ Favorito'    : '☆ Favorito')    + '</button>';

            openLightbox({
                imageSrc : card.getAttribute('data-image') || '',
                audioSrc : card.getAttribute('data-audio') || '',
                author   : card.getAttribute('data-author') || '',
                textHtml : card.getAttribute('data-text')   || '',
                actions  : buttonsHtml
            });
        }
    });

    // =========================================================
    // Cerrar — botón, overlay, Escape
    // =========================================================
    lightboxCloseBtn.addEventListener('click', closeLightbox);

    lightboxModal.addEventListener('click', function(event) {
        if (event.target === lightboxModal) {
            closeLightbox();
        }
    });

    document.addEventListener('keydown', function(event) {
        if ((event.key === 'Escape' || event.keyCode === 27) && lightboxModal.classList.contains('open')) {
            closeLightbox();
        }
    });
});
