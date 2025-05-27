<?php
// Activer l'affichage des erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Configuration de la base de données
$host = 'localhost';
$username = 'root';
$password = '';

try {
    // Connexion sans sélectionner de base de données
    $conn = new PDO("mysql:host=$host", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Créer la base de données si elle n'existe pas
    $conn->exec("CREATE DATABASE IF NOT EXISTS freelanceconnect");
    // ...
}

