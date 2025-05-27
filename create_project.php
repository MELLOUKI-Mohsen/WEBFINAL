<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.html");
    exit();
}

// Vérifier si l'utilisateur est une entreprise
if ($_SESSION['account_type'] !== 'company') {
    header("Location: ../dashboard.html");
    exit();
}

// Inclure le fichier de configuration
require_once 'config.php';

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $category = $_POST['category'];
    $budget_min = $_POST['budget_min'];
    $budget_max = $_POST['budget_max'];
    $duration = $_POST['duration'];
    $location = $_POST['location'];
    $skills = isset($_POST['skills']) ? $_POST['skills'] : '';
    
    // Validation de base
    $errors = [];
    
    if (empty($title)) {
        $errors[] = "Le titre du projet est requis.";
    }
    
    if (empty($description)) {
        $errors[] = "La description du projet est requise.";
    }
    
    // Si aucune erreur, procéder à la création du projet
    if (empty($errors)) {
        try {
            // Insérer le projet dans la base de données
            $stmt = $conn->prepare("INSERT INTO projects (user_id, title, description, category, budget_min, budget_max, duration, location, skills, created_at) VALUES (:user_id, :title, :description, :category, :budget_min, :budget_max, :duration, :location, :skills, NOW())");
            $stmt->bindParam(':user_id', $_SESSION['user_id']);
            $stmt->bindParam(':title', $title);
            $stmt->bindParam(':description', $description);
            $stmt->bindParam(':category', $category);
            $stmt->bindParam(':budget_min', $budget_min);
            $stmt->bindParam(':budget_max', $budget_max);
            $stmt->bindParam(':duration', $duration);
            $stmt->bindParam(':location', $location);
            $stmt->bindParam(':skills', $skills);
            $stmt->execute();
            
            // Rediriger vers la page des projets
            header("Location: ../my-projects.html");
            exit();
        } catch(PDOException $e) {
            $errors[] = "Erreur de création du projet: " . $e->getMessage();
        }
    }
    
    // S'il y a des erreurs, les stocker dans la session
    if (!empty($errors)) {
        $_SESSION['project_errors'] = $errors;
        $_SESSION['form_data'] = $_POST; // Pour repopuler le formulaire
        header("Location: ../create-project.html");
        exit();
    }
}
?>