<?php
class TaskController {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Affichage du formulaire de création
    public function getCreate($projectId) {
        $projectModel = new ProjectModel($this->db);
        $project = $projectModel->getById($projectId);

        if (!$project) {
            $this->notFound();
        }

        require_once __DIR__ . '/../views/tasks/create.php';
    }

    // Traitement de la création
    public function postStore() {
        $projectId = $_POST['id_projet'] ?? null;
        $data = [
            'nom' => $_POST['nom'] ?? '',
            'duree_estimee' => $_POST['duree_estimee'] ?? 1,
            'etat' => $_POST['etat'] ?? 'à faire',
            'id_projet' => $projectId
        ];

        $taskModel = new TaskModel($this->db);
        $success = $taskModel->create($data);

        $_SESSION['alert'] = [
            'type' => $success ? 'success' : 'danger',
            'message' => $success ? 'Tâche créée avec succès' : 'Erreur lors de la création de la tâche'
        ];

        header('Location: /projects/' . $projectId . '/tasks');
        exit;
    }

    // Affichage d'une tâche
    public function getShow($id) {
        $taskModel = new TaskModel($this->db);
        $projectModel = new ProjectModel($this->db);
        
        $task = $taskModel->getByIdWithProject($id);
        
        if (!$task) {
            $this->notFound();
        }

        $project = $projectModel->getByIdWithClient($task['id_projet']);

        require_once __DIR__ . '/../views/tasks/show.php';
    }

    // Affichage du formulaire d'édition
    public function getEdit($id) {
        $taskModel = new TaskModel($this->db);
        $task = $taskModel->getById($id);
        $projectModel = new ProjectModel($this->db);
        $project = $projectModel->getById($task['id_projet']);

        if (!$task || !$project) {
            $this->notFound();
        }

        require_once __DIR__ . '/../views/tasks/edit.php';
    }

    // Traitement de la modification
    public function postUpdate($id) {
        $taskModel = new TaskModel($this->db);
        $task = $taskModel->getById($id);
        
        if (!$task) {
            $this->notFound();
        }

        $data = [
            'nom' => $_POST['nom'] ?? '',
            'duree_estimee' => $_POST['duree_estimee'] ?? 1,
            'etat' => $_POST['etat'] ?? 'à faire',
            'id_projet' => $task['id_projet'] // On conserve le projet d'origine
        ];

        $success = $taskModel->update($id, $data);

        $_SESSION['alert'] = [
            'type' => $success ? 'success' : 'danger',
            'message' => $success ? 'Tâche modifiée avec succès' : 'Erreur lors de la modification de la tâche'
        ];

        header('Location: /projects/' . $task['id_projet'] . '/tasks');
        exit;
    }

    // Suppression d'une tâche
    public function postDelete($id) {
        $taskModel = new TaskModel($this->db);
        $task = $taskModel->getById($id);
        
        if (!$task) {
            $this->notFound();
        }

        $projectId = $task['id_projet'];
        $success = $taskModel->delete($id);

        $_SESSION['alert'] = [
            'type' => $success ? 'success' : 'danger',
            'message' => $success ? 'Tâche supprimée avec succès' : 'Erreur lors de la suppression de la tâche'
        ];

        header('Location: /projects/' . $projectId . '/tasks');
        exit;
    }

    // Mise à jour du statut via AJAX
    public function putUpdateStatus($id) {
        header('Content-Type: application/json');
        
        $etat = file_get_contents('php://input');
        $etat = json_decode($etat, true)['etat'] ?? null;
        
        if (!in_array($etat, ['à faire', 'en cours', 'fait'])) {
            echo json_encode(['success' => false, 'message' => 'Statut invalide']);
            exit;
        }

        $taskModel = new TaskModel($this->db);
        $success = $taskModel->updateStatus($id, $etat);

        echo json_encode([
            'success' => $success,
            'message' => $success ? 'Statut mis à jour' : 'Erreur lors de la mise à jour'
        ]);
        exit;
    }

    // Gestion des erreurs 404
    private function notFound() {
        header("HTTP/1.0 404 Not Found");
        require_once __DIR__ . '/../views/errors/404.php';
        exit;
    }
}