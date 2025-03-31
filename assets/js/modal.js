document.addEventListener('DOMContentLoaded', function() {
    // First modal page connexion
    const modal = document.getElementById('modal');
    const openModalButtons = document.querySelectorAll('.auth');
    const closeButton = document.querySelector('.close-button');

    // Ne vérifier la présence d'un message d'erreur que si l'accès est demandé
    // ou si une action de connexion a été tentée

    if (modal && modal.getAttribute('data-show') === 'true') {
        modal.style.display = 'block';
    }

    openModalButtons.forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();
            modal.style.display = 'block';
        });
    });

    if (closeButton) {
        closeButton.addEventListener('click', function() {
            modal.style.display = 'none';
            modal.setAttribute('data-show', 'false');

            history.replaceState(null, null, window.location.pathname + window.location.search.replace(/[?&]login_error=[^&]+/, ''));

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
