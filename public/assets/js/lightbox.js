document.addEventListener('DOMContentLoaded', function() {
    var lightboxModal = document.getElementById('lightbox-modal');
    var lightboxImage = document.getElementById('lightbox-image');
    var lightboxCaption = document.getElementById('lightbox-caption');
    var lightboxCloseBtn = document.getElementById('lightbox-close');

    if (!lightboxModal || !lightboxImage || !lightboxCaption || !lightboxCloseBtn) {
        return;
    }

    // Event delegation: catch clicks on elements with .post-thumbnail class
    document.addEventListener('click', function(event) {
        if (event.target && event.target.classList.contains('post-thumbnail')) {
            var img = event.target;
            var src = img.getAttribute('src');
            
            // Try to find the username of the author
            var captionText = 'Publicación';
            var postCard = img.closest('.post-card');
            if (postCard) {
                var authorHeader = postCard.querySelector('.post-header h3');
                if (authorHeader) {
                    captionText = 'Publicación de ' + authorHeader.textContent.trim();
                }
            }

            // Set content and display the lightbox modal
            lightboxImage.setAttribute('src', src);
            lightboxCaption.textContent = captionText;
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
        // Clear the image source after the transition finishes to avoid flash on reopen
        setTimeout(function() {
            if (!lightboxModal.classList.contains('open')) {
                lightboxImage.setAttribute('src', '');
                lightboxCaption.textContent = '';
            }
        }, 300); // matches the CSS transition duration of 0.3s
    }
});
