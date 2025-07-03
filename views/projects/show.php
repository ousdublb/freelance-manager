<?php require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Détails du Projet</h1>
        <div>
            <a href="/projects/<?= $project['id_projet'] ?>/edit" class="btn btn-warning me-2">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="/projects" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h2 class="h5 mb-0"><?= htmlspecialchars($project['titre']) ?></h2>
        </div>
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h3 class="h6">Informations générales</h3>
                    <ul class="list-unstyled">
                        <li><strong>Client:</strong> <?= htmlspecialchars($project['client_nom']) ?></li>
                        <li><strong>Date début:</strong> <?= date('d/m/Y', strtotime($project['date_debut'])) ?></li>
                        <li><strong>Statut:</strong> 
                            <span class="badge bg-<?= $project['statut'] === 'en cours' ? 'warning' : 'success' ?>">
                                <?= ucfirst($project['statut']) ?>
                            </span>
                        </li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h3 class="h6">Avancement</h3>
                    <div class="progress mb-2" style="height: 25px;">
                        <div class="progress-bar progress-bar-striped bg-success" 
                             role="progressbar" 
                             style="width: <?= $progress ?>%" 
                             aria-valuenow="<?= $progress ?>" 
                             aria-valuemin="0" 
                             aria-valuemax="100">
                            <?= $progress ?>%
                        </div>
                    </div>
                    <p class="mb-0">
                        <?= $completedTasks ?> tâches complétées sur <?= $totalTasks ?>
                        (<?= $totalDuration ?> heures estimées)
                    </p>
                </div>
            </div>

            <h3 class="h6">Description</h3>
            <div class="border p-3 rounded bg-light">
                <?= $project['description'] ? nl2br(htmlspecialchars($project['description'])) : 'Aucune description' ?>
            </div>
        </div>
        <div class="card-footer text-muted">
            <small>Créé le <?= date('d/m/Y à H:i', strtotime($project['date_creation'])) ?></small>
        </div>
    </div>

    <div class="card">
        <div class="card-header bg-light d-flex justify-content-between align-items-center">
            <h2 class="h5 mb-0">Tâches</h2>
            <a href="/projects/<?= $project['id_projet'] ?>/tasks/create" class="btn btn-sm btn-success">
                <i class="fas fa-plus"></i> Nouvelle tâche
            </a>
        </div>
        <div class="card-body">
            <?php if (!empty($tasks)): ?>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Tâche</th>
                                <th>Durée estimée</th>
                                <th>État</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($tasks as $task): ?>
                            <tr>
                                <td><?= htmlspecialchars($task['nom']) ?></td>
                                <td><?= $task['duree_estimee'] ?> heures</td>
                                <td>
                                    <span class="badge bg-<?= 
                                        $task['etat'] === 'fait' ? 'success' : 
                                        ($task['etat'] === 'en cours' ? 'warning' : 'danger') 
                                    ?>">
                                        <?= ucfirst($task['etat']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="/tasks/<?= $task['id_tache'] ?>" class="btn btn-sm btn-info me-1">
                                        <i class="fas fa-eye"></i>
                                    </a>
                                    <a href="/tasks/<?= $task['id_tache'] ?>/edit" class="btn btn-sm btn-warning me-1">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="/tasks/<?= $task['id_tache'] ?>/delete" 
                                       class="btn btn-sm btn-danger btn-delete"
                                       data-item-name="<?= htmlspecialchars($task['nom']) ?>">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php else: ?>
                <div class="alert alert-info">Aucune tâche pour ce projet</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>