<h1>Tableau de bord</h1>

<div class="row">
    <div class="col-md-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-body">
                <h5 class="card-title">Clients</h5>
                <p class="card-text display-4"><?= $clientsCount ?></p>
                <a href="views/clients" class="text-white">Voir la liste</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-success mb-3">
            <div class="card-body">
                <h5 class="card-title">Projets</h5>
                <p class="card-text display-4"><?= $projectsCount ?></p>
                <a href="views/projects" class="text-white">Voir la liste</a>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card text-white bg-info mb-3">
            <div class="card-body">
                <h5 class="card-title">Tâches</h5>
                <p class="card-text display-4"><?= $tasksCount ?></p>
                <a href="views/tasks" class="text-white">Voir la liste</a>
            </div>
        </div>
    </div>
</div>