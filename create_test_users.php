<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuration de la base de données
$host = 'localhost';
$username = 'root';
$password = '';
$dbname = 'freelanceconnect';

try {
    // Connexion à la base de données
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Création des utilisateurs de test</h2>";
    
    // Vérifier si les utilisateurs existent déjà
    $check = $conn->query("SELECT COUNT(*) FROM users WHERE email IN ('freelancer@test.com', 'company@test.com')");
    $count = $check->fetchColumn();
    
    if ($count > 0) {
        echo "Des utilisateurs de test existent déjà. Suppression...<br>";
        $conn->exec("DELETE FROM users WHERE email IN ('freelancer@test.com', 'company@test.com')");
    }
    
    // Créer un utilisateur freelancer
    $name_freelancer = "Jean Dupont";
    $email_freelancer = "freelancer@test.com";
    $password_freelancer = password_hash("password123", PASSWORD_DEFAULT);
    $account_type_freelancer = "freelancer";
    
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, account_type, created_at) 
                        VALUES (:name, :email, :password, :account_type, NOW())");
    $stmt->bindParam(':name', $name_freelancer);
    $stmt->bindParam(':email', $email_freelancer);
    $stmt->bindParam(':password', $password_freelancer);
    $stmt->bindParam(':account_type', $account_type_freelancer);
    $stmt->execute();
    
    $freelancer_user_id = $conn->lastInsertId();
    
    // Ajouter les détails du freelancer
    $skills = "PHP, JavaScript, HTML, CSS";
    $experience = 5;
    
    $stmt = $conn->prepare("INSERT INTO freelancers (user_id, skills, experience) 
                           VALUES (:user_id, :skills, :experience)");
    $stmt->bindParam(':user_id', $freelancer_user_id);
    $stmt->bindParam(':skills', $skills);
    $stmt->bindParam(':experience', $experience);
    $stmt->execute();
    
    echo "Utilisateur freelancer créé avec succès:<br>";
    echo "- Email: freelancer@test.com<br>";
    echo "- Mot de passe: password123<br><br>";
    
    // Créer un utilisateur entreprise
    $name_company = "Entreprise ABC";
    $email_company = "company@test.com";
    $password_company = password_hash("password123", PASSWORD_DEFAULT);
    $account_type_company = "company";
    
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, account_type, created_at) 
                           VALUES (:name, :email, :password, :account_type, NOW())");
    $stmt->bindParam(':name', $name_company);
    $stmt->bindParam(':email', $email_company);
    $stmt->bindParam(':password', $password_company);
    $stmt->bindParam(':account_type', $account_type_company);
    $stmt->execute();
    
    $company_user_id = $conn->lastInsertId();
    
    // Ajouter les détails de l'entreprise
    $company_name = "Entreprise ABC";
    $industry = "Technologie";
    
    $stmt = $conn->prepare("INSERT INTO companies (user_id, company_name, industry) 
                        VALUES (:user_id, :company_name, :industry)");
    $stmt->bindParam(':user_id', $company_user_id);
    $stmt->bindParam(':company_name', $company_name);
    $stmt->bindParam(':industry', $industry);
    $stmt->execute();
    
    echo "Utilisateur entreprise créé avec succès:<br>";
    echo "- Email: company@test.com<br>";
    echo "- Mot de passe: password123<br><br>";
    
    echo "<h2>Utilisateurs de test créés avec succès</h2>";
    echo "<p>Vous pouvez maintenant vous connecter avec ces identifiants.</p>";
    
} catch(PDOException $e) {
    echo "<h3>Erreur : " . $e->getMessage() . "</h3>";
}
?>
