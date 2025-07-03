<?php
class ClientController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Liste des clients
public function getIndex() {
    $clientModel = new ClientModel($this->db);
    
    // Récupération des clients
    $clients = $clientModel->getAll();
    
    // Passage explicite des variables à la vue
    require_once __DIR__ . '/../views/clients/index.php';
}

    // Affichage du formulaire de création
    public function getCreate() {
        require_once __DIR__ . '/../views/clients/create.php';
    }

    // Traitement de la création
    public function postStore() {
        $data = [
            'nom' => $_POST['nom'] ?? '',
            'email' => $_POST['email'] ?? null,
            'telephone' => $_POST['telephone'] ?? null,
            'adresse' => $_POST['adresse'] ?? null
        ];

        $clientModel = new ClientModel($this->db);
        $success = $clientModel->create($data);

    // Redirection avec feedback
    if ($success) {
        $_SESSION['alert'] = [
            'type' => 'success',
            'message' => 'Client créé avec succès'
        ];
    } else {
        $_SESSION['alert'] = [
            'type' => 'danger',
            'message' => 'Erreur lors de la création du client'
        ];
        $_SESSION['old_input'] = $data;
    }
    
    header('Location: /clients');
    exit;
}

    // Affichage d'un client
    public function getShow($id) {
        $clientModel = new ClientModel($this->db);
        $projectModel = new ProjectModel($this->db);
        
        $client = $clientModel->getById($id);
        $projects = $projectModel->getByClientId($id);

        if (!$client) {
            $this->notFound();
        }

        require_once __DIR__ . '/../views/clients/show.php';
    }

    // Affichage du formulaire d'édition
    public function getEdit($id) {
        $clientModel = new ClientModel($this->db);
        $client = $clientModel->getById($id);

        if (!$client) {
            $this->notFound();
        }

        require_once __DIR__ . '/../views/clients/edit.php';
    }

    // Traitement de la modification
    public function postUpdate($id) {
        $data = [
            'nom' => $_POST['nom'] ?? '',
            'email' => $_POST['email'] ?? null,
            'telephone' => $_POST['telephone'] ?? null,
            'adresse' => $_POST['adresse'] ?? null
        ];

        $clientModel = new ClientModel($this->db);
        $success = $clientModel->update($id, $data);

        $_SESSION['alert'] = [
            'type' => $success ? 'success' : 'danger',
            'message' => $success ? 'Client modifié avec succès' : 'Erreur lors de la modification du client'
        ];

        header('Location: /clients/' . $id);
        exit;
    }

    // Suppression d'un client
    public function postDelete($id) {
        $clientModel = new ClientModel($this->db);
        $success = $clientModel->delete($id);

        $_SESSION['alert'] = [
            'type' => $success ? 'success' : 'danger',
            'message' => $success ? 'Client supprimé avec succès' : 'Erreur lors de la suppression du client'
        ];

        header('Location: /clients');
        exit;
    }

    // Gestion des erreurs 404
    private function notFound() {
        header("HTTP/1.0 404 Not Found");
        require_once __DIR__ . '/../views/errors/404.php';
        exit;
    }
}