<?php
header('Content-Type: application/json');
require_once __DIR__ . '/config/database.php';

// Récupère la méthode et l'URI de la requête
$method = $_SERVER['REQUEST_METHOD'];
$request = explode('/', trim($_SERVER['PATH_INFO'],'/'));

// Routeur simple
$endpoint = array_shift($request);
$id = array_shift($request);

switch ("$method:$endpoint") {
    // Dashboard Stats
    case 'GET:dashboard':
        getDashboardStats($db);
        break;
        
    // Gestion des clients
    case 'GET:clients':
        getClients($db);
        break;
    case 'POST:clients':
        addClient($db);
        break;
    case 'DELETE:clients':
        deleteClient($db, $id);
        break;
        
    // Gestion des projets
    case 'GET:projects':
        getProjects($db);
        break;
    case 'POST:projects':
        addProject($db);
        break;
    case 'DELETE:projects':
        deleteProject($db, $id);
        break;
        
    // Gestion des tâches
    case 'GET:tasks':
        getTasks($db);
        break;
    case 'POST:tasks':
        addTask($db);
        break;
    case 'PUT:tasks':
        updateTaskStatus($db, $id);
        break;
    case 'DELETE:tasks':
        deleteTask($db, $id);
        break;
        
    default:
        http_response_code(404);
        echo json_encode(['success' => false, 'message' => 'Endpoint non trouvé']);
        break;
}

// Fonctions pour les endpoints

/**
 * Récupère les statistiques du dashboard
 */
function getDashboardStats($db) {
    try {
        // Clients
        $stmt = $db->query("SELECT COUNT(*) FROM clients");
        $totalClients = $stmt->fetchColumn();
        
        // Projets
        $stmt = $db->query("SELECT COUNT(*) FROM projets");
        $totalProjects = $stmt->fetchColumn();
        
        $stmt = $db->query("SELECT COUNT(*) FROM projets WHERE statut = 'en cours'");
        $activeProjects = $stmt->fetchColumn();
        
        $stmt = $db->query("SELECT COUNT(*) FROM projets WHERE statut = 'terminé'");
        $completedProjects = $stmt->fetchColumn();
        
        // Tâches
        $stmt = $db->query("SELECT COUNT(*) FROM taches");
        $totalTasks = $stmt->fetchColumn();
        
        $stmt = $db->query("SELECT COUNT(*) FROM taches WHERE etat = 'fait'");
        $completedTasks = $stmt->fetchColumn();
        
        echo json_encode([
            'success' => true,
            'data' => [
                'totalClients' => $totalClients,
                'totalProjects' => $totalProjects,
                'activeProjects' => $activeProjects,
                'completedProjects' => $completedProjects,
                'totalTasks' => $totalTasks,
                'completedTasks' => $completedTasks
            ]
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur de base de données']);
    }
}

/**
 * Récupère tous les clients
 */
function getClients($db) {
    try {
        $stmt = $db->query("SELECT * FROM clients ORDER BY nom");
        $clients = $stmt->fetchAll();
        echo json_encode(['success' => true, 'data' => $clients]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur de base de données']);
    }
}

/**
 * Ajoute un nouveau client
 */
function addClient($db) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['nom'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Le nom est obligatoire']);
        return;
    }
    
    try {
        $stmt = $db->prepare("INSERT INTO clients (nom, email, telephone, adresse) VALUES (?, ?, ?, ?)");
        $stmt->execute([$data['nom'], $data['email'] ?? null, $data['telephone'] ?? null, $data['adresse'] ?? null]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Client ajouté avec succès',
            'id' => $db->lastInsertId()
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout du client']);
    }
}

/**
 * Supprime un client
 */
function deleteClient($db, $id) {
    if (empty($id)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID client manquant']);
        return;
    }
    
    try {
        // Vérifie d'abord s'il y a des projets associés
        $stmt = $db->prepare("SELECT COUNT(*) FROM projets WHERE id_client = ?");
        $stmt->execute([$id]);
        
        if ($stmt->fetchColumn() > 0) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Ce client a des projets associés']);
            return;
        }
        
        $stmt = $db->prepare("DELETE FROM clients WHERE id_client = ?");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Client supprimé avec succès']);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Client non trouvé']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
    }
}

/**
 * Récupère tous les projets
 */
function getProjects($db) {
    try {
        $query = "SELECT p.*, c.nom as client_nom 
                 FROM projets p 
                 JOIN clients c ON p.id_client = c.id_client 
                 ORDER BY p.date_debut DESC";
                 
        $stmt = $db->query($query);
        $projects = $stmt->fetchAll();
        echo json_encode(['success' => true, 'data' => $projects]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur de base de données']);
    }
}

/**
 * Ajoute un nouveau projet
 */
function addProject($db) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['titre']) || empty($data['id_client'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Titre et client sont obligatoires']);
        return;
    }
    
    try {
        $stmt = $db->prepare("INSERT INTO projets (titre, description, date_debut, statut, id_client) 
                             VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $data['titre'],
            $data['description'] ?? null,
            $data['date_debut'] ?? date('Y-m-d'),
            $data['statut'] ?? 'en cours',
            $data['id_client']
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Projet ajouté avec succès',
            'id' => $db->lastInsertId()
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout du projet']);
    }
}

/**
 * Supprime un projet
 */
function deleteProject($db, $id) {
    if (empty($id)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID projet manquant']);
        return;
    }
    
    try {
        $db->beginTransaction();
        
        // Supprime d'abord les tâches associées
        $stmt = $db->prepare("DELETE FROM taches WHERE id_projet = ?");
        $stmt->execute([$id]);
        
        // Puis supprime le projet
        $stmt = $db->prepare("DELETE FROM projets WHERE id_projet = ?");
        $stmt->execute([$id]);
        
        $db->commit();
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Projet supprimé avec succès']);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Projet non trouvé']);
        }
    } catch (PDOException $e) {
        $db->rollBack();
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
    }
}

/**
 * Récupère toutes les tâches
 */
function getTasks($db) {
    try {
        $query = "SELECT t.*, p.titre as projet_titre 
                 FROM taches t 
                 JOIN projets p ON t.id_projet = p.id_projet 
                 ORDER BY t.etat, t.date_creation";
                 
        $stmt = $db->query($query);
        $tasks = $stmt->fetchAll();
        echo json_encode(['success' => true, 'data' => $tasks]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur de base de données']);
    }
}

/**
 * Ajoute une nouvelle tâche
 */
function addTask($db) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($data['nom']) || empty($data['id_projet'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Nom et projet sont obligatoires']);
        return;
    }
    
    try {
        $stmt = $db->prepare("INSERT INTO taches (nom, duree_estimee, etat, id_projet) 
                             VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $data['nom'],
            $data['duree_estimee'] ?? null,
            $data['etat'] ?? 'à faire',
            $data['id_projet']
        ]);
        
        echo json_encode([
            'success' => true,
            'message' => 'Tâche ajoutée avec succès',
            'id' => $db->lastInsertId()
        ]);
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur lors de l\'ajout de la tâche']);
    }
}

/**
 * Met à jour le statut d'une tâche
 */
function updateTaskStatus($db, $id) {
    $data = json_decode(file_get_contents('php://input'), true);
    
    if (empty($id) || empty($data['etat'])) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID et état sont obligatoires']);
        return;
    }
    
    try {
        $stmt = $db->prepare("UPDATE taches SET etat = ? WHERE id_tache = ?");
        $stmt->execute([$data['etat'], $id]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Statut mis à jour']);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Tâche non trouvée']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour']);
    }
}

/**
 * Supprime une tâche
 */
function deleteTask($db, $id) {
    if (empty($id)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'ID tâche manquant']);
        return;
    }
    
    try {
        $stmt = $db->prepare("DELETE FROM taches WHERE id_tache = ?");
        $stmt->execute([$id]);
        
        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Tâche supprimée avec succès']);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Tâche non trouvée']);
        }
    } catch (PDOException $e) {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression']);
    }
}