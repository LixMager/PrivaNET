document.addEventListener('DOMContentLoaded', function() {
    var lightboxModal = document.getElementById('lightbox-modal');
    var lightboxImage = document.getElementById('lightbox-image');
    var lightboxCloseBtn = document.getElementById('lightbox-close');

    if (!lightboxModal || !lightboxImage || !lightboxCloseBtn) {
        return;
    }

    // Event delegation: catch clicks on elements with .post-thumbnail class
    document.addEventListener('click', function(event) {
        if (event.target && event.target.classList.contains('post-thumbnail')) {
            var img = event.target;
            var src = img.getAttribute('src');
            
            var postCard = img.closest('.post-card');
            if (postCard) {
                var author = postCard.querySelector('.post-header h3').textContent.trim();
                var date = postCard.querySelector('.post-header span').textContent.trim();
                
                // Get post text content if present
                var textEl = postCard.querySelector('.post-text');
                var textHtml = textEl ? textEl.innerHTML : '';
                
                // Check for audio player
                var audioMedia = postCard.querySelector('.audio-media audio');
                
                // Copy buttons
                var actionsFooter = postCard.querySelector('.post-actions');

                // Populating elements
                document.getElementById('lightbox-author').textContent = author;
                document.getElementById('lightbox-post-text').innerHTML = textHtml;

                // Audio setup
                var lightboxAudioContainer = document.getElementById('lightbox-audio-container');
                var lightboxAudio = document.getElementById('lightbox-audio');
                if (audioMedia && lightboxAudioContainer && lightboxAudio) {
                    var audioSource = audioMedia.querySelector('source');
                    if (audioSource) {
                        var audioSrc = audioSource.getAttribute('src');
                        lightboxAudio.innerHTML = '<source src="' + audioSrc + '" type="audio/mpeg">';
                        lightboxAudio.load();
                        lightboxAudioContainer.style.display = 'block';
                    }
                } else if (lightboxAudioContainer) {
                    lightboxAudioContainer.style.display = 'none';
                    if (lightboxAudio) lightboxAudio.src = '';
                }

                // Cloned interaction buttons (edit/delete excluded in lightbox)
                var lightboxActions = document.getElementById('lightbox-post-actions');
                if (actionsFooter && lightboxActions) {
                    var cloned = actionsFooter.cloneNode(true);
                    cloned.querySelectorAll('.edit-post-btn, .delete-post-btn').forEach(function(el) {
                        el.remove();
                    });
                    lightboxActions.innerHTML = cloned.innerHTML;
                }
            }

            // Set content and display the lightbox modal
            lightboxImage.setAttribute('src', src);
            lightboxModal.classList.add('open');
        }

        // ── Search result card click ──────────────────────────
        var card = event.target.closest('.search-result-card');
        if (card) {
            var author   = card.getAttribute('data-author') || '';
            var imageSrc = card.getAttribute('data-image') || '';
            var audioSrc = card.getAttribute('data-audio') || '';
            var postId   = card.getAttribute('data-post-id') || '';
            var rawText  = card.getAttribute('data-text') || '';
            var liked    = card.getAttribute('data-liked') === '1';
            var disliked = card.getAttribute('data-disliked') === '1';
            var fav      = card.getAttribute('data-favorited') === '1';

            // Author
            document.getElementById('lightbox-author').textContent = author;

            // Text (data attr is HTML-escaped plain text; render as-is)
            document.getElementById('lightbox-post-text').textContent = rawText;

            // Image
            var mediaContainer = lightboxImage.closest('.lightbox-media-container');
            if (imageSrc) {
                lightboxImage.setAttribute('src', imageSrc);
                if (mediaContainer) mediaContainer.style.display = '';
            } else {
                lightboxImage.setAttribute('src', '');
                if (mediaContainer) mediaContainer.style.display = 'none';
            }

            // Audio
            var lightboxAudioContainer = document.getElementById('lightbox-audio-container');
            var lightboxAudio = document.getElementById('lightbox-audio');
            if (audioSrc && lightboxAudioContainer && lightboxAudio) {
                lightboxAudio.innerHTML = '<source src="' + audioSrc + '" type="audio/mpeg">';
                lightboxAudio.load();
                lightboxAudioContainer.style.display = 'block';
            } else if (lightboxAudioContainer) {
                lightboxAudioContainer.style.display = 'none';
                if (lightboxAudio) lightboxAudio.src = '';
            }

            // Interaction buttons built from data attrs
            var lightboxActions = document.getElementById('lightbox-post-actions');
            if (lightboxActions) {
                lightboxActions.innerHTML =
                    '<button type="button" class="action-btn like-btn ' + (liked ? 'active' : '') + '" data-post-id="' + postId + '">' +
                        (liked ? '▲ Te gusta' : '△ Me gusta') +
                    '</button>' +
                    '<button type="button" class="action-btn dislike-btn ' + (disliked ? 'active' : '') + '" data-post-id="' + postId + '">' +
                        (disliked ? '▼ Te disgusta' : '▽ No me gusta') +
                    '</button>' +
                    '<button type="button" class="action-btn fav-btn ' + (fav ? 'active' : '') + '" data-post-id="' + postId + '">' +
                        (fav ? '★ Favorito' : '☆ Favorito') +
                    '</button>';
            }

            lightboxModal.classList.add('open');
        }
    });

    // Close lightbox on click on close button
    lightboxCloseBtn.addEventListener('click', function() {
        closeLightbox();
    });

    // Close lightbox on click outside the image content (on the overlay itself)
    lightboxModal.addEventListener('click', function(event) {
        if (event.target === lightboxModal) {
            closeLightbox();
        }
    });

    // Close lightbox on Escape key
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape' || event.keyCode === 27) {
            if (lightboxModal.classList.contains('open')) {
                closeLightbox();
            }
        }
    });

    function closeLightbox() {
        lightboxModal.classList.remove('open');
        
        // Stop audio playing inside the lightbox if any
        var lightboxAudio = document.getElementById('lightbox-audio');
        if (lightboxAudio) {
            lightboxAudio.pause();
            lightboxAudio.currentTime = 0;
        }

        // Clear the image source after the transition finishes to avoid flash on reopen
        setTimeout(function() {
            if (!lightboxModal.classList.contains('open')) {
                lightboxImage.setAttribute('src', '');
                document.getElementById('lightbox-author').textContent = '';
                document.getElementById('lightbox-post-text').innerHTML = '';
                var lightboxActions = document.getElementById('lightbox-post-actions');
                if (lightboxActions) lightboxActions.innerHTML = '';
            }
        }, 300); // matches the CSS transition duration of 0.3s
    }
});
