<?php
// config/constants.php

/**
 * Constantes de configuration de l'application
 */

// 1. Constantes de l'application
define('APP_NAME', 'Freelance Manager');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // 'development' ou 'production'
define('APP_DEBUG', true); // Mettre à false en production
define('APP_URL', 'http://localhost/freelance-manager');
define('APP_ROOT', dirname(__DIR__));

// URL et chemins
$base_path = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME']);
define('APP_BASE_PATH', $base_path);

// 2. Constantes de session
define('SESSION_NAME', 'FREELANCE_SESSID');
define('SESSION_LIFETIME', 86400); // 24 heures en secondes
define('SESSION_PATH', '/');
define('SESSION_DOMAIN', '');
define('SESSION_SECURE', false); // Mettre à true en HTTPS
define('SESSION_HTTPONLY', true);

// 3. Constantes de sécurité
define('CSRF_TOKEN_NAME', 'csrf_token');
define('PASSWORD_ALGO', PASSWORD_BCRYPT);
define('PASSWORD_OPTIONS', ['cost' => 12]);

// 4. Constantes de format
define('DATE_FORMAT', 'd/m/Y');
define('DATETIME_FORMAT', 'd/m/Y H:i:s');
define('TIME_FORMAT', 'H:i:s');

// 5. Constantes de fichiers et répertoires
define('UPLOAD_DIR', APP_ROOT . '/uploads');
define('MAX_UPLOAD_SIZE', 5 * 1024 * 1024); // 5MB
define('ALLOWED_FILE_TYPES', [
    'image/jpeg',
    'image/png',
    'application/pdf',
    'application/msword',
    'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
]);

// 6. Constantes des rôles utilisateurs (si ajout d'authentification)
define('ROLE_ADMIN', 1);
define('ROLE_FREELANCER', 2);
define('ROLE_CLIENT', 3);

// 7. Constantes des états
define('PROJECT_STATUS_ACTIVE', 'en cours');
define('PROJECT_STATUS_COMPLETED', 'terminé');

define('TASK_STATUS_TODO', 'à faire');
define('TASK_STATUS_IN_PROGRESS', 'en cours');
define('TASK_STATUS_DONE', 'fait');

// 8. Messages d'erreur génériques
define('ERROR_404', 'La ressource demandée n\'a pas été trouvée.');
define('ERROR_403', 'Accès non autorisé.');
define('ERROR_DB', 'Une erreur de base de données est survenue.');
define('ERROR_FORM', 'Veuillez corriger les erreurs dans le formulaire.');

// 9. Paramètres d'affichage
define('ITEMS_PER_PAGE', 10); // Pagination
define('DEFAULT_TIMEZONE', 'Europe/Paris');

// 10. Configuration des logs
define('LOG_DIR', APP_ROOT . '/logs');
define('LOG_ERROR', LOG_DIR . '/errors.log');
define('LOG_ACCESS', LOG_DIR . '/access.log');
define('LOG_QUERY', LOG_DIR . '/queries.log');

// Initialisation des paramètres
if (APP_DEBUG) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
}

// Définition du fuseau horaire
date_default_timezone_set(DEFAULT_TIMEZONE);

// Vérification des répertoires requis
$requiredDirs = [UPLOAD_DIR, LOG_DIR];
foreach ($requiredDirs as $dir) {
    if (!file_exists($dir)) {
        mkdir($dir, 0755, true);
    }
}

