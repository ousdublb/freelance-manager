<?php require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Modifier la Tâche</h1>
        <a href="/projects/<?= $projectId ?>/tasks" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left"></i> Retour aux tâches
        </a>
    </div>

    <form action="/tasks/update/<?= $task['id_tache'] ?>" method="POST">
        <input type="hidden" name="id_projet" value="<?= $projectId ?>">
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom *</label>
                    <input type="text" class="form-control" id="nom" name="nom" 
                           value="<?= htmlspecialchars($task['nom']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="duree_estimee" class="form-label">Durée estimée (heures)</label>
                    <input type="number" step="0.5" min="0.5" class="form-control" id="duree_estimee" 
                           name="duree_estimee" value="<?= htmlspecialchars($task['duree_estimee']) ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="etat" class="form-label">État *</label>
                    <select class="form-select" id="etat" name="etat" required>
                        <option value="à faire" <?= $task['etat'] == 'à faire' ? 'selected' : '' ?>>À faire</option>
                        <option value="en cours" <?= $task['etat'] == 'en cours' ? 'selected' : '' ?>>En cours</option>
                        <option value="fait" <?= $task['etat'] == 'fait' ? 'selected' : '' ?>>Terminée</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex justify-content-between mt-4">
            <a href="/projects/<?= $projectId ?>/tasks" class="btn btn-outline-secondary">
                <i class="fas fa-times"></i> Annuler
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>