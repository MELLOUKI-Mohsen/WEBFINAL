<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    // Rediriger vers la page de connexion
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - FreelanceConnect</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <h1><a href="index.html">FreelanceConnect</a></h1>
            </div>
            <ul class="nav-links">
                <li><a href="index.html">Accueil</a></li>
                <li><a href="projects.html">Projets</a></li>
                <li><a href="freelancers.html">Freelancers</a></li>
                <li><a href="companies.html">Entreprises</a></li>
                <li><a href="about.html">À propos</a></li>
            </ul>
            <div class="user-menu">
                <span><i class="fas fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['user_name']); ?></span>
                <a href="php/logout.php"><i class="fas fa-sign-out-alt"></i> Déconnexion</a>
            </div>
        </nav>
    </header>

    <section class="dashboard-container">
        <h2>Tableau de bord</h2>
        
        <div class="dashboard-stats">
            <?php if ($_SESSION['account_type'] == 'freelancer'): ?>
                <div class="stat-card">
                    <i class="fas fa-briefcase"></i>
                    <h4>0</h4>
                    <p>Projets en cours</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-paper-plane"></i>
                    <h4>0</h4>
                    <p>Candidatures envoyées</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-check-circle"></i>
                    <h4>0</h4>
                    <p>Projets terminés</p>
                </div>
            <?php else: ?>
                <div class="stat-card">
                    <i class="fas fa-clipboard-list"></i>
                    <h4>0</h4>
                    <p>Projets publiés</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-users"></i>
                    <h4>0</h4>
                    <p>Candidatures reçues</p>
                </div>
                <div class="stat-card">
                    <i class="fas fa-check-circle"></i>
                    <h4>0</h4>
                    <p>Projets terminés</p>
                </div>
            <?php endif; ?>
        </div>
        
        <?php if ($_SESSION['account_type'] == 'freelancer'): ?>
            <div class="dashboard-section">
                <h3><i class="fas fa-search"></i> Projets disponibles</h3>
                <div class="empty-state">
                    <i class="fas fa-clipboard-list"></i>
                    <p>Aucun projet disponible pour le moment.</p>
                    <a href="projects.html" class="btn primary">Parcourir les projets</a>
                </div>
            </div>
            <div class="dashboard-section">
                <h3><i class="fas fa-paper-plane"></i> Mes candidatures</h3>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Vous n'avez pas encore postulé à des projets.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="dashboard-section">
                <h3><i class="fas fa-clipboard-list"></i> Mes projets</h3>
                <div class="empty-state">
                    <i class="fas fa-folder-open"></i>
                    <p>Vous n'avez pas encore créé de projets.</p>
                    <a href="create-project.php" class="btn primary">Créer un projet</a>
                </div>
            </div>
            <div class="dashboard-section">
                <h3><i class="fas fa-users"></i> Candidatures reçues</h3>
                <div class="empty-state">
                    <i class="fas fa-inbox"></i>
                    <p>Vous n'avez pas encore reçu de candidatures.</p>
                </div>
            </div>
        <?php endif; ?>
    </section>

    <footer>
        <div class="footer-content">
            <div class="footer-section">
                <h3>FreelanceConnect</h3>
                <p>La plateforme qui connecte les freelances et les entreprises.</p>
            </div>
            <div class="footer-section">
                <h3>Liens rapides</h3>
                <ul>
                    <li><a href="index.html">Accueil</a></li>
                    <li><a href="projects.html">Projets</a></li>
                    <li><a href="freelancers.html">Freelancers</a></li>
                    <li><a href="companies.html">Entreprises</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contact</h3>
                <p>Email: contact@freelanceconnect.com</p>
                <p>Téléphone: +33 1 23 45 67 89</p>
            </div>
        </div>
        <div class="footer-bottom">
            <p>&copy; 2025 FreelanceConnect. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html> 


