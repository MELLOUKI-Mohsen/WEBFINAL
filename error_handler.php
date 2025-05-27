<?php
// Démarrer la session si elle n'est pas déjà démarrée
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Fonction pour stocker les erreurs dans la session et dans sessionStorage
function store_errors($errors, $form_data = [], $redirect_url = '') {
    $_SESSION['register_errors'] = $errors;
    $_SESSION['form_data'] = $form_data;
    
    // Créer un script pour stocker les erreurs dans sessionStorage
    echo '<script>';
    echo 'sessionStorage.setItem("register_errors", ' . json_encode(json_encode($errors)) . ');';
    echo 'sessionStorage.setItem("form_data", ' . json_encode(json_encode($form_data)) . ');';
    if (!empty($redirect_url)) {
        echo 'window.location.href = "' . $redirect_url . '";';
    }
    echo '</script>';
    
    // Si pas de redirection, afficher les erreurs
    if (empty($redirect_url)) {
        echo '<div style="background-color: #ffebee; border: 1px solid #f44336; border-radius: 4px; padding: 10px; margin: 20px;">';
        echo '<h3 style="color: #d32f2f;">Erreurs :</h3>';
        echo '<ul>';
        foreach ($errors as $error) {
            echo '<li style="color: #d32f2f;">' . htmlspecialchars($error) . '</li>';
        }
        echo '</ul>';
        echo '</div>';
    }
}
?>