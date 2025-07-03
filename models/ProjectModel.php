<?php
class ProjectModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM projets ORDER BY date_debut DESC";
        try {
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ProjectModel::getAll() - Erreur: " . $e->getMessage());
            return [];
        }
    }

    public function getAllWithClient() {
        $query = "SELECT p.*, c.nom as client_nom 
                 FROM projets p 
                 JOIN clients c ON p.id_client = c.id_client 
                 ORDER BY p.date_debut DESC";
        try {
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ProjectModel::getAllWithClient() - Erreur: " . $e->getMessage());
            return [];
        }
    }

    public function getById($id) {
        $query = "SELECT * FROM projets WHERE id_projet = :id";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ProjectModel::getById() - Erreur: " . $e->getMessage());
            return false;
        }
    }

    public function getByIdWithClient($id) {
        $query = "SELECT p.*, c.nom as client_nom 
                 FROM projets p 
                 JOIN clients c ON p.id_client = c.id_client 
                 WHERE p.id_projet = :id";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ProjectModel::getByIdWithClient() - Erreur: " . $e->getMessage());
            return false;
        }
    }

    public function getByClientId($clientId) {
        $query = "SELECT * FROM projets WHERE id_client = :clientId ORDER BY date_debut DESC";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':clientId', $clientId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ProjectModel::getByClientId() - Erreur: " . $e->getMessage());
            return [];
        }
    }

    public function create($data) {
        $query = "INSERT INTO projets (titre, description, date_debut, statut, id_client) 
                 VALUES (:titre, :description, :date_debut, :statut, :id_client)";
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':titre' => $data['titre'],
                ':description' => $data['description'],
                ':date_debut' => $data['date_debut'],
                ':statut' => $data['statut'],
                ':id_client' => $data['id_client']
            ]);
        } catch (PDOException $e) {
            error_log("ProjectModel::create() - Erreur: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        $query = "UPDATE projets SET 
                 titre = :titre,
                 description = :description,
                 date_debut = :date_debut,
                 statut = :statut,
                 id_client = :id_client
                 WHERE id_projet = :id";
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':id' => $id,
                ':titre' => $data['titre'],
                ':description' => $data['description'],
                ':date_debut' => $data['date_debut'],
                ':statut' => $data['statut'],
                ':id_client' => $data['id_client']
            ]);
        } catch (PDOException $e) {
            error_log("ProjectModel::update() - Erreur: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        try {
            $this->db->beginTransaction();
            
            // Supprimer d'abord les tâches associées
            $taskModel = new TaskModel($this->db);
            $taskModel->deleteByProject($id);
            
            // Puis supprimer le projet
            $query = "DELETE FROM projets WHERE id_projet = :id";
            $stmt = $this->db->prepare($query);
            $result = $stmt->execute([':id' => $id]);
            
            $this->db->commit();
            return $result;
        } catch (PDOException $e) {
            $this->db->rollBack();
            error_log("ProjectModel::delete() - Erreur: " . $e->getMessage());
            return false;
        }
    }

    public function countAll() {
        $query = "SELECT COUNT(*) FROM projets";
        try {
            return $this->db->query($query)->fetchColumn();
        } catch (PDOException $e) {
            error_log("ProjectModel::countAll() - Erreur: " . $e->getMessage());
            return 0;
        }
    }

    public function countByStatus($status) {
        $query = "SELECT COUNT(*) FROM projets WHERE statut = :status";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("ProjectModel::countByStatus() - Erreur: " . $e->getMessage());
            return 0;
        }
    }
}