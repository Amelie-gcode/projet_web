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
                newDomain.innerHTML = `
                <input type="text" name="domaines[]" required>
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