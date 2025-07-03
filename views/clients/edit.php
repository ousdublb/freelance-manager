<?php require_once __DIR__ . '/../../config/constants.php';require_once __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <h1 class="my-4">Modifier le Client</h1>

    <form action="/clients/update/<?= $client['id_client'] ?>" method="POST">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nom" class="form-label">Nom *</label>
                    <input type="text" class="form-control" id="nom" name="nom" 
                           value="<?= htmlspecialchars($client['nom']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email"
                           value="<?= htmlspecialchars($client['email']) ?>">
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="telephone" class="form-label">Téléphone</label>
                    <input type="tel" class="form-control" id="telephone" name="telephone"
                           value="<?= htmlspecialchars($client['telephone']) ?>">
                </div>
                <div class="mb-3">
                    <label for="adresse" class="form-label">Adresse</label>
                    <textarea class="form-control" id="adresse" name="adresse" rows="2"><?= 
                        htmlspecialchars($client['adresse']) ?></textarea>
                </div>
            </div>
        </div>
        
        <div class="d-flex justify-content-between mt-4">
            <a href="/clients" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left"></i> Annuler
            </a>
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Enregistrer
            </button>
        </div>
    </form>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>