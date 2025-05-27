<?php
// Démarrer la session
session_start();

// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Inclure le fichier de configuration
require_once 'config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $account_type = $_POST['account_type'] ?? '';
    
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
    
    // Si aucune erreur, procéder à l'inscription
    if (empty($errors)) {
        try {
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
            
            // Traiter les informations spécifiques selon le type de compte
            if ($account_type === 'freelancer') {
                $skills = isset($_POST['skills']) ? $_POST['skills'] : '';
                $experience = isset($_POST['experience']) ? intval($_POST['experience']) : 0;
                
                $stmt = $conn->prepare("INSERT INTO freelancers (user_id, skills, experience) VALUES (:user_id, :skills, :experience)");
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':skills', $skills);
                $stmt->bindParam(':experience', $experience);
                $stmt->execute();
            } else if ($account_type === 'company') {
                $company_name = isset($_POST['company_name']) ? $_POST['company_name'] : $name;
                $industry = isset($_POST['industry']) ? $_POST['industry'] : '';
                
                $stmt = $conn->prepare("INSERT INTO companies (user_id, company_name, industry) VALUES (:user_id, :company_name, :industry)");
                $stmt->bindParam(':user_id', $user_id);
                $stmt->bindParam(':company_name', $company_name);
                $stmt->bindParam(':industry', $industry);
                $stmt->execute();
            }
            
            // Connecter l'utilisateur
            $_SESSION['user_id'] = $user_id;
            $_SESSION['user_name'] = $name;
            $_SESSION['account_type'] = $account_type;

            // Rediriger vers le tableau de bord
            header("Location: ../dashboard.html");
            exit();
        } catch(PDOException $e) {
            $errors[] = "Erreur d'inscription: " . $e->getMessage();
        }
    }
    
    // S'il y a des erreurs, les stocker dans la session
    if (!empty($errors)) {
        $_SESSION['register_errors'] = $errors;
        $_SESSION['form_data'] = $_POST; // Pour repopuler le formulaire
        header("Location: ../register.html?error=1");
        exit();
    }
}
?>
