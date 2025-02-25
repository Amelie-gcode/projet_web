document.addEventListener('DOMContentLoaded', function () {
    const heartIcon = document.querySelector('.heart-icon');

    heartIcon.addEventListener('click', function () {
        heartIcon.classList.toggle('far');
        heartIcon.classList.toggle('fas');
        heartIcon.classList.toggle('liked');
    })
})


/* bouton page etudiants*/

function supprimerEtudiant() {
    alert('Supprimer l\'étudiant');
    // Logique pour supprimer un étudiant
}

/* bouton page fav*/

document.addEventListener('DOMContentLoaded', function() {
    const buttons = document.querySelectorAll('.fav-btn');

    buttons.forEach(button => {
        button.addEventListener('click', function() {
            // Désactive tous les boutons
            buttons.forEach(btn => btn.classList.remove('active'));
            // Active le bouton cliqué
            this.classList.add('active');
        });
    });
});

