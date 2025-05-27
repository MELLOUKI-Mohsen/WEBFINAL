<?php
// Inclure le fichier de configuration
require_once 'config.php';

// Paramètres de filtrage
$category = isset($_GET['category']) ? $_GET['category'] : '';
$budget = isset($_GET['budget']) ? $_GET['budget'] : '';
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Construire la requête SQL de base
$sql = "SELECT p.*, u.name as company_name FROM projects p 
        JOIN users u ON p.user_id = u.id 
        WHERE 1=1";

// Ajouter les filtres si nécessaire
if (!empty($category)) {
    $sql .= " AND p.category = :category";
}

if (!empty($budget)) {
    switch ($budget) {
        case 'low':
            $sql .= " AND p.budget_max < 500";
            break;
        case 'medium':
            $sql .= " AND p.budget_min >= 500 AND p.budget_max <= 2000";
            break;
        case 'high':
            $sql .= " AND p.budget_min >= 2000 AND p.budget_max <= 5000";
            break;
        case 'very-high':
            $sql .= " AND p.budget_min > 5000";
            break;
    }
}

if (!empty($search)) {
    $sql .= " AND (p.title LIKE :search OR p.description LIKE :search OR p.skills LIKE :search)";
}

// Ajouter l'ordre
$sql .= " ORDER BY p.created_at DESC";

try {
    $stmt = $conn->prepare($sql);
    
    // Lier les paramètres si nécessaire
    if (!empty($category)) {
        $stmt->bindParam(':category', $category);
    }
    
    if (!empty($search)) {
        $searchParam = "%$search%";
        $stmt->bindParam(':search', $searchParam);
    }
    
    $stmt->execute();
    $projects = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Retourner les projets au format JSON
    header('Content-Type: application/json');
    echo json_encode($projects);
} catch(PDOException $e) {
    // En cas d'erreur, retourner un message d'erreur
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}
?>