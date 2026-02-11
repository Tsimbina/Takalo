/**
 * JavaScript pour la page des propositions d'échange
 */

document.addEventListener('DOMContentLoaded', function() {
    // Active l'onglet sauvegardé dans localStorage ou le premier onglet par défaut
    const activeTab = localStorage.getItem('activePropositionsTab') || 'recues-tab';
    const tab = new bootstrap.Tab(document.getElementById(activeTab));
    tab.show();

    // Sauvegarde l'onglet actif dans localStorage lors du changement d'onglet
    document.querySelectorAll('#propositionTabs button[data-bs-toggle="tab"]').forEach(tabEl => {
        tabEl.addEventListener('shown.bs.tab', function (event) {
            localStorage.setItem('activePropositionsTab', event.target.id);
        });
    });

    // Gestion des formulaires d'action avec AJAX pour accepter/refuser/annuler
    document.querySelectorAll('.proposition-form').forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const action = form.dataset.action;
            const propositionId = form.dataset.propositionId;
            const confirmMessage = getConfirmMessage(action);
            
            if (confirmMessage && !confirm(confirmMessage)) {
                return false;
            }
            
            // Désactiver le bouton pendant le traitement
            const button = form.querySelector('button[type="submit"]');
            button.disabled = true;
            button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Traitement...';
            
            // Envoyer la requête AJAX
            updatePropositionStatus(propositionId, action, button);
        });
    });

    // Animation pour les cartes de propositions
    document.querySelectorAll('.proposition-card').forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.style.transform = 'translateY(-5px)';
        });
        
        card.addEventListener('mouseleave', function() {
            this.style.transform = 'translateY(0)';
        });
    });
});

/**
 * Retourne le message de confirmation approprié selon l'action
 * @param {string} action - Type d'action (accepter, refuser, annuler)
 * @returns {string|null} Message de confirmation ou null si pas de confirmation nécessaire
 */
function getConfirmMessage(action) {
    switch(action) {
        case 'accepter':
            return 'Êtes-vous sûr de vouloir accepter cette proposition d\'échange ?';
        case 'refuser':
            return 'Êtes-vous sûr de vouloir refuser cette proposition d\'échange ?';
        case 'annuler':
            return 'Êtes-vous sûr de vouloir annuler cette proposition d\'échange ?';
        default:
            return null;
    }
}

/**
 * Met à jour le statut d'une proposition via AJAX
 * @param {number} propositionId - ID de la proposition
 * @param {string} action - Action à effectuer (accepter, refuser, annuler)
 * @param {HTMLElement} button - Bouton qui déclenche l'action
 */
function updatePropositionStatus(propositionId, action, button) {
    const formData = new FormData();
    formData.append('action', action);
    
    fetch(`/echone/${propositionId}/${action}`, {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            // Mettre à jour l'interface
            updatePropositionUI(propositionId, action, data.message);
        } else {
            // Afficher une erreur
            showAlert(data.message || 'Une erreur est survenue', 'danger');
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        showAlert('Une erreur technique est survenue', 'danger');
    })
    .finally(() => {
        // Réactiver le bouton
        button.disabled = false;
        button.innerHTML = button.dataset.originalText;
    });
}

/**
 * Met à jour l'interface utilisateur après une action sur une proposition
 * @param {number} propositionId - ID de la proposition
 * @param {string} action - Action effectuée
 * @param {string} message - Message de succès
 */
function updatePropositionUI(propositionId, action, message) {
    const card = document.querySelector(`[data-proposition-id="${propositionId}"]`);
    if (!card) return;
    
    // Mettre à jour le badge de statut
    const badge = card.querySelector('.statut-badge');
    if (badge) {
        badge.className = 'badge statut-badge';
        switch(action) {
            case 'accepter':
                badge.classList.add('statut-accepte');
                badge.textContent = 'Accepté';
                break;
            case 'refuser':
                badge.classList.add('statut-refuse');
                badge.textContent = 'Refusé';
                break;
            case 'annuler':
                badge.classList.add('statut-refuse');
                badge.textContent = 'Annulé';
                break;
        }
    }
    
    // Retirer les boutons d'action
    const footer = card.querySelector('.card-footer');
    if (footer) {
        footer.innerHTML = '<small class="text-muted">Action terminée</small>';
    }
    
    // Afficher un message de succès
    showAlert(message, 'success');
}

/**
 * Affiche une alerte Bootstrap
 * @param {string} message - Message à afficher
 * @param {string} type - Type d'alerte (success, danger, warning, info)
 */
function showAlert(message, type) {
    const alertContainer = document.getElementById('alert-container') || createAlertContainer();
    
    const alert = document.createElement('div');
    alert.className = `alert alert-${type} alert-dismissible fade show`;
    alert.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    
    alertContainer.appendChild(alert);
    
    // Auto-suppression après 5 secondes
    setTimeout(() => {
        if (alert.parentNode) {
            alert.remove();
        }
    }, 5000);
}

/**
 * Crée le conteneur d'alertes s'il n'existe pas
 */
function createAlertContainer() {
    const container = document.createElement('div');
    container.id = 'alert-container';
    container.className = 'position-fixed top-0 end-0 p-3';
    container.style.zIndex = '1050';
    document.body.appendChild(container);
    return container;
}
