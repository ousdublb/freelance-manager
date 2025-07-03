<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-5">
    <div class="alert alert-warning">
        <h1 class="display-4">500 - Erreur serveur</h1>
        <p class="lead">Une erreur interne s'est produite. Notre équipe a été notifiée.</p>
        
        <?php if (APP_DEBUG && isset($e)): ?>
        <div class="mt-4 p-3 bg-light rounded">
            <h5>Détails techniques :</h5>
            <pre><?= htmlspecialchars($e->getMessage()) ?></pre>
            <small>Fichier : <?= $e->getFile() ?>:<?= $e->getLine() ?></small>
        </div>
        <?php endif; ?>
        
        <hr>
        <a href="/" class="btn btn-primary">
            <i class="fas fa-home"></i> Retour à l'accueil
        </a>
        <button onclick="window.history.back()" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour
        </button>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>