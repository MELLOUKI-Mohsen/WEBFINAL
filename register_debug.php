<?php
// Démarrer la session
session_start();

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de configuration
require_once 'config.php';

echo "<h1>Débogage de l'inscription</h1>";

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<h2>Données du formulaire reçues :</h2>";
    echo "<pre>";
    print_r($_POST);
    echo "</pre>";
    
    // Récupérer les données du formulaire
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $account_type = $_POST['account_type'] ?? '';
    
    echo "<h2>Données traitées :</h2>";
    echo "Nom : " . htmlspecialchars($name) . "<br>";
    echo "Email : " . htmlspecialchars($email) . "<br>";
    echo "Mot de passe : " . (empty($password) ? "Non fourni" : "Fourni") . "<br>";
    echo "Confirmation du mot de passe : " . (empty($confirm_password) ? "Non fourni" : "Fourni") . "<br>";
    echo "Type de compte : " . htmlspecialchars($account_type) . "<br>";
    
    // Validation de base
    $errors = [];
    
    if (empty($name)) {
        $errors[] = "Le nom est requis.";
    }
    
    if (empty($email)) {
        $errors[] = "L'email est requis.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'adresse email n'est pas valide.";
    }
    
    if (empty($password)) {
        $errors[] = "Le mot de passe est requis.";
    } elseif (strlen($password) < 6) {
        $errors[] = "Le mot de passe doit contenir au moins 6 caractères.";
    }
    
    if ($password !== $confirm_password) {
        $errors[] = "Les mots de passe ne correspondent pas.";
    }
    
    if (empty($account_type)) {
        $errors[] = "Le type de compte est requis.";
    }
    
    // Vérifier si l'email existe déjà
    try {
        $stmt = $conn->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            $errors[] = "Cette adresse email est déjà utilisée.";
        }
    } catch(PDOException $e) {
        $errors[] = "Erreur de base de données: " . $e->getMessage();
    }
    
    echo "<h2>Erreurs de validation :</h2>";
    if (empty($errors)) {
        echo "Aucune erreur de validation.<br>";
    } else {
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
    }
    
    // Si aucune erreur, procéder à l'inscription
    if (empty($errors)) {
        try {
            echo "<h2>Tentative d'inscription :</h2>";
            
            // Hacher le mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insérer l'utilisateur dans la base de données
            $stmt = $conn->prepare("INSERT INTO users (name, email, password, account_type, created_at) VALUES (:name, :email, :password, :account_type, NOW())");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':password', $hashed_password);
            $stmt->bindParam(':account_type', $account_type);
            $stmt->execute();
            
            $user_id = $conn->lastInsertId();
            echo "Utilisateur créé avec l'ID : " . $user_id . "<br>";
            
            // Traiter les informations spécifiques selon le type de compte
            if ($account_type === 'freelancer') {
                $skills = isset($_POST['skills']) ? $_POST['skills'] : '';
                $experience = isset($_POST['experience']) ? $_POST['experience'] : 0;
                
                echo "Création du profil freelancer...<br>";
                $stmt = $conn->prepare("INSERT INTO freelancers (user_id, skills, experience) VALUES (:user_id, :skills, :experience)");
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':skills', $skills);
                $stmt->bindParam(':experience', $experience);
                $stmt->execute();
                echo "Profil freelancer créé.<br>";
            } else if ($account_type === 'company') {
                $company_name = isset($_POST['company_name']) ? $_POST['company_name'] : '';
                $industry = isset($_POST['industry']) ? $_POST['industry'] : '';
                
                echo "Création du profil entreprise...<br>";
                $stmt = $conn->prepare("INSERT INTO companies (user_id, company_name, industry) VALUES (:user_id, :company_name, :industry)");
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':company_name', $company_name);
                $stmt->bindParam(':industry', $industry);
                $stmt->execute();
                echo "Profil entreprise créé.<br>";
            }
            
            echo "Inscription réussie !<br>";
            echo "Redirection vers le tableau de bord...<br>";
            
            // Connecter l'utilisateur
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['account_type'] = $account_type;
            
            // Ne pas rediriger pour voir les résultats
            // header("Location: ../dashboard.php");
            // exit();
        } catch(PDOException $e) {
            echo "<h2>Erreur lors de l'inscription :</h2>";
            echo htmlspecialchars($e->getMessage()) . "<br>";
            echo "Code : " . $e->getCode() . "<br>";
            $errors[] = "Erreur d'inscription: " . $e->getMessage();
        }
    }
    
    // S'il y a des erreurs, les afficher
    if (!empty($errors)) {
        echo "<h2>Erreurs finales :</h2>";
        echo "<ul>";
        foreach ($errors as $error) {
            echo "<li>" . htmlspecialchars($error) . "</li>";
        }
        echo "</ul>";
        
        $_SESSION['register_errors'] = $errors;
        $_SESSION['form_data'] = $_POST; // Pour repopuler le formulaire
        
        echo "<p>Les erreurs ont été stockées dans la session.</p>";
        // Ne pas rediriger pour voir les résultats
        // header("Location: ../register.php");
        // exit();
    }
}
?>

