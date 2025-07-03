// script.js - Fichier principal JavaScript pour l'application Freelance Manager

document.addEventListener('DOMContentLoaded', function() {
    // Initialisation de l'application
    initApp();
    
    // Gestion des événements
    setupEventListeners();
});

/**
 * Initialise l'application
 */
function initApp() {
    // Vérifie si nous sommes sur la page de tableau de bord
    if (document.getElementById('dashboard-page')) {
        updateDashboardStats();
    }
    
    // Initialise les sélecteurs de date
    const dateInputs = document.querySelectorAll('input[type="date"]');
    dateInputs.forEach(input => {
        if (!input.value) {
            input.valueAsDate = new Date();
        }
    });
    
    // Initialise les tooltips Bootstrap
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
}

/**
 * Configure les écouteurs d'événements
 */
function setupEventListeners() {
    // Gestion des formulaires
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', handleFormSubmit);
    });
    
    // Gestion des boutons de suppression
    document.querySelectorAll('.btn-delete').forEach(btn => {
        btn.addEventListener('click', confirmDelete);
    });
    
    // Filtrage des projets par client
    const clientFilter = document.getElementById('client-filter');
    if (clientFilter) {
        clientFilter.addEventListener('change', filterProjectsByClient);
    }
    
    // Barre de recherche
    const searchInput = document.getElementById('search-input');
    if (searchInput) {
        searchInput.addEventListener('input', searchItems);
    }
}

/**
 * Met à jour les statistiques du tableau de bord via AJAX
 */
function updateDashboardStats() {
    fetch('/api/dashboard/stats')
        .then(response => response.json())
        .then(data => {
            document.getElementById('total-clients').textContent = data.totalClients;
            document.getElementById('total-projects').textContent = data.totalProjects;
            document.getElementById('active-projects').textContent = data.activeProjects;
            document.getElementById('completed-projects').textContent = data.completedProjects;
            document.getElementById('total-tasks').textContent = data.totalTasks;
            document.getElementById('completed-tasks').textContent = data.completedTasks;
        })
        .catch(error => console.error('Erreur:', error));
}

/**
 * Gère la soumission des formulaires
 */
function handleFormSubmit(e) {
    e.preventDefault();
    const form = e.target;
    const formData = new FormData(form);
    const submitBtn = form.querySelector('button[type="submit"]');
    
    // Désactive le bouton pendant l'envoi
    submitBtn.disabled = true;
    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Envoi en cours...';
    
    // Envoi AJAX
    fetch(form.action, {
        method: form.method,
        body: formData
    })
    .then(response => {
        if (!response.ok) throw new Error('Erreur réseau');
        return response.json();
    })
    .then(data => {
        showAlert(data.success ? 'success' : 'danger', data.message);
        if (data.success) {
            if (form.dataset.redirect) {
                window.location.href = form.dataset.redirect;
            } else {
                form.reset();
                // Mise à jour de l'interface si nécessaire
                if (document.getElementById('dashboard-page')) {
                    updateDashboardStats();
                }
            }
        }
    })
    .catch(error => {
        showAlert('danger', 'Une erreur est survenue: ' + error.message);
    })
    .finally(() => {
        submitBtn.disabled = false;
        submitBtn.textContent = 'Sauvegarder';
    });
}

/**
 * Confirme la suppression d'un élément
 */
function confirmDelete(e) {
    e.preventDefault();
    const itemName = this.dataset.itemName || 'cet élément';
    
    if (confirm(`Êtes-vous sûr de vouloir supprimer ${itemName} ? Cette action est irréversible.`)) {
        fetch(this.href, {
            method: 'DELETE',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            showAlert(data.success ? 'success' : 'danger', data.message);
            if (data.success) {
                // Supprime la ligne du tableau ou recharge la page
                const row = this.closest('tr');
                if (row) {
                    row.remove();
                    updateDashboardStats();
                } else {
                    window.location.reload();
                }
            }
        })
        .catch(error => {
            showAlert('danger', 'Erreur lors de la suppression: ' + error.message);
        });
    }
}

/**
 * Filtre les projets par client
 */
function filterProjectsByClient() {
    const clientId = this.value;
    const projectRows = document.querySelectorAll('.project-row');
    
    projectRows.forEach(row => {
        if (clientId === 'all' || row.dataset.clientId === clientId) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

/**
 * Recherche dans les éléments
 */
function searchItems() {
    const searchTerm = this.value.toLowerCase();
    const items = document.querySelectorAll('.searchable-item');
    
    items.forEach(item => {
        const text = item.textContent.toLowerCase();
        if (text.includes(searchTerm)) {
            item.style.display = '';
        } else {
            item.style.display = 'none';
        }
    });
}

/**
 * Affiche une notification
 */
function showAlert(type, message) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
    alertDiv.role = 'alert';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    `;
    
    const container = document.getElementById('alerts-container') || document.body;
    container.prepend(alertDiv);
    
    // Supprime automatiquement l'alerte après 5 secondes
    setTimeout(() => {
        const alert = bootstrap.Alert.getOrCreateInstance(alertDiv);
        alert.close();
    }, 5000);
}

/**
 * Fonction utilitaire pour formater la date
 */
function formatDate(dateString) {
    const options = { year: 'numeric', month: 'short', day: 'numeric' };
    return new Date(dateString).toLocaleDateString('fr-FR', options);
}

// Exporte les fonctions si nécessaire pour d'autres fichiers JS
if (typeof module !== 'undefined' && module.exports) {
    module.exports = {
        initApp,
        showAlert,
        formatDate
    };
}