<?php require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Tâches du projet: <?= htmlspecialchars($project['titre']) ?></h1>
        <a href="/projects/<?= $project['id_projet'] ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Retour au projet
        </a>
    </div>

    <div class="card mb-4">
        <div class="card-body">
            <div class="d-flex justify-content-between">
                <div>
                    <span class="badge bg-<?= $project['statut'] === 'en cours' ? 'warning' : 'success' ?>">
                        <?= ucfirst($project['statut']) ?>
                    </span>
                    <span class="ms-2">Client: <?= htmlspecialchars($project['client_nom']) ?></span>
                </div>
                <div>
                    <span class="fw-bold">Durée totale estimée:</span>
                    <?= $totalDuration ?> heures
                </div>
            </div>
        </div>
    </div>

    <div class="d-flex justify-content-between mb-4">
        <a href="/projects/<?= $project['id_projet'] ?>/tasks/create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouvelle Tâche
        </a>
        <div class="btn-group">
            <button type="button" class="btn btn-outline-secondary filter-task" data-status="all">Toutes</button>
            <button type="button" class="btn btn-outline-danger filter-task" data-status="à faire">À faire</button>
            <button type="button" class="btn btn-outline-warning filter-task" data-status="en cours">En cours</button>
            <button type="button" class="btn btn-outline-success filter-task" data-status="fait">Terminées</button>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Durée estimée</th>
                    <th>État</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($tasks as $task): ?>
                <tr class="task-row" data-status="<?= $task['etat'] ?>">
                    <td><?= $task['id_tache'] ?></td>
                    <td><?= htmlspecialchars($task['nom']) ?></td>
                    <td><?= $task['duree_estimee'] ?> heures</td>
                    <td>
                        <select class="form-select task-status" data-task-id="<?= $task['id_tache'] ?>" style="width: auto;">
                            <option value="à faire" <?= $task['etat'] === 'à faire' ? 'selected' : '' ?>>À faire</option>
                            <option value="en cours" <?= $task['etat'] === 'en cours' ? 'selected' : '' ?>>En cours</option>
                            <option value="fait" <?= $task['etat'] === 'fait' ? 'selected' : '' ?>>Terminée</option>
                        </select>
                    </td>
                    <td>
                        <a href="/tasks/<?= $task['id_tache'] ?>/edit" class="btn btn-sm btn-warning">
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
</div>

<script src="/assets/js/tasks.js"></script>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>