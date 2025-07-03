<?php require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <h1 class="my-4">Modifier le Projet</h1>

    <form action="/projects/update/<?= $project['id_projet'] ?>" method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="titre" class="form-label">Titre *</label>
                    <input type="text" class="form-control" id="titre" name="titre" 
                           value="<?= htmlspecialchars($project['titre']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="id_client" class="form-label">Client *</label>
                    <select class="form-select" id="id_client" name="id_client" required>
                        <?php foreach ($clients as $client): ?>
                        <option value="<?= $client['id_client'] ?>" 
                            <?= $client['id_client'] == $project['id_client'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($client['nom']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="date_debut" class="form-label">Date de début *</label>
                    <input type="date" class="form-control" id="date_debut" name="date_debut"
                           value="<?= htmlspecialchars($project['date_debut']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="statut" class="form-label">Statut *</label>
                    <select class="form-select" id="statut" name="statut" required>
                        <option value="en cours" <?= $project['statut'] == 'en cours' ? 'selected' : '' ?>>En cours</option>
                        <option value="terminé" <?= $project['statut'] == 'terminé' ? 'selected' : '' ?>>Terminé</option>
                    </select>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea class="form-control" id="description" name="description" rows="4"><?= 
                htmlspecialchars($project['description']) ?></textarea>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
            <a href="/projects" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Annuler
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>