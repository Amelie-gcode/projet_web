// dropdown menu

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

//scroll to top

document.addEventListener('DOMContentLoaded', function () {
    const scrollButton = document.getElementById('scrollToTop');

    window.addEventListener('scroll', function () {
        if (window.scrollY > 105) {
            scrollButton.style.display = 'block';
        } else {
            scrollButton.style.display = 'none';
        }
    });

    scrollButton.addEventListener('click', function () {
        window.scrollTo({
            top: 105,
            behavior: 'smooth'
        });
        // window.scrollTo(0, 0);
    })
});

//scroll form

document.addEventListener('DOMContentLoaded', function () {
    const scrollButton = document.querySelector('.scrolling-form-box');

    window.addEventListener('scroll', function () {
        if (window.scrollY < 1400) {
            scrollButton.style.display = 'flex';
        } else {
            scrollButton.style.display = 'none';
        }
    });

    scrollButton.addEventListener('click', function () {
        const targetElement = document.getElementById('formulaire');

        if (targetElement) {
            targetElement.scrollIntoView({
                behavior: 'smooth'
            });
        }
    });

});

// menu burger

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