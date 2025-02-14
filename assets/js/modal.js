// Ajoutez ceci à votre fichier nav.js ou créez un nouveau fichier JS
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('modal');
    const openModalButton = document.querySelector('.user-items');
    const closeButton = document.querySelector('.close-button');

    openModalButton.addEventListener('click', function(event) {
        event.preventDefault();
        modal.style.display = 'block';
    });

    closeButton.addEventListener('click', function() {
        modal.style.display = 'none';
    });

    window.addEventListener('click', function(event) {
        if (event.target === modal) {
            modal.style.display = 'none';
        }
    });
});

// document.addEventListener('DOMContentLoaded', function() {
//     const modal = document.getElementById('modal');
//     const openModalButtons = document.querySelectorAll('.user-items');
//     const closeButton = document.querySelector('.close-button');
//
//     openModalButtons.forEach(function(button) {
//         button.addEventListener('click', function(event) {
//             event.preventDefault();
//             modal.style.display = 'block';
//         });
//     });
//
//     closeButton.addEventListener('click', function() {
//         modal.style.display = 'none';
//     });
//
//     window.addEventListener('click', function(event) {
//         if (event.target == modal) {
//             modal.style.display = 'none';
//         }
//     });
// });