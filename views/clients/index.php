<?php if (!isset($clients) || !is_array($clients)) {
    $clients = []; // Initialise avec un tableau vide si non défini
}
require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/../partials/header.php'; ?>

<div class="container">
    <h1 class="my-4">Gestion des Clients</h1>

    <div class="d-flex justify-content-between mb-4">
        <a href="create" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nouveau Client
        </a>
        <div class="w-50">
            <input type="text" id="search-client" class="form-control" placeholder="Rechercher un client...">
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-striped table-hover">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Téléphone</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clients as $client): ?>
                <tr>
                    <td><?= htmlspecialchars($client['id_client']) ?></td>
                    <td><?= htmlspecialchars($client['nom']) ?></td>
                    <td><?= htmlspecialchars($client['email']) ?></td>
                    <td><?= htmlspecialchars($client['telephone']) ?></td>
                    <td>
                        <a href="/clients/<?= $client['id_client'] ?>" class="btn btn-sm btn-info">
                            <i class="fas fa-eye"></i>
                        </a>
                        <a href="/clients/<?= $client['id_client'] ?>/edit" class="btn btn-sm btn-warning">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="/clients/<?= $client['id_client'] ?>/delete" 
                           class="btn btn-sm btn-danger btn-delete" 
                           data-item-name="<?= htmlspecialchars($client['nom']) ?>">
                            <i class="fas fa-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../partials/footer.php'; ?>