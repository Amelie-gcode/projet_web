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


    const addDomainButton = document.getElementById('add-domain');
    const domainsContainer = document.getElementById('domains-container');

    if (addDomainButton && !addDomainButton.dataset.clickAttached) {
        addDomainButton.addEventListener('click', function () {
            const domainInputs = document.querySelectorAll('.domain-input');
            if (domainInputs.length < 5) { // Limite à 5 domaines
                const newDomain = document.createElement('div');
                newDomain.className = 'domain-input';

                // Get the skills data from the first select element
                const firstSelect = document.querySelector('.domain-input select');
                const skillOptions = firstSelect ? firstSelect.innerHTML : '';

                newDomain.innerHTML = `
                <select name="domaines[]" required>
                    ${skillOptions}
                </select>
                <button type="button" class="remove-domain">-</button>
            `;

                domainsContainer.appendChild(newDomain);

                // Afficher les boutons de suppression
                document.querySelectorAll('.remove-domain').forEach(button => {
                    button.style.display = 'flex';
                });
            }
        });
        addDomainButton.dataset.clickAttached = 'true';
    }

    domainsContainer.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-domain')) {
            e.target.parentElement.remove();
            const remainingInputs = document.querySelectorAll('.domain-input');
            if (remainingInputs.length === 1) {
                remainingInputs[0].querySelector('.remove-domain').style.display = 'none';
            }
        }
    });
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
});

