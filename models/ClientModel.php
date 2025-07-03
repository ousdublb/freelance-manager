<?php
class ClientModel {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

public function getAll() {
    $query = "SELECT * FROM clients ORDER BY nom ASC";
    try {
        $stmt = $this->db->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC) ?: []; // Retourne toujours un tableau
    } catch (PDOException $e) {
        error_log("Erreur ClientModel::getAll(): " . $e->getMessage());
        return []; // Retourne un tableau vide même en cas d'erreur
    }
}

    public function getById($id) {
        $query = "SELECT * FROM clients WHERE id_client = :id";
        try {
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("ClientModel::getById() - Erreur: " . $e->getMessage());
            return false;
        }
    }

    public function create($data) {
        $query = "INSERT INTO clients (nom, email, telephone, adresse, date_creation) 
                VALUES (:nom, :email, :telephone, :adresse, NOW())";
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':nom' => $data['nom'],
                ':email' => $data['email'],
                ':telephone' => $data['telephone'],
                ':adresse' => $data['adresse']
            ]);
        } catch (PDOException $e) {
            error_log("ClientModel::create() - Erreur: " . $e->getMessage());
            return false;
        }
    }

    public function update($id, $data) {
        $query = "UPDATE clients SET 
                 nom = :nom,
                 email = :email,
                 telephone = :telephone,
                 adresse = :adresse
                 WHERE id_client = :id";
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([
                ':id' => $id,
                ':nom' => $data['nom'],
                ':email' => $data['email'],
                ':telephone' => $data['telephone'],
                ':adresse' => $data['adresse']
            ]);
        } catch (PDOException $e) {
            error_log("ClientModel::update() - Erreur: " . $e->getMessage());
            return false;
        }
    }

    public function delete($id) {
        $query = "DELETE FROM clients WHERE id_client = :id";
        try {
            $stmt = $this->db->prepare($query);
            return $stmt->execute([':id' => $id]);
        } catch (PDOException $e) {
            error_log("ClientModel::delete() - Erreur: " . $e->getMessage());
            return false;
        }
    }

    public function countAll() {
        $query = "SELECT COUNT(*) FROM clients";
        try {
            return $this->db->query($query)->fetchColumn();
        } catch (PDOException $e) {
            error_log("ClientModel::countAll() - Erreur: " . $e->getMessage());
            return 0;
        }
    }
}