<?php

// Activer l'affichage des erreurs
ini_set("display_errors", 1);
ini_set("display_startup_errors", 1);
error_reporting(E_ALL);

// Configuration de la base de données
$host = "localhost";
$username = "root";
$password = "";
$dbname = "freelanceconnect";

try {
    // Connexion à la base de données
    $conn = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "<h2>Création des tables manquantes</h2>";
    
    // Créer la table projects
    $sql_projects = "
    CREATE TABLE IF NOT EXISTS projects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(100) NOT NULL,
        description TEXT,
        budget DECIMAL(10, 2) DEFAULT NULL,
        company_id INT NOT NULL,
        status ENUM('open', 'in_progress', 'completed') DEFAULT 'open',
        created_at DATETIME NOT NULL,
        updated_at DATETIME DEFAULT NULL,
        FOREIGN KEY (company_id) REFERENCES companies(id) ON DELETE CASCADE
    )";
    
    $conn->exec($sql_projects);
    echo "Table 'projects' créée avec succès<br>";
    
    // Créer la table applications
    $sql_applications = "
    CREATE TABLE IF NOT EXISTS applications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        project_id INT NOT NULL,
        freelancer_id INT NOT NULL,
        message TEXT,
        bid_amount DECIMAL(10, 2) DEFAULT NULL,
        status ENUM('pending', 'accepted', 'rejected') DEFAULT 'pending',
        created_at DATETIME NOT NULL,
        FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
        FOREIGN KEY (freelancer_id) REFERENCES freelancers(id) ON DELETE CASCADE
    )";
    
    $conn->exec($sql_applications);
    echo "Table 'applications' créée avec succès<br>";
    
    // Créer la table messages
    $sql_messages = "
    CREATE TABLE IF NOT EXISTS messages (
        id INT AUTO_INCREMENT PRIMARY KEY,
        sender_id INT NOT NULL,
        recipient_id INT NOT NULL,
        content TEXT NOT NULL,
        is_read BOOLEAN DEFAULT FALSE,
        created_at DATETIME NOT NULL,
        FOREIGN KEY (sender_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (recipient_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    
    $conn->exec($sql_messages);
    echo "Table 'messages' créée avec succès<br>";
    
    // Créer la table reviews
    $sql_reviews = "
    CREATE TABLE IF NOT EXISTS reviews (
        id INT AUTO_INCREMENT PRIMARY KEY,
        project_id INT NOT NULL,
        reviewer_id INT NOT NULL,
        reviewee_id INT NOT NULL,
        rating INT NOT NULL CHECK (rating BETWEEN 1 AND 5),
        comment TEXT,
        created_at DATETIME NOT NULL,
        FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
        FOREIGN KEY (reviewer_id) REFERENCES users(id) ON DELETE CASCADE,
        FOREIGN KEY (reviewee_id) REFERENCES users(id) ON DELETE CASCADE
    )";
    
    $conn->exec($sql_reviews);
    echo "Table 'reviews' créée avec succès<br>";
    
    echo "<h2>Toutes les tables manquantes ont été créées</h2>";
    
} catch (PDOException $e) {
    echo "<h3>Erreur : " . $e->getMessage() . "</h3>";
}
