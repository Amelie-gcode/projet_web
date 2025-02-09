document.addEventListener('DOMContentLoaded', function() {
    const userIcon = document.querySelector('.user-icon');
    const dropdownMenu = document.querySelector('.dropdown-menu');

    // Gérer le clic sur l'icône
    userIcon.addEventListener('click', function(e) {
        e.stopPropagation();
        dropdownMenu.classList.toggle('active');
        // Met à jour l'attribut aria-expanded pour l'accessibilité
        this.setAttribute('aria-expanded', dropdownMenu.classList.contains('active'));
    });

    // Fermer le menu si on clique ailleurs dans la page
    document.addEventListener('click', function(e) {
        if (!dropdownMenu.contains(e.target) && !userIcon.contains(e.target)) {
            dropdownMenu.classList.remove('active');
            userIcon.setAttribute('aria-expanded', 'false');
        }
    });
});