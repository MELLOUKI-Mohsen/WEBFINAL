// auth-manager.js - Manages authentication state across pages

document.addEventListener('DOMContentLoaded', function() {
    // Check if user is logged in
    checkAuthStatus();
    
    // Add logout functionality to any logout buttons
    setupLogoutButtons();
});

// Function to check authentication status
function checkAuthStatus() {
    // Check if user is logged in via localStorage
    const isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
    const userName = localStorage.getItem('userName');
    const userType = localStorage.getItem('userType');
    const userId = localStorage.getItem('userId');
    
    if (isLoggedIn && userName && userType && userId) {
        // User is logged in, update the UI
        updateUIForLoggedInUser(userName, userType);
    } else {
        // If on a protected page, redirect to login
        const currentPage = window.location.pathname.split('/').pop();
        if (currentPage === 'dashboard.html' || 
            currentPage === 'create-project.html' || 
            currentPage === 'my-projects.html' || 
            currentPage === 'profile.html') {
            window.location.href = 'login.html?redirect=' + currentPage;
        }
    }
}

// Update UI for logged in users
function updateUIForLoggedInUser(userName, userType) {
    const authButtons = document.querySelector('.auth-buttons');
    if (authButtons) {
        authButtons.innerHTML = `
            <a href="dashboard.html" class="btn login">Tableau de bord</a>
            <a href="#" id="logout-btn" class="btn register">Déconnexion</a>
        `;
    }
    
    // Add user menu if it doesn't exist
    if (!document.querySelector('.user-menu') && authButtons) {
        const userMenu = document.createElement('div');
        userMenu.className = 'user-menu';
        userMenu.innerHTML = `
            <span><i class="fas fa-user-circle"></i> ${userName}</span>
            <a href="#" id="logout-btn"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
        `;
        authButtons.parentNode.replaceChild(userMenu, authButtons);
    }
    
    // Setup logout functionality
    setupLogoutButtons();
}

// Setup logout buttons
function setupLogoutButtons() {
    const logoutButtons = document.querySelectorAll('#logout-btn');
    logoutButtons.forEach(button => {
        button.addEventListener('click', function(e) {
            e.preventDefault();
            logout();
        });
    });
}

// Logout function
function logout() {
    // Clear authentication data from localStorage
    localStorage.removeItem('isLoggedIn');
    localStorage.removeItem('userName');
    localStorage.removeItem('userType');
    localStorage.removeItem('userId');
    
    // Also make a request to the server to end the PHP session
    fetch('php/logout.php')
        .then(response => {
            // Redirect to home page
            window.location.href = 'index.html';
        })
        .catch(error => {
            console.error('Logout error:', error);
            // Still redirect even if there's an error
            window.location.href = 'index.html';
        });
}
