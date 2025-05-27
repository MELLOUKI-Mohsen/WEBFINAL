document.addEventListener('DOMContentLoaded', () => {});
    // Gestion du formulaire d'inscription
    const registerForm = document.getElementById('register-form');
    if (registerForm) {
        // Gestion des types de compte (freelancer/entreprise)
        const freelancerBtn = document.getElementById('freelancer-btn');
        const companyBtn = document.getElementById('company-btn');
        const accountTypeInput = document.getElementById('account-type');
        const freelancerFields = document.getElementById('freelancer-fields');
        const companyFields = document.getElementById('company-fields');
        
        // Vérifier si l'URL contient un paramètre de type
        const urlParams = new URLSearchParams(window.location.search);
        const accountType = urlParams.get('type');
        
        if (accountType === 'company') {
            setCompanyType();
        } else {
            setFreelancerType();
        }
        
        // Fonction pour définir le type freelancer
        function setFreelancerType() {
            freelancerBtn.classList.add('active');
            companyBtn.classList.remove('active');
            accountTypeInput.value = 'freelancer';
            freelancerFields.style.display = 'block';
            companyFields.style.display = 'none';
        }
    }
        // Fonction pour définir le type entreprise