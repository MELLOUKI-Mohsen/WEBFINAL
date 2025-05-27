<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuration de la base de données
$host = "localhost";
$dbname = "freelanceconnect";
$username = "root";
$password = "";

try {
    // Création de la connexion PDO
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    // Configurer PDO pour qu'il lance des exceptions en cas d'erreur
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h1>Connexion à la base de données réussie !</h1>";
    
    // Vérifier si la table users existe
    $stmt = $conn->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "<p>La table 'users' existe.</p>";
        
        // Compter le nombre d'utilisateurs
        $stmt = $conn->query("SELECT COUNT(*) as count FROM users");
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<p>Nombre d'utilisateurs dans la base de données : " . $result['count'] . "</p>";
    } else {
        echo "<p>La table 'users' n'existe pas. Veuillez exécuter le script SQL pour créer les tables.</p>";
    }
} catch(PDOException $e) {
    echo "<h1>Erreur de connexion à la base de données</h1>";
    echo "<p>" . $e->getMessage() . "</p>";
}
?>

