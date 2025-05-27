document.addEventListener('DOMContentLoaded'), () => {
    // Gestion de la recherche de projets
    const searchInput = document.getElementById('project-search');
    const searchButton = searchInput?.nextElementSibling;
    
    if (searchButton) {
        searchButton.addEventListener('click', () => {
            searchProjects();
        });
    }
    
    if (searchInput) {
        searchInput.addEventListener('keypress', (e) => {
            if (e.key === 'Enter') {
                searchProjects();
            }
        });
    }
    
    // Gestion des filtres
    const categoryFilter = document.getElementById('category-filter');
    const budgetFilter = document.getElementById('budget-filter');
    
    if (categoryFilter) {
        categoryFilter.addEventListener('change', () => {
            filterProjects();
        });
    }
    
    if (budgetFilter) {
        budgetFilter.addEventListener('change', () => {
            filterProjects();
        });
    }
}
    // Fonction de recherche (simulation)
    function searchProjects() {
        const searchTerm = searchInput.value.toLowerCase();
        console.log(`Recherche de projets avec le terme: ${searchTerm}`);
        
        // Ici, vous feriez normalement une requête AJAX vers le serveur
        // Pour l'instant, nous simulons juste un message dans la console
        
        // Exemple de code pour une requête AJAX:
        fetch(`php/get_projects.php?search=${encodeURIComponent(searchTerm)}`)
            .then(response => response.json())
            .then(data => {
                // Mettre à jour l'interface avec les résultats
                updateProjectsList(data);
            })
            .catch(error => {
                console.error('Erreur lors de la recherche:', error);
            });
    }
    
    // Fonction de filtrage (simulation)
    function filterProjects() {
        const category = categoryFilter.value;
        const budget = budgetFilter.value;
        
        console.log(`Filtrage des projets - Catégorie: ${category}, Budget: ${budget}`);
        
        // Ici, vous feriez normalement une requête AJAX vers le serveur
        // Pour l'instant, nous simulons juste un message dans la console
        
        // Exemple de code pour une requête AJAX:
        fetch(`php/get_projects.php?category=${encodeURIComponent(category)}&budget=${encodeURIComponent(budget)}`)
            .then(response => response.json())
            .then(data => {
                // Mettre à jour l'interface avec les résultats
                updateProjectsList(data);
            })
            .catch(error => {
                console.error('Erreur lors du filtrage:', error);
            });
    }
    
    // Fonction pour mettre à jour la liste des projets (à implémenter)
    function updateProjectsList(projects) {
        // Code pour mettre à jour l'interface avec les projets reçus
        const projectsContainer = document.querySelector('.projects-container');
        projectsContainer.innerHTML = '';
        
        projects.forEach(project => {
            const projectCard = document.createElement('div');
            projectCard.classList.add('project-card');
            
            // Remplir la carte du projet avec les données de l'objet 'project'
            // ...
            
            projectsContainer.appendChild(projectCard);
        });
    }
;