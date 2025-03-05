document.addEventListener('DOMContentLoaded', function() {
    // Character counter for description
    const descriptionCourte = document.getElementById('description-courte');
    if (descriptionCourte) {
        const charCount = document.querySelector('.char-count');
        descriptionCourte.addEventListener('input', function() {
            const remaining = this.value.length;
            charCount.textContent = `${remaining}/250`;
        });
    }

    // Generic validation for all forms
    const forms = {
        '.offer-form': validateOfferForm,
        '.account-form': validateAccountForm,
        '.company-form': validateCompanyForm,
        '.form form': validateApplicationForm // Added the application form selector
    };

    // Set up validation for the form that exists on the page
    for (const [selector, validationFn] of Object.entries(forms)) {
        const form = document.querySelector(selector);
        if (form) {
            form.addEventListener('submit', function(event) {
                event.preventDefault();
                removeAllErrorMessages();

                if (validationFn()) {
                    console.log('Form is valid, submitting...');
                    this.submit();
                } else {
                    // Scroll to the first error
                    const firstError = document.querySelector('.error-message');
                    if (firstError) {
                        firstError.parentNode.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    }
                }
            });
        }
    }

    // Validation function for application form (postuler.html)
    function validateApplicationForm() {
        let isValid = true;

        // Text inputs validation
        const textInputs = {
            'name': 'Veuillez entrer votre nom',
            'prenom': 'Veuillez entrer votre prénom',
            'email': 'Veuillez entrer votre email',
            'message': 'Veuillez entrer un message'
        };

        // Check text fields
        for (const [id, message] of Object.entries(textInputs)) {
            const field = document.getElementById(id);
            if (!validateField(field, message)) {
                isValid = false;
            }
        }

        // Check civilité (dropdown)
        const civilite = document.getElementById('civilite');
        if (civilite && civilite.value === '') {
            showError(civilite, 'Veuillez sélectionner une civilité');
            isValid = false;
        }

        // Check email format
        const email = document.getElementById('email');
        if (email && email.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                showError(email, 'Veuillez entrer une adresse email valide');
                isValid = false;
            }
        }

        // Check if major status is selected (radio)
        const majeurRadios = document.querySelectorAll('input[name="majeur"]');
        let majeurSelected = false;
        majeurRadios.forEach(radio => {
            if (radio.checked) {
                majeurSelected = true;
            }
        });

        if (majeurRadios.length > 0 && !majeurSelected) {
            showError(document.querySelector('.radio-group'), 'Veuillez indiquer si vous êtes majeur');
            isValid = false;
        }

        // Check if CV is uploaded
        const cv = document.getElementById('cv');
        if (cv && cv.files.length === 0) {
            showError(cv, 'Veuillez ajouter votre CV');
            isValid = false;
        } else if (cv && cv.files.length > 0) {
            // Check file type
            const allowedTypes = ['.pdf', '.doc', '.docx'];
            let validType = false;

            for (const type of allowedTypes) {
                if (cv.value.toLowerCase().endsWith(type)) {
                    validType = true;
                    break;
                }
            }

            if (!validType) {
                showError(cv, 'Format de fichier non valide. Utilisez PDF, DOC ou DOCX.');
                isValid = false;
            }
        }

        return isValid;
    }

    // Validation function for offer form
    function validateOfferForm() {
        let isValid = true;

        // Text inputs validation
        const textInputs = {
            'titre': 'Veuillez entrer un titre',
            'description-courte': 'Veuillez entrer une description courte',
            'description-longue': 'Veuillez entrer une description détaillée',
            'profil': 'Veuillez décrire le profil recherché'
        };

        // Check text fields
        for (const [id, message] of Object.entries(textInputs)) {
            const field = document.getElementById(id);
            if (!validateField(field, message)) {
                isValid = false;
            }
        }

        // Check company selection
        const entreprise = document.getElementById('entreprise');
        if (entreprise && entreprise.value === '') {
            showError(entreprise, 'Veuillez sélectionner une entreprise');
            isValid = false;
        }

        // Check salary (must be a number)
        const salaire = document.getElementById('salaire');
        if (salaire && !validateField(salaire, 'Veuillez entrer un salaire')) {
            isValid = false;
        } else if (salaire && !/^\d+(\.\d{1,2})?$/.test(salaire.value)) {
            showError(salaire, 'Le salaire doit être un nombre (ex: 1500 ou 1500.50)');
            isValid = false;
        }

        // Check duration (must be a number)
        const duree = document.getElementById('duree');
        if (duree && !validateField(duree, 'Veuillez entrer une durée')) {
            isValid = false;
        } else if (duree && !/^\d+$/.test(duree.value)) {
            showError(duree, 'La durée doit être un nombre entier positif');
            isValid = false;
        }

        // Check contract type (radio buttons)
        const typeInputs = document.querySelectorAll('input[name="type"]');
        let typeSelected = false;
        typeInputs.forEach(input => {
            if (input.checked) {
                typeSelected = true;
            }
        });

        if (typeInputs.length > 0 && !typeSelected) {
            showError(typeInputs[0].closest('.type-container'), 'Veuillez sélectionner un type de contrat');
            isValid = false;
        }

        // Check domains (at least one required)
        const domainInputs = document.querySelectorAll('input[name="domaines[]"]');
        let domainFilled = false;

        domainInputs.forEach(input => {
            if (input.value.trim() !== '') {
                domainFilled = true;
            }
        });

        if (domainInputs.length > 0 && !domainFilled) {
            showError(domainInputs[0], 'Veuillez entrer au moins un domaine');
            isValid = false;
        }

        return isValid;
    }

    // Validation function for account form
    function validateAccountForm() {
        let isValid = true;

        // Text inputs validation
        const textInputs = {
            'nom': 'Veuillez entrer votre nom',
            'prenom': 'Veuillez entrer votre prénom',
            'email': 'Veuillez entrer votre email',
            'password': 'Veuillez entrer un mot de passe'
        };

        // Check text fields
        for (const [id, message] of Object.entries(textInputs)) {
            const field = document.getElementById(id);
            if (!validateField(field, message)) {
                isValid = false;
            }
        }

        // Check email format
        const email = document.getElementById('email');
        if (email && email.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                showError(email, 'Veuillez entrer une adresse email valide');
                isValid = false;
            }
        }

        // Check role selection (radio buttons)
        const roleInputs = document.querySelectorAll('input[name="role"]');
        let roleSelected = false;
        roleInputs.forEach(input => {
            if (input.checked) {
                roleSelected = true;
            }
        });

        if (roleInputs.length > 0 && !roleSelected) {
            showError(roleInputs[0].closest('fieldset') || roleInputs[0].parentNode.parentNode, 'Veuillez sélectionner un statut');
            isValid = false;
        }

        return isValid;
    }

    // Validation function for company form
    function validateCompanyForm() {
        let isValid = true;

        // Text inputs validation
        const textInputs = {
            'nom': 'Veuillez entrer le nom de l\'entreprise',
            'description': 'Veuillez entrer une description',
            'email': 'Veuillez entrer un email de contact',
            'telephone': 'Veuillez entrer un numéro de téléphone'
        };

        // Check text fields
        for (const [id, message] of Object.entries(textInputs)) {
            const field = document.getElementById(id);
            if (!validateField(field, message)) {
                isValid = false;
            }
        }

        // Check email format
        const email = document.getElementById('email');
        if (email && email.value) {
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email.value)) {
                showError(email, 'Veuillez entrer une adresse email valide');
                isValid = false;
            }
        }

        // Check phone format (accepts various formats)
        const phone = document.getElementById('telephone');
        if (phone && phone.value) {
            const phoneRegex = /^(\+\d{1,3}\s?)?(\d{1,4}[\s.-]?){1,4}\d{1,4}$/;
            if (!phoneRegex.test(phone.value)) {
                showError(phone, 'Veuillez entrer un numéro de téléphone valide');
                isValid = false;
            }
        }

        return isValid;
    }

    // Helper function to validate a field
    function validateField(field, errorMessage) {
        if (!field) return true; // Skip if field doesn't exist on this form

        if (field.value.trim() === '') {
            showError(field, errorMessage);
            return false;
        }
        return true;
    }

    // Helper function to show error message
    function showError(element, message) {
        if (!element) return; // Safety check

        // Find the appropriate parent container based on the form type
        let parentElement;

        if (element.closest('.offer-form-group')) {
            parentElement = element.closest('.offer-form-group');
        } else if (element.closest('.account-form-group')) {
            parentElement = element.closest('.account-form-group');
        } else if (element.closest('.company-form-group')) {
            parentElement = element.closest('.company-form-group');
        } else if (element.closest('.form-group')) {
            parentElement = element.closest('.form-group');
        } else if (element.closest('.form-field')) {
            parentElement = element.closest('.form-field');
        } else if (element.closest('.radio-group')) {
            parentElement = element.closest('.radio-group');
        } else {
            parentElement = element.parentNode;
        }

        // Check if error message already exists
        if (!parentElement.querySelector('.error-message')) {
            const errorDiv = document.createElement('div');
            errorDiv.className = 'error-message';
            errorDiv.textContent = message;
            errorDiv.setAttribute('role', 'alert');

            // Add error class to the input
            element.classList.add('input-error');

            // Insert error after the input/select
            parentElement.appendChild(errorDiv);
        }
    }

    // Remove all error messages
    function removeAllErrorMessages() {
        document.querySelectorAll('.error-message').forEach(error => error.remove());
        document.querySelectorAll('.input-error').forEach(input => input.classList.remove('input-error'));
    }

    // Add event listeners for numeric input fields
    const salaire = document.getElementById('salaire');
    if (salaire) {
        salaire.addEventListener('input', function() {
            this.value = this.value.replace(/[^\d.]/g, '');

            // Ensure only one decimal point
            const parts = this.value.split('.');
            if (parts.length > 2) {
                this.value = parts[0] + '.' + parts.slice(1).join('');
            }
        });
    }

    // For duration field - allow only integers
    const duree = document.getElementById('duree');
    if (duree) {
        duree.addEventListener('input', function() {
            this.value = this.value.replace(/\D/g, '');
        });
    }

    // Add CSS for error styling
    const style = document.createElement('style');
    style.textContent = `
        .error-message {
            color: #d32f2f;
            font-size: 0.85rem;
            margin-top: 0.25rem;
            font-weight: 500;
        }

        .input-error {
            border: 1px solid #d32f2f !important;
            background-color: #fff8f8;
        }
    `;
    document.head.appendChild(style);
});