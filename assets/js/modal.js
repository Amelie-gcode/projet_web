document.addEventListener('DOMContentLoaded', function() {
    // First modal (original)
    const modal = document.getElementById('modal');
    const openModalButton = document.querySelector('.user-items');
    const closeButton = document.querySelector('.close-button');

    if (openModalButton) {
        openModalButton.addEventListener('click', function(event) {
            event.preventDefault();
            modal.style.display = 'block';
        });
    }

    if (closeButton) {
        closeButton.addEventListener('click', function() {
            modal.style.display = 'none';
        });
    }
});

// Delete confirmation modal functionality
function supprimerEtudiant() {
    const modal = document.getElementById('deleteConfirmModal');
    const confirmYes = document.getElementById('confirmYes');
    const confirmNo = document.getElementById('confirmNo');

    modal.style.display = 'block';

    confirmYes.onclick = function() {
        // Add your deletion logic here
        console.log('Compte supprimé');
        modal.style.display = 'none';
    }

    confirmNo.onclick = function() {
        modal.style.display = 'none';
    }

    window.onclick = function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    }
}