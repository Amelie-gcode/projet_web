// changement remplissage du coeur
document.addEventListener('DOMContentLoaded', function () {
    const heartIcons = document.querySelectorAll('.heart-icon');

    heartIcons.forEach(heartIcon => {
        heartIcon.addEventListener('click', function () {
            this.classList.toggle('far');
            this.classList.toggle('fas');
            this.classList.toggle('liked');
        });
    });

    /* bouton page fav*/
    const buttons = document.querySelectorAll('.fav-btn');
    buttons.forEach(button => {
        button.addEventListener('click', function() {
            // Désactive tous les boutons
            buttons.forEach(btn => btn.classList.remove('active'));
            // Active le bouton cliqué
            this.classList.add('active');
        });
    });

    // Gestion des compétences
    const addDomainButton = document.getElementById('add-domain');
    const domainsContainer = document.getElementById('domains-container');

    if (addDomainButton && domainsContainer) {
        // Assurer que les boutons de suppression sont correctement configurés au chargement
        const domainInputs = document.querySelectorAll('.domain-input');
        if (domainInputs.length > 1) {
            domainInputs.forEach((input, index) => {
                const removeBtn = input.querySelector('.remove-domain');
                if (removeBtn) {
                    removeBtn.style.display = index === 0 ? 'none' : 'inline-block';
                }
            });
        }

        // Gestion du clic sur le bouton d'ajout
        addDomainButton.addEventListener('click', function() {
            const domainInputs = document.querySelectorAll('.domain-input');

            if (domainInputs.length < 5) { // Limite à 5 domaines
                // Cloner le premier élément de sélection
                const firstDomainInput = domainInputs[0].cloneNode(true);

                // Réinitialiser la sélection
                const select = firstDomainInput.querySelector('select');
                if (select) {
                    select.selectedIndex = 0;
                }

                // Montrer le bouton de suppression
                const removeBtn = firstDomainInput.querySelector('.remove-domain');
                if (removeBtn) {
                    removeBtn.style.display = 'inline-block';
                }

                // Ajouter le clone au conteneur
                domainsContainer.appendChild(firstDomainInput);
            }

            // Mettre à jour l'affichage des boutons de suppression
            updateRemoveButtons();
        });

        // Délégation d'événements pour gérer la suppression
        domainsContainer.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-domain')) {
                e.target.closest('.domain-input').remove();

                // Mettre à jour l'affichage des boutons après suppression
                updateRemoveButtons();
            }
        });

        // Fonction pour mettre à jour l'affichage des boutons de suppression
        function updateRemoveButtons() {
            const domainInputs = document.querySelectorAll('.domain-input');

            // Toujours cacher le bouton de suppression du premier élément
            if (domainInputs.length >= 1) {
                const firstRemoveBtn = domainInputs[0].querySelector('.remove-domain');
                if (firstRemoveBtn) {
                    firstRemoveBtn.style.display = 'none';
                }
            }

            // S'il y a plus d'un élément, montrer les boutons des autres
            if (domainInputs.length > 1) {
                for (let i = 1; i < domainInputs.length; i++) {
                    const removeBtn = domainInputs[i].querySelector('.remove-domain');
                    if (removeBtn) {
                        removeBtn.style.display = 'inline-block';
                    }
                }
            }
        }
    }
});
document.addEventListener("DOMContentLoaded", function () {
    const stars = document.querySelectorAll(".rating-stars .star");
    const ratingValue = document.getElementById("rating-value");
    const selectedRating = document.getElementById("selected-rating");

    stars.forEach(star => {
        star.addEventListener("click", function () {
            const value = parseInt(this.getAttribute("data-value")); // Convertir en nombre
            console.log("Note sélectionnée :", value); // Débogage

            ratingValue.value = value; // Met à jour l'input caché
            selectedRating.textContent = value; // Met à jour l'affichage

            // Réinitialiser toutes les étoiles
            stars.forEach(s => {
                s.classList.remove("fas");
                s.classList.add("far");
            });

            // Colorer les étoiles jusqu'à celle cliquée
            for (let i = 0; i < value; i++) {
                stars[i].classList.remove("far");
                stars[i].classList.add("fas");
            }
        });

        // Effet de survol
        star.addEventListener("mouseover", function () {
            const value = parseInt(this.getAttribute("data-value"));

            stars.forEach(s => s.classList.remove("hovered"));
            for (let i = 0; i < value; i++) {
                stars[i].classList.add("hovered");
            }
        });

        star.addEventListener("mouseleave", function () {
            stars.forEach(s => s.classList.remove("hovered"));
        });
    });

    // envoie form filtre plus search

    const searchForm = document.querySelector(".search-container"); // Sélectionne le formulaire de recherche
    const filtersForm = document.querySelector(".filters-container"); // Sélectionne le formulaire des filtres

    if (!searchForm || !filtersForm) return; // Sécurité : on ne continue que si les formulaires existent

    searchForm.addEventListener("submit", function (event) {
        event.preventDefault(); // Empêche l'envoi classique du formulaire

        const formData = new FormData(searchForm);
        const filterFormData = new FormData(filtersForm);

        // Fusionne les données des deux formulaires
        filterFormData.forEach((value, key) => {
            formData.append(key, value);
        });

        const filteredParams = new URLSearchParams();

        formData.forEach((value, key) => {
            if (key === "domaines[]") {
                if (value.trim() !== "") {
                    filteredParams.append(key, value);
                }
            } else if (value.trim() !== "") {
                filteredParams.append(key, value);
            }
        });

        window.location.href = window.location.pathname + "?" + filteredParams.toString();
    });



 });
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".comment-card").forEach(card => {
        let firstName = card.querySelector(".firstname").value.trim();
        let lastName = card.querySelector(".lastname").value.trim();
        let avatar = card.querySelector(".user-avatar");

        if (firstName && lastName) {
            let initials = firstName.charAt(0).toUpperCase() + lastName.charAt(0).toUpperCase();
            avatar.textContent = initials;
        }
    });
});
