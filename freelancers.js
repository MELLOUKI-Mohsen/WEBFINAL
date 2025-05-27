document.addEventListener('DOMContentLoaded', () => {
    // Gestion de la recherche de freelancers
    const searchInput = document.getElementById('freelancer-search');
    const searchButton = searchInput?.nextElementSibling;
    
    if (searchButton) {
        searchButton.addEventListener('click', () => {
            searchFreelancers();
        });
    }
    
    if (searchInput) {
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                searchFreelancers();
            }
        });
    }
    
    // Gestion des filtres
    const skillFilter = document.getElementById('skill-filter');
    const experienceFilter = document.getElementById('experience-filter');
    
    if (skillFilter) {
        skillFilter.addEventListener('change', () => {
            filterFreelancers();
        });
    }
    
    if (experienceFilter) {
        experienceFilter.addEventListener('change', () => {
            filterFreelancers();
        });
    }
    
    // Fonction de recherche (simulation)
    function searchFreelancers() {
        const searchTerm = searchInput.value.toLowerCase();
        console.log(`Recherche de freelancers avec le terme: ${searchTerm}`);
        
        // Ici, vous feriez normalement une requête AJAX vers le serveur
        // Pour l'instant, nous simulons juste un message dans la console
        
        // Exemple de code pour une requête AJAX:
        /*
        fetch(`php/get_freelancers.php?search=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(data => {
                // Mettre à jour l'interface avec les résultats
                updateFreelancersList(data);
            })
            .catch(error => {
                console.error('Erreur lors de la recherche:', error);
            });
        */
    }
    
    // Fonction de filtrage (simulation)
    function filterFreelancers() {
        const skill = skillFilter.value;
        const experience = experienceFilter.value;
        
        console.log(`Filtrage des freelancers - Compétence: ${skill}, Expérience: ${experience}`);
        
        // Ici, vous feriez normalement une requête AJAX vers le serveur
        // Pour l'instant, nous simulons juste un message dans la console
        
        // Exemple de code pour une requête AJAX:
        /*
        fetch(`php/get_freelancers.php?skill=${encodeURIComponent(skill)}&experience=${encodeURIComponent(experience)}`)
            .then(response => response.json())
            .then(data => {
                // Mettre à jour l'interface avec les résultats
                updateFreelancersList(data);
            })
            .catch(error => {
                console.error('Erreur lors du filtrage:', error);
            });
        */
    }
    
    // Fonction pour mettre à jour la liste des freelancers (à implémenter)
    function updateFreelancersList(freelancers) {
        // Code pour mettre à jour l'interface avec les freelancers reçus
        const freelancersContainer = document.querySelector('.freelancers-container');
        freelancersContainer.innerHTML = '';
        
        freelancers.forEach(freelancer => {
            const freelancerCard = document.createElement('div');
            freelancerCard.classList.add('freelancer-card');
            
            // Remplir la carte du freelancer avec les données de l'objet 'freelancer'
            // ...
            
            freelancersContainer.appendChild(freelancerCard);
        });
    }
});