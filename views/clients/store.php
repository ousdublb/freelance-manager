<?php
// store.php - Script pour traiter l'ajout d'un nouveau client

require_once __DIR__ . '/../../config/database.php';

// Vérifier que la requête est bien en POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /clients');
    exit();
}

try {
    // Récupérer et valider les données du formulaire
    $nom = trim($_POST['nom'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telephone = trim($_POST['telephone'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');
    
    // Validation des données
    $errors = [];
    
    if (empty($nom)) {
        $errors[] = "Le nom est obligatoire";
    }
    
    if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "L'email n'est pas valide";
    }
    
    // Si des erreurs existent, rediriger avec les erreurs
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['old_data'] = $_POST;
        header('Location: /clients/create');
        exit();
    }
    
    // Connexion à la base de données
    $pdo = new PDO($dsn, $username, $password, $options);
    
    // Préparer la requête d'insertion
    $sql = "INSERT INTO clients (nom, email, telephone, adresse, date_creation) 
            VALUES (:nom, :email, :telephone, :adresse, NOW())";
    
    $stmt = $pdo->prepare($sql);
    
    // Exécuter la requête avec les paramètres
    $result = $stmt->execute([
        ':nom' => $nom,
        ':email' => $email ?: null,
        ':telephone' => $telephone ?: null,
        ':adresse' => $adresse ?: null
    ]);
    
    if ($result) {
        // Succès - rediriger avec un message de succès
        $_SESSION['success'] = "Client créé avec succès !";
        header('Location: /clients');
        exit();
    } else {
        throw new Exception("Erreur lors de l'insertion en base de données");
    }
    
} catch (Exception $e) {
    // Gestion des erreurs
    error_log("Erreur création client : " . $e->getMessage());
    $_SESSION['error'] = "Une erreur est survenue lors de la création du client";
    header('Location: /clients/create');
    exit();
}
?>