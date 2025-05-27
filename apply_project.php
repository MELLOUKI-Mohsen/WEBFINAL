<?php
// Démarrer la session
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Vous devez être connecté pour postuler.']);
    exit();
}

// Vérifier si l'utilisateur est un freelancer
if ($_SESSION['account_type'] !== 'freelancer') {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Seuls les freelancers peuvent postuler aux projets.']);
    exit();
}

// Inclure le fichier de configuration
require_once 'config.php';

// Vérifier si la requête est de type POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données
    $project_id = $_POST['project_id'];
    $message = trim($_POST['message']);
    $bid_amount = $_POST['bid_amount'];
    
    // Validation de base
    if (empty($project_id) || empty($message) || empty($bid_amount)) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Tous les champs sont requis.']);
        exit();
    }
    
    try {
        // Vérifier si l'utilisateur a déjà postulé à ce projet
        $stmt = $conn->prepare("SELECT id FROM applications WHERE user_id = :user_id AND project_id = :project_id");
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':project_id', $project_id);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'Vous avez déjà postulé à ce projet.']);
            exit();
        }
        
        // Insérer la candidature dans la base de données
        $stmt = $conn->prepare("INSERT INTO applications (user_id, project_id, message, bid_amount, created_at) VALUES (:user_id, :project_id, :message, :bid_amount, NOW())");
        $stmt->bindParam(':user_id', $_SESSION['user_id']);
        $stmt->bindParam(':project_id', $project_id);
        $stmt->bindParam(':message', $message);
        $stmt->bindParam(':bid_amount', $bid_amount);
        $stmt->execute();
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Votre candidature a été envoyée avec succès.']);
    } catch(PDOException $e) {
        header('Content-Type: application/json');
        echo json_encode(['success' => false, 'message' => 'Erreur: ' . $e->getMessage()]);
    }
}
?>