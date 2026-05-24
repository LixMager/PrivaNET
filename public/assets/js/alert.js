window.customAlert = function(message, title = 'Alerta') {
    var modal = document.getElementById('custom-alert-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'custom-alert-modal';
        modal.className = 'modal-overlay';
        modal.innerHTML = `
            <div class="modal-content">
                <div class="modal-header">
                    <h3 id="custom-alert-title"></h3>
                    <button type="button" class="modal-close-btn" id="close-alert-modal">✕</button>
                </div>
                <div class="modal-body">
                    <p class="modal-confirm-text" id="custom-alert-message"></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="action-btn modal-btn-primary" id="confirm-alert-btn">Aceptar</button>
                </div>
            </div>
        `;
        document.body.appendChild(modal);

        var closeBtn = modal.querySelector('#close-alert-modal');
        var confirmBtn = modal.querySelector('#confirm-alert-btn');

        function closeModal() {
            modal.classList.remove('open');
            setTimeout(function() {
                modal.style.display = 'none';
            }, 300);
        }

        closeBtn.addEventListener('click', closeModal);
        confirmBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', function(e) {
            if (e.target === modal) {
                closeModal();
            }
        });
    }

    modal.querySelector('#custom-alert-title').textContent = title;
    modal.querySelector('#custom-alert-message').textContent = message;
    
    modal.style.display = 'flex';
    // Trigger reflow
    void modal.offsetWidth;
    modal.classList.add('open');
};
