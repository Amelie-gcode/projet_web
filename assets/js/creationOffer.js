document.addEventListener('DOMContentLoaded', function() {
    const addDomainButton = document.getElementById('add-domain');
    const domainsContainer = document.getElementById('domains-container');
    const descriptionCourte = document.getElementById('description-courte');
    const charCount = document.querySelector('.char-count');

    addDomainButton.addEventListener('click', function() {
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

    domainsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-domain')) {
            e.target.parentElement.remove();
            const remainingInputs = document.querySelectorAll('.domain-input');
            if (remainingInputs.length === 1) {
                remainingInputs[0].querySelector('.remove-domain').style.display = 'none';
            }
        }
    });

    descriptionCourte.addEventListener('input', function() {
        const remaining = this.value.length;
        charCount.textContent = `${remaining}/250`;
    });
});