<?php
// 1. Chargement des configurations
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/config/database.php';

set_exception_handler(function($e) {
    error_log("Erreur non capturée: " . $e->getMessage());
    http_response_code(500);
    require __DIR__ . '/views/errors/500.php';
    exit;
});

// 2. Initialisation de la session
session_start();

// 3. Fonction d'autoload pour les contrôleurs
spl_autoload_register(function ($class_name) {
    $file = __DIR__ . '/controllers/' . $class_name . '.php';
    if (file_exists($file)) {
        require_once $file;
    }
});

// 4. Analyse de la requête
$request_uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$request_uri = str_replace(APP_BASE_PATH, '', $request_uri);
$request_uri = trim($request_uri, '/');
$request_method = $_SERVER['REQUEST_METHOD'];

// 5. Routeur principal
try {
    // Route par défaut (tableau de bord)
    if (empty($request_uri) && $request_method === 'GET') {
        $projectsCount = $db->query("SELECT COUNT(*) FROM projets")->fetchColumn();
        $clientsCount = $db->query("SELECT COUNT(*) FROM clients")->fetchColumn();
        $tasksCount = $db->query("SELECT COUNT(*) FROM taches")->fetchColumn();
        
        require_once __DIR__ . '/views/partials/header.php';
        require_once __DIR__ . '/views/dashboard.php';
        require_once __DIR__ . '/views/partials/footer.php';
        exit;
    }

    // Routes GET
    if ($request_method === 'GET') {
        switch ($request_uri) {
            case 'clients':
                $controller = new ClientController($db);
                $controller->getIndex();
                break;
                
            case 'clients/create':
                $controller = new ClientController($db);
                $controller->getCreate();
                break;
                
            case preg_match('#^clients/(\d+)$#', $request_uri, $matches) ? true : false:
                $controller = new ClientController($db);
                $controller->getShow($matches[1]);
                break;
                
            // Ajoutez d'autres routes GET au besoin...
        }
    }
    
    // Routes POST
    elseif ($request_method === 'POST') {
        switch ($request_uri) {
            case 'clients/store':
                $controller = new ClientController($db);
                $controller->postStore();
                break;
                
            // Ajoutez d'autres routes POST au besoin...
        }
    }
    
    // Si aucune route ne correspond
    http_response_code(404);
    require_once __DIR__ . '/views/errors/404.php';
    
} catch (PDOException $e) {
    error_log("Erreur DB: " . $e->getMessage());
    $error = APP_DEBUG ? $e->getMessage() : 'Database error';
    require __DIR__ . '/views/errors/database.php';
    exit;
} catch (Exception $e) {
    error_log("Erreur: " . $e->getMessage());
    http_response_code(500);
    require __DIR__ . '/views/errors/500.php';
    exit;
}


