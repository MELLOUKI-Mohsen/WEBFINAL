<?php
// Démarrer la session
session_start();

// Récupérer les erreurs s'il y en a
$errors = $_SESSION['login_errors'] ?? [];
$email = $_SESSION['login_email'] ?? '';

// Effacer les erreurs et l'email de la session
unset($_SESSION['login_errors']);
unset($_SESSION['login_email']);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - FreelanceConnect</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/auth.css">
</head>
<body>
    <header>
        <nav>
            <div class="logo">
                <h1><a href="index.html">FreelanceConnect</a></h1>
            </div>
        </nav>
    </header>

    <section class="auth-container">
        <div class="auth-form">
            <h2>Connexion</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="error-messages">
                    <?php foreach ($errors as $error): ?>
                        <p><?php echo htmlspecialchars($error); ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <form id="login-form" action="php/login.php" method="POST">
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>
                </div>
                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div class="form-group remember">
                    <input type="checkbox" id="remember" name="remember">
                    <label for="remember">Se souvenir de moi</label>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn primary full-width">Se connecter</button>
                </div>
                <div class="form-links">
                    <a href="forgot-password.html">Mot de passe oublié?</a>
                    <a href="register.php">Pas encore inscrit? Créer un compte</a>
                </div>
            </form>
        </div>
    </section>

    <footer>
        <div class="footer-bottom">
            <p>&copy; 2025 FreelanceConnect. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
