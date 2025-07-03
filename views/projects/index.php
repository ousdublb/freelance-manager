<?php require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <h1 class="my-4">Gestion des Projets</h1>

    <div class="d-flex justify-content-between mb-4">
        <a href="/freelance-manager/views/projects/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau Projet
        </a>
        <div class="d-flex gap-3">
            <select id="client-filter" class="form-select w-auto">
                <option value="all">Tous les clients</option>
                <?php foreach ($clients as $client): ?>
                <option value="<?= $client['id_client'] ?>">
                    <?= htmlspecialchars($client['nom']) ?>
                </option>
                <?php endforeach; ?>
            </select>
            <select id="status-filter" class="form-select w-auto">
                <option value="all">Tous les statuts</option>
                <option value="en cours">En cours</option>
                <option value="terminé">Terminé</option>
            </select>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Titre</th>
                    <th>Client</th>
                    <th>Date début</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($projects as $project): ?>
                <tr class="project-row" data-client-id="<?= $project['id_client'] ?>" data-status="<?= $project['statut'] ?>">
                    <td><?= $project['id_projet'] ?></td>
                    <td><?= htmlspecialchars($project['titre']) ?></td>
                    <td><?= htmlspecialchars($project['client_nom']) ?></td>
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
                        <a href="/projects/<?= $project['id_projet'] ?>/edit" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="/projects/<?= $project['id_projet'] ?>/tasks" class="btn btn-sm btn-secondary">
                            <i class="fas fa-tasks"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<script src="/assets/js/projects.js"></script>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>