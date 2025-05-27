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
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Créer la base de données si elle n'existe pas
    $conn->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    echo "<h2>Base de données '$dbname' créée ou déjà existante</h2>";
    
    // Sélectionner la base de données
    $conn->exec("USE $dbname");
    
    echo "<h2>Création des tables utilisateurs</h2>";
    
    // Créer la table users
    $sql_users = "
    CREATE TABLE IF NOT EXISTS users (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(100) NOT NULL,
        email VARCHAR(100) NOT NULL UNIQUE,
        password VARCHAR(255) NOT NULL,
        account_type ENUM('freelancer', 'company') NOT NULL,
        created_at DATETIME NOT NULL,
        updated_at DATETIME DEFAULT NULL
    )";
    
    $conn->exec($sql_users);
    echo "Table 'users' créée avec succès<br>";
    
    // Créer la table freelancers
    $sql_freelancers = "
    CREATE TABLE IF NOT EXISTS freelancers (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        skills TEXT,
        experience INT DEFAULT 0,
        hourly_rate DECIMAL(10, 2) DEFAULT NULL,
        bio TEXT,
        portfolio_url VARCHAR(255) DEFAULT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    
    $conn->exec($sql_freelancers);
    echo "Table 'freelancers' créée avec succès<br>";
    
    // Créer la table companies
    $sql_companies = "
    CREATE TABLE IF NOT EXISTS companies (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        company_name VARCHAR(100) NOT NULL,
        industry VARCHAR(100),
        description TEXT,
        website VARCHAR(255) DEFAULT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    
    $conn->exec($sql_companies);
    echo "Table 'companies' créée avec succès<br>";
    
    // Créer la table remember_tokens
    $sql_tokens = "
    CREATE TABLE IF NOT EXISTS remember_tokens (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_id INT NOT NULL,
        token VARCHAR(255) NOT NULL,
        expires_at DATETIME NOT NULL,
        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    
    $conn->exec($sql_tokens);
    echo "Table 'remember_tokens' créée avec succès<br>";
    
    echo "<h2>Toutes les tables utilisateurs ont été créées</h2>";
    
} catch(PDOException $e) {
    echo "<h3>Erreur : " . $e->getMessage() . "</h3>";
}
?>
