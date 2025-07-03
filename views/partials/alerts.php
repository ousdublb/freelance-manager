<?php if (isset($_SESSION['alert'])): ?>
<div class="container mt-3">
    <div class="alert alert-<?= $_SESSION['alert']['type'] ?> alert-dismissible fade show">
        <?= $_SESSION['alert']['message'] ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
</div>
<?php 
unset($_SESSION['alert']);
endif; ?>