<?php
/**
 * Partial de pagination
 * 
 * @param int $currentPage Page actuelle
 * @param int $totalItems Nombre total d'éléments
 * @param int $itemsPerPage Nombre d'éléments par page
 * @param string $baseUrl URL de base pour les liens (sans le paramètre page)
 * @param int $visiblePages Nombre de pages visibles autour de la page actuelle
 */

// Initialisation des variables avec des valeurs par défaut
$currentPage = isset($currentPage) ? $currentPage : 1;
$itemsPerPage = isset($itemsPerPage) ? $itemsPerPage : (defined('ITEMS_PER_PAGE') ? ITEMS_PER_PAGE : 10);
$totalPages = ceil($totalItems / $itemsPerPage);
$visiblePages = isset($visiblePages) ? $visiblePages : 5;
$baseUrl = isset($baseUrl) ? $baseUrl : '';
$queryString = isset($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : '';
$queryString = preg_replace('/page=\d+&?/', '', $queryString);
$queryString = $queryString ? '?' . $queryString : '';

// Ne rien afficher s'il n'y a qu'une seule page
if ($totalPages <= 1) return;
?>

<nav aria-label="Page navigation" class="mt-4">
    <ul class="pagination justify-content-center">
        <!-- Lien Précédent -->
        <li class="page-item <?php echo $currentPage <= 1 ? 'disabled' : ''; ?>">
            <a class="page-link" 
               href="<?php echo $baseUrl . $queryString . ($queryString ? '&' : '?'); ?>page=<?php echo $currentPage - 1; ?>" 
               aria-label="Précédent">
                <span aria-hidden="true">&laquo;</span>
            </a>
        </li>

        <!-- Première page -->
        <?php if ($currentPage > ceil($visiblePages / 2) + 1): ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo $baseUrl . $queryString . ($queryString ? '&' : '?'); ?>page=1">1</a>
            </li>
            <?php if ($currentPage > ceil($visiblePages / 2) + 2): ?>
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            <?php endif; ?>
        <?php endif; ?>

        <!-- Pages autour de la page actuelle -->
        <?php 
        $start = max(1, $currentPage - floor($visiblePages / 2));
        $end = min($totalPages, $start + $visiblePages - 1);
        
        // Ajuster si on est proche de la fin
        $start = max(1, $end - $visiblePages + 1);
        
        for ($i = $start; $i <= $end; $i++): ?>
            <li class="page-item <?php echo $i == $currentPage ? 'active' : ''; ?>">
                <a class="page-link" 
                   href="<?php echo $baseUrl . $queryString . ($queryString ? '&' : '?'); ?>page=<?php echo $i; ?>">
                   <?php echo $i; ?>
                </a>
            </li>
        <?php endfor; ?>

        <!-- Dernière page -->
        <?php if ($currentPage < $totalPages - floor($visiblePages / 2)): ?>
            <?php if ($currentPage < $totalPages - floor($visiblePages / 2) - 1): ?>
                <li class="page-item disabled">
                    <span class="page-link">...</span>
                </li>
            <?php endif; ?>
            <li class="page-item">
                <a class="page-link" href="<?php echo $baseUrl . $queryString . ($queryString ? '&' : '?'); ?>page=<?php echo $totalPages; ?>">
                    <?php echo $totalPages; ?>
                </a>
            </li>
        <?php endif; ?>

        <!-- Lien Suivant -->
        <li class="page-item <?php echo $currentPage >= $totalPages ? 'disabled' : ''; ?>">
            <a class="page-link" 
               href="<?php echo $baseUrl . $queryString . ($queryString ? '&' : '?'); ?>page=<?php echo $currentPage + 1; ?>" 
               aria-label="Suivant">
                <span aria-hidden="true">&raquo;</span>
            </a>
        </li>
    </ul>
</nav>