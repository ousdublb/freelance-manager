<?php
class ProjectController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Liste des projets
    public function getIndex() {
        $projectModel = new ProjectModel($this->db);
        $clientModel = new ClientModel($this->db);
        
        $projects = $projectModel->getAllWithClient();
        $clients = $clientModel->getAll();

        require_once __DIR__ . '/../views/projects/index.php';
    }

    // Affichage du formulaire de création
    public function getCreate() {
        $clientModel = new ClientModel($this->db);
        $clients = $clientModel->getAll();

        require_once __DIR__ . '/../views/projects/create.php';
    }

    // Traitement de la création
    public function postStore() {
        $data = [
            'titre' => $_POST['titre'] ?? '',
            'description' => $_POST['description'] ?? null,
            'date_debut' => $_POST['date_debut'] ?? date('Y-m-d'),
            'statut' => $_POST['statut'] ?? 'en cours',
            'id_client' => $_POST['id_client'] ?? null
        ];

        $projectModel = new ProjectModel($this->db);
        $success = $projectModel->create($data);

        $_SESSION['alert'] = [
            'type' => $success ? 'success' : 'danger',
            'message' => $success ? 'Projet créé avec succès' : 'Erreur lors de la création du projet'
        ];

        header('Location: /projects');
        exit;
    }

    // Affichage d'un projet
    public function getShow($id) {
        $projectModel = new ProjectModel($this->db);
        $taskModel = new TaskModel($this->db);
        
        $project = $projectModel->getByIdWithClient($id);
        $tasks = $taskModel->getByProjectId($id);
        
        if (!$project) {
            $this->notFound();
        }

        // Calcul des statistiques
        $totalTasks = count($tasks);
        $completedTasks = array_reduce($tasks, function($carry, $task) {
            return $carry + ($task['etat'] === 'fait' ? 1 : 0);
        }, 0);
        
        $progress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;
        
        $totalDuration = array_reduce($tasks, function($carry, $task) {
            return $carry + (float)$task['duree_estimee'];
        }, 0);

        require_once __DIR__ . '/../views/projects/show.php';
    }

    // Affichage du formulaire d'édition
    public function getEdit($id) {
        $projectModel = new ProjectModel($this->db);
        $clientModel = new ClientModel($this->db);
        
        $project = $projectModel->getById($id);
        $clients = $clientModel->getAll();

        if (!$project) {
            $this->notFound();
        }

        require_once __DIR__ . '/../views/projects/edit.php';
    }

    // Traitement de la modification
    public function postUpdate($id) {
        $data = [
            'titre' => $_POST['titre'] ?? '',
            'description' => $_POST['description'] ?? null,
            'date_debut' => $_POST['date_debut'] ?? date('Y-m-d'),
            'statut' => $_POST['statut'] ?? 'en cours',
            'id_client' => $_POST['id_client'] ?? null
        ];

        $projectModel = new ProjectModel($this->db);
        $success = $projectModel->update($id, $data);

        $_SESSION['alert'] = [
            'type' => $success ? 'success' : 'danger',
            'message' => $success ? 'Projet modifié avec succès' : 'Erreur lors de la modification du projet'
        ];

        header('Location: /projects/' . $id);
        exit;
    }

    // Suppression d'un projet
    public function postDelete($id) {
        $projectModel = new ProjectModel($this->db);
        $success = $projectModel->delete($id);

        $_SESSION['alert'] = [
            'type' => $success ? 'success' : 'danger',
            'message' => $success ? 'Projet supprimé avec succès' : 'Erreur lors de la suppression du projet'
        ];

        header('Location: /projects');
        exit;
    }

    // Gestion des erreurs 404
    private function notFound() {
        header("HTTP/1.0 404 Not Found");
        require_once __DIR__ . '/../views/errors/404.php';
        exit;
    }
}