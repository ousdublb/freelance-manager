<?php require_once __DIR__ . '/../partials/header.php'; ?>

<div class="container mt-5">
    <div class="alert alert-danger">
        <h1 class="display-4">Erreur de base de données</h1>
        <p class="lead">Impossible de se connecter au système de données.</p>
        
        <?php if (APP_DEBUG && isset($error)): ?>
        <div class="mt-4 p-3 bg-light rounded">
            <h5>Détails :</h5>
            <pre><?= htmlspecialchars($error) ?></pre>
        </div>
        <?php endif; ?>
        
        <hr>
        <div class="d-flex gap-3">
            <a href="/" class="btn btn-primary">
                <i class="fas fa-home"></i> Accueil
            </a>
            <button onclick="window.location.reload()" class="btn btn-warning">
                <i class="fas fa-sync-alt"></i> Réessayer
            </button>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>