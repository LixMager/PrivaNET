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
