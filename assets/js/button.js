document.addEventListener('DOMContentLoaded', function () {
    const heartIcons = document.querySelectorAll('.heart-icon');

    heartIcons.forEach(heartIcon => {
        heartIcon.addEventListener('click', function () {
            this.classList.toggle('far');
            this.classList.toggle('fas');
            this.classList.toggle('liked');
        });
    });
});

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

