<?php
// Démarrer la session
session_start();

// Inclure le fichier de configuration
require_once 'config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']) ? true : false;
    
    // Validation de base
    $errors = [];
    
    if (empty($email)) {
        $errors[] = "L'email est requis.";
    }
    
    if (empty($password)) {
        $errors[] = "Le mot de passe est requis.";
    }
    
    // Si aucune erreur, procéder à la connexion
    if (empty($errors)) {
        try {
            // Vérifier si l'utilisateur existe
            $stmt = $conn->prepare("SELECT id, name, email, password, account_type FROM users WHERE email = :email");
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            
            if ($stmt->rowCount() > 0) {
                $user = $stmt->fetch(PDO::FETCH_ASSOC);
                
                // Vérifier le mot de passe
                if (password_verify($password, $user['password'])) {
                    // Connexion réussie
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_name'] = $user['name'];
                    $_SESSION['account_type'] = $user['account_type'];
                    
                    // Si "Se souvenir de moi" est coché
                    if ($remember) {
                        $token = bin2hex(random_bytes(32));
                        $expiry = time() + (86400 * 30); // 30 jours
                        
                        // Stocker le token dans la base de données
                        $stmt = $conn->prepare("INSERT INTO remember_tokens (user_id, token, expires_at) VALUES (:user_id, :token, FROM_UNIXTIME(:expiry))");
                        $stmt->bindParam(':user_id', $user['id']);
                        $stmt->bindParam(':token', $token);
                        $stmt->bindParam(':expiry', $expiry);
                        $stmt->execute();
                        
                        // Créer un cookie
                        setcookie('remember_token', $token, $expiry, "/", "", false, true);
                    }
                    
                    // Retourner les données pour localStorage
                    echo json_encode([
                        'success' => true,
                        'userId' => $user['id'],
                        'userName' => $user['name'],
                        'userType' => $user['account_type'],
                        'redirect' => '../dashboard.html'
                    ]);
                    exit();
                } else {
                    $errors[] = "Mot de passe incorrect.";
                }
            } else {
                $errors[] = "Aucun compte trouvé avec cette adresse email.";
            }
        } catch(PDOException $e) {
            $errors[] = "Erreur de connexion: " . $e->getMessage();
        }
    }
    
    // S'il y a des erreurs, les retourner
    if (!empty($errors)) {
        echo json_encode([
            'success' => false,
            'errors' => $errors
        ]);
        exit();
    }
}
?>

