document.addEventListener('DOMContentLoaded', function() {
    // First modal page connexion
    const modal = document.getElementById('modal');
    const openModalButton = document.querySelector('.user-items');
    const closeButton = document.querySelector('.close-button');
    const accessInput = document.getElementById('access'); // Correction ici

    if (accessInput && accessInput.value === '1') {
        modal.style.display = 'block';
    }
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
function supprimer(deleteUrl) {
    const modal = document.querySelector('.confirm-modal');
    const confirmYes = document.getElementById('confirmYes');
    const confirmNo = document.getElementById('confirmNo');

    modal.style.display = 'block';

    confirmYes.onclick = function() {
        console.log('Entreprise supprimée');
        modal.style.display = 'none';
        window.location.href = deleteUrl; // Redirect to the specified URL
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
