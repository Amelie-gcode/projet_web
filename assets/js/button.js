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

document.addEventListener('DOMContentLoaded', function () {
    const hamburger = document.querySelector('.btn-hamburger');
    const cross = document.querySelector('.btn-cross');
    const navBar = document.querySelector('.navbar-nav');

    hamburger.addEventListener('click', function () {
        navBar.classList.add('active');
        cross.classList.add('active');
    })

    cross.addEventListener('click', function () {
        navBar.classList.remove('active');
        cross.classList.remove('active');
    })


})