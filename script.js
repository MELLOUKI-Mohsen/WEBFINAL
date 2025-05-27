// Navigation mobile
const burger = document.querySelector('.burger');
const nav = document.querySelector('.nav-links');
const authButtons = document.querySelector('.auth-buttons');

if (burger) {
    burger.addEventListener('click', () => {
        // Toggle navigation
        nav.classList.toggle('nav-active');
        authButtons.classList.toggle('nav-active');
        
        // Burger animation
        burger.classList.toggle('toggle');
    });
}

// Animation au défilement
document.addEventListener('DOMContentLoaded', () => {
    const cards = document.querySelectorAll('.card');
    
    // Fonction pour vérifier si un élément est visible
    const isElementInViewport = (el) => {
        const rect = el.getBoundingClientRect();
        return (
            rect.top >= 0 &&
            rect.left >= 0 &&
            rect.bottom <= (window.innerHeight || document.documentElement.clientHeight) &&
            rect.right <= (window.innerWidth || document.documentElement.clientWidth)
        );
    };
    
    // Fonction pour animer les éléments visibles
    const animateOnScroll = () => {
        cards.forEach(card => {
            if (isElementInViewport(card)) {
                card.classList.add('animate');
            }
        });
    };
    
    // Exécuter au chargement et au défilement
    animateOnScroll();
    window.addEventListener('scroll', animateOnScroll);
});

// Vérifier si l'utilisateur est connecté
const checkAuthStatus = () => {
    // Simuler une vérification d'authentification (à remplacer par une vraie vérification)
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
    
    if (isLoggedIn) {
        // Modifier l'interface pour les utilisateurs connectés
        const authButtons = document.querySelector('.auth-buttons');
        if (authButtons) {
            authButtons.innerHTML = `
                <a href="dashboard.html" class="btn login">Tableau de bord</a>
                <a href="#" id="logout-btn" class="btn register">Déconnexion</a>
            `;
            
            // Ajouter l'événement de déconnexion
            document.getElementById('logout-btn').addEventListener('click', (e) => {
                e.preventDefault();
                localStorage.removeItem('isLoggedIn');
                localStorage.removeItem('userType');
                localStorage.removeItem('userId');
                window.location.href = 'index.html';
            });
        }
    }
};
// Exécuter la vérification d'authentification
checkAuthStatus(); 
