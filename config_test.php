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
    // Définir le jeu de caractères à utf8
    $conn->exec("SET NAMES utf8");
    
    echo "<h1>Connexion à la base de données réussie !</h1>";
    
    // Vérifier si les tables existent
    $tables = ['users', 'freelancers', 'companies', 'projects', 'applications', 'remember_tokens', 'messages', 'reviews'];
    echo "<h2>Vérification des tables :</h2>";
    echo "<ul>";
    
    foreach ($tables as $table) {
        $stmt = $conn->query("SHOW TABLES LIKE '$table'");
        if ($stmt->rowCount() > 0) {
            echo "<li>Table '$table' : <span style='color:green'>Existe</span></li>";
        } else {
            echo "<li>Table '$table' : <span style='color:red'>N'existe pas</span></li>";
        }
    }
    
    echo "</ul>";
    
} catch(PDOException $e) {
    die("<h1>Erreur de connexion à la base de données:</h1> <p>" . $e->getMessage() . "</p>");
}
?>
