<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - <?= $pageTitle ?? 'Tableau de bord' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="/assets/css/style.css" rel="stylesheet">
</head>
<body>
    <?php require_once __DIR__ . '/alerts.php'; ?>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand" href="/freelance-manager"><?= APP_NAME ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/freelance-manager"><i class="fas fa-home"></i> Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/freelance-manager/views/clients"><i class="fas fa-users"></i> Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/freelance-manager/views/projects"><i class="fas fa-project-diagram"></i> Projets</a>
                    </li>
                </ul>
                <div class="d-flex">
                    <input type="text" class="form-control me-2" placeholder="Recherche...">
                    <button class="btn btn-outline-light"><i class="fas fa-search"></i></button>
                </div>
            </div>
        </div>
    </nav>

    <div class="container my-4">