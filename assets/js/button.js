document.addEventListener('DOMContentLoaded', function () {
    const heartIcon = document.querySelector('.heart-icon');

    heartIcon.addEventListener('click', function () {
        heartIcon.classList.toggle('far');
        heartIcon.classList.toggle('fas');
        heartIcon.classList.toggle('liked');
    })
})

/* bouton page etudiants*/


function modifierEtudiant() {
    alert('Modifier l\'étudiant');
    // Logique pour modifier un étudiant
}

function supprimerEtudiant() {
    alert('Supprimer l\'étudiant');
    // Logique pour supprimer un étudiant
}

function ajouterEtudiant() {
    alert('Ajouter un nouvel étudiant');
    // Logique pour ajouter un nouvel étudiant
}