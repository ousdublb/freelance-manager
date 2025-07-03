<?php
class TaskModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function getAll() {
        $query = "SELECT t.*, p.titre as projet_titre 
                 FROM taches t 
                 JOIN projets p ON t.id_projet = p.id_projet 
                 ORDER BY t.date_creation DESC";
        try {
            $stmt = $this->db->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("TaskModel::getAll() - Erreur: " . $e->getMessage());
            return [];
        }
    }

    public function getById($id) {
        $query = "SELECT * FROM taches WHERE id_tache = :id";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("TaskModel::getById() - Erreur: " . $e->getMessage());
            return false;
        }
    }

    public function getByIdWithProject($id) {
        $query = "SELECT t.*, p.titre as projet_titre 
                 FROM taches t 
                 JOIN projets p ON t.id_projet = p.id_projet 
                 WHERE t.id_tache = :id";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("TaskModel::getByIdWithProject() - Erreur: " . $e->getMessage());
            return false;
        }
    }

    public function getByProjectId($projectId) {
        $query = "SELECT * FROM taches WHERE id_projet = :projectId ORDER BY etat, date_creation";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':projectId', $projectId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("TaskModel::getByProjectId() - Erreur: " . $e->getMessage());
            return [];
        }
    }

    public function create($data) {
        $query = "INSERT INTO taches (nom, duree_estimee, etat, id_projet) 
                 VALUES (:nom, :duree_estimee, :etat, :id_projet)";
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':nom' => $data['nom'],
                ':duree_estimee' => $data['duree_estimee'],
                ':etat' => $data['etat'],
                ':id_projet' => $data['id_projet']
            ]);
        } catch (PDOException $e) {
            error_log("TaskModel::create() - Erreur: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        $query = "UPDATE taches SET 
                 nom = :nom,
                 duree_estimee = :duree_estimee,
                 etat = :etat,
                 id_projet = :id_projet
                 WHERE id_tache = :id";
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':id' => $id,
                ':nom' => $data['nom'],
                ':duree_estimee' => $data['duree_estimee'],
                ':etat' => $data['etat'],
                ':id_projet' => $data['id_projet']
            ]);
        } catch (PDOException $e) {
            error_log("TaskModel::update() - Erreur: " . $e->getMessage());
            return false;
        }
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE taches SET etat = :etat WHERE id_tache = :id";
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':id' => $id,
                ':etat' => $status
            ]);
        } catch (PDOException $e) {
            error_log("TaskModel::updateStatus() - Erreur: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        $query = "DELETE FROM taches WHERE id_tache = :id";
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("TaskModel::delete() - Erreur: " . $e->getMessage());
            return false;
        }
    }

    public function deleteByProject($projectId) {
        $query = "DELETE FROM taches WHERE id_projet = :projectId";
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([':projectId' => $projectId]);
        } catch (PDOException $e) {
            error_log("TaskModel::deleteByProject() - Erreur: " . $e->getMessage());
            return false;
        }
    }

    public function countByStatus($status) {
        $query = "SELECT COUNT(*) FROM taches WHERE etat = :status";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':status', $status, PDO::PARAM_STR);
            $stmt->execute();
            return $stmt->fetchColumn();
        } catch (PDOException $e) {
            error_log("TaskModel::countByStatus() - Erreur: " . $e->getMessage());
            return 0;
        }
    }
}