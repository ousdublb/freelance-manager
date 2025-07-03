<?php require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Fiche Client</h1>
        <div>
            <a href="/clients/<?= $client['id_client'] ?>/edit" class="btn btn-warning me-2">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="/clients" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h2 class="h5 mb-0"><?= htmlspecialchars($client['nom']) ?></h2>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="h6">Coordonnées</h3>
                    <ul class="list-unstyled">
                        <li><strong>Email:</strong> <?= $client['email'] ? htmlspecialchars($client['email']) : 'Non renseigné' ?></li>
                        <li><strong>Téléphone:</strong> <?= $client['telephone'] ? htmlspecialchars($client['telephone']) : 'Non renseigné' ?></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h3 class="h6">Adresse</h3>
                    <address>
                        <?= $client['adresse'] ? nl2br(htmlspecialchars($client['adresse'])) : 'Non renseignée' ?>
                    </address>
                </div>
            </div>
        </div>
        <div class="card-footer text-muted">
            <small>Créé le <?= date('d/m/Y à H:i', strtotime($client['date_creation'])) ?></small>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light">
            <h2 class="h5 mb-0">Projets associés</h2>
        </div>
        <div class="card-body">
            <?php if (!empty($projects)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Projet</th>
                                <th>Date début</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($projects as $project): ?>
                            <tr>
                                <td><?= htmlspecialchars($project['titre']) ?></td>
                                <td><?= date('d/m/Y', strtotime($project['date_debut'])) ?></td>
                                <td>
                                    <span class="badge bg-<?= $project['statut'] === 'en cours' ? 'warning' : 'success' ?>">
                                        <?= ucfirst($project['statut']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/projects/<?= $project['id_projet'] ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Aucun projet associé à ce client</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>