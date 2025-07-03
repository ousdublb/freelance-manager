<?php require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Détails de la Tâche</h1>
        <div>
            <a href="/tasks/<?= $task['id_tache'] ?>/edit" class="btn btn-warning me-2">
                <i class="fas fa-edit"></i> Modifier
            </a>
            <a href="/projects/<?= $project['id_projet'] ?>/tasks" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Retour
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h2 class="h5 mb-0"><?= htmlspecialchars($task['nom']) ?></h2>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <h3 class="h6">Informations</h3>
                            <ul class="list-unstyled">
                                <li><strong>Projet:</strong> <?= htmlspecialchars($project['titre']) ?></li>
                                <li><strong>Client:</strong> <?= htmlspecialchars($project['client_nom']) ?></li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h3 class="h6">Statistiques</h3>
                            <ul class="list-unstyled">
                                <li><strong>Durée estimée:</strong> <?= $task['duree_estimee'] ?> heures</li>
                                <li><strong>État:</strong> 
                                    <span class="badge bg-<?= 
                                        $task['etat'] === 'fait' ? 'success' : 
                                        ($task['etat'] === 'en cours' ? 'warning' : 'danger') 
                                    ?>">
                                        <?= ucfirst($task['etat']) ?>
                                    </span>
                                </li>
                            </ul>
                        </div>
                    </div>

                    <h3 class="h6">Dates</h3>
                    <ul class="list-unstyled">
                        <li><strong>Créée le:</strong> <?= date('d/m/Y à H:i', strtotime($task['date_creation'])) ?></li>
                        <?php if ($task['date_modif']): ?>
                        <li><strong>Modifiée le:</strong> <?= date('d/m/Y à H:i', strtotime($task['date_modif'])) ?></li>
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header bg-light">
                    <h2 class="h5 mb-0">Actions rapides</h2>
                </div>
                <div class="card-body">
                    <form id="quick-status-form" class="mb-3">
                        <label class="form-label">Changer l'état</label>
                        <select class="form-select mb-2 task-status" data-task-id="<?= $task['id_tache'] ?>">
                            <option value="à faire" <?= $task['etat'] === 'à faire' ? 'selected' : '' ?>>À faire</option>
                            <option value="en cours" <?= $task['etat'] === 'en cours' ? 'selected' : '' ?>>En cours</option>
                            <option value="fait" <?= $task['etat'] === 'fait' ? 'selected' : '' ?>>Terminée</option>
                        </select>
                    </form>
                    <div class="d-grid gap-2">
                        <a href="/projects/<?= $project['id_projet'] ?>" class="btn btn-outline-primary">
                            <i class="fas fa-project-diagram"></i> Voir le projet
                        </a>
                        <a href="/projects/<?= $project['id_projet'] ?>/tasks/create" class="btn btn-outline-success">
                            <i class="fas fa-plus"></i> Nouvelle tâche
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/assets/js/tasks.js"></script>
<?php require_once __DIR__ . '/../partials/footer.php'; ?>