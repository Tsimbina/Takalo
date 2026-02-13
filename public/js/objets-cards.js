// JavaScript pour la page des cartes d'objets
class ObjetsCardsManager {
    constructor() {
        this.objets = [];
        this.filtreActif = 'tous';
        this.init();
    }

    init() {
        this.bindEvents();
        this.loadObjets();
    }

    bindEvents() {
        // Écouteurs pour les boutons de filtre
        document.querySelectorAll('.filtre-btn').forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.setFiltre(e.target.dataset.categorie);
            });
        });

        // Écouteur pour le bouton d'échange
        document.addEventListener('click', (e) => {
            if (e.target.closest('.btn-echanger')) {
                const btn = e.target.closest('.btn-echanger');
                const objetId = btn.dataset.objetId;
                this.handleEchange(objetId);
            }
        });

        // Écouteur pour le scroll infini
        window.addEventListener('scroll', () => {
            this.handleScroll();
        });
    }

    async loadObjets() {
        this.showLoading(true);
        
        try {
            // Simulation de chargement d'objets depuis une API
            // En production, remplacer par un vrai appel API
            const response = await this.simulateApiCall();
            this.objets = response.objets;
            this.renderObjets();
        } catch (error) {
            console.error('Erreur lors du chargement des objets:', error);
            this.showError('Une erreur est survenue lors du chargement des objets');
        } finally {
            this.showLoading(false);
        }
    }

    simulateApiCall() {
        return new Promise((resolve) => {
            setTimeout(() => {
                resolve({
                    objets: [
                        {
                            id: 1,
                            titre: "iPhone 13 Pro",
                            description: "iPhone 13 Pro en excellent état, 256GB, couleur Graphite. Vendu avec boîte et accessoires d'origine.",
                            prix: 850000,
                            categorie: "Électronique",
                            image: "https://via.placeholder.com/300x200/667eea/ffffff?text=iPhone+13+Pro"
                        },
                        {
                            id: 2,
                            titre: "PlayStation 5",
                            description: "PS5 avec manette DualSense, moins d'un an d'utilisation. Jeux inclus: FIFA 23, Gran Turismo 7.",
                            prix: 650000,
                            categorie: "Gaming",
                            image: "https://via.placeholder.com/300x200/764ba2/ffffff?text=PlayStation+5"
                        },
                        {
                            id: 3,
                            titre: "MacBook Air M1",
                            description: "MacBook Air M1 2020, 8GB RAM, 256GB SSD. État impeccable, batterie en excellent état.",
                            prix: 950000,
                            categorie: "Informatique",
                            image: "https://via.placeholder.com/300x200/667eea/ffffff?text=MacBook+Air"
                        },
                        {
                            id: 4,
                            titre: "Montre Connectée",
                            description: "Apple Watch Series 7, 45mm, GPS + Cellular. Bracelet en silicone inclus.",
                            prix: 320000,
                            categorie: "Accessoires",
                            image: "https://via.placeholder.com/300x200/764ba2/ffffff?text=Apple+Watch"
                        },
                        {
                            id: 5,
                            titre: "Appareil Photo",
                            description: "Canon EOS R5, plein format, 45MP. Vends avec objectif 24-70mm f/2.8.",
                            prix: 1500000,
                            categorie: "Photo",
                            image: "https://via.placeholder.com/300x200/667eea/ffffff?text=Canon+EOS+R5"
                        },
                        {
                            id: 6,
                            titre: "Téléphone Android",
                            description: "Samsung Galaxy S23 Ultra, 512GB, couleur Phantom Black. S-pen inclus.",
                            prix: 750000,
                            categorie: "Électronique",
                            image: "https://via.placeholder.com/300x200/764ba2/ffffff?text=Galaxy+S23"
                        }
                    ]
                });
            }, 1000);
        });
    }

    renderObjets() {
        const container = document.querySelector('.objets-grid');
        const objetsFiltres = this.getObjetsFiltres();
        
        if (objetsFiltres.length === 0) {
            container.innerHTML = this.getNoObjetsHTML();
            return;
        }

        container.innerHTML = objetsFiltres.map((objet, index) => 
            this.getObjetCardHTML(objet, index)
        ).join('');
    }

    getObjetsFiltres() {
        if (this.filtreActif === 'tous') {
            return this.objets;
        }
        return this.objets.filter(objet => objet.categorie === this.filtreActif);
    }

    getObjetCardHTML(objet, index) {
        return `
            <div class="objet-card" style="animation-delay: ${index * 0.1}s">
                <div class="objet-image">
                    <img src="${objet.image}" alt="${objet.titre}" onerror="this.src='https://via.placeholder.com/300x200/cccccc/666666?text=Image+non+disponible'">
                    <span class="objet-badge">${objet.categorie}</span>
                </div>
                <div class="objet-content">
                    <h3 class="objet-titre">${this.escapeHtml(objet.titre)}</h3>
                    <p class="objet-description">${this.escapeHtml(objet.description)}</p>
                    <div class="objet-footer">
                        <div class="objet-prix">
                            ${this.formatPrix(objet.prix)}
                            <span>Ar</span>
                        </div>
                        <button class="btn-echanger" data-objet-id="${objet.id}">
                            <i class="bi bi-arrow-repeat"></i>
                            Échanger
                        </button>
                    </div>
                </div>
            </div>
        `;
    }

    getNoObjetsHTML() {
        return `
            <div class="no-objets">
                <i class="bi bi-inbox"></i>
                <h3>Aucun objet trouvé</h3>
                <p>Essayez de modifier vos filtres ou revenez plus tard</p>
            </div>
        `;
    }

    setFiltre(categorie) {
        this.filtreActif = categorie;
        
        // Mettre à jour l'apparence des boutons
        document.querySelectorAll('.filtre-btn').forEach(btn => {
            btn.classList.toggle('active', btn.dataset.categorie === categorie);
        });
        
        // Re-render les objets
        this.renderObjets();
    }

    handleEchange(objetId) {
        const objet = this.objets.find(o => o.id == objetId);
        if (!objet) return;

        // Afficher une confirmation
        if (confirm(`Voulez-vous vraiment proposer un échange pour "${objet.titre}" ?`)) {
            this.proposerEchange(objet);
        }
    }

    proposerEchange(objet) {
        // Animation du bouton
        const btn = document.querySelector(`[data-objet-id="${objet.id}"]`);
        if (btn) {
            btn.innerHTML = '<i class="bi bi-check-circle"></i> Proposition envoyée';
            btn.style.background = 'linear-gradient(135deg, #28a745, #20c997)';
            btn.disabled = true;
        }

        // Simuler l'envoi de la proposition
        setTimeout(() => {
            this.showNotification(`Proposition d'échange envoyée pour "${objet.titre}"`, 'success');
            
            // Réinitialiser le bouton après 3 secondes
            if (btn) {
                setTimeout(() => {
                    btn.innerHTML = '<i class="bi bi-arrow-repeat"></i> Échanger';
                    btn.style.background = '';
                    btn.disabled = false;
                }, 3000);
            }
        }, 1000);
    }

    handleScroll() {
        // Implémentation du scroll infini (optionnel)
        const scrollPosition = window.innerHeight + window.scrollY;
        const documentHeight = document.documentElement.offsetHeight;
        
        if (scrollPosition >= documentHeight - 100) {
            // Charger plus d'objets si nécessaire
            // this.loadMoreObjets();
        }
    }

    showLoading(show) {
        const spinner = document.querySelector('.loading-spinner');
        if (spinner) {
            spinner.classList.toggle('active', show);
        }
    }

    showError(message) {
        this.showNotification(message, 'error');
    }

    showNotification(message, type = 'info') {
        // Créer une notification temporaire
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        notification.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 1rem 1.5rem;
            background: ${type === 'success' ? '#28a745' : type === 'error' ? '#dc3545' : '#17a2b8'};
            color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            z-index: 1000;
            animation: slideInRight 0.3s ease;
        `;

        document.body.appendChild(notification);

        // Supprimer après 3 secondes
        setTimeout(() => {
            notification.style.animation = 'slideOutRight 0.3s ease';
            setTimeout(() => {
                document.body.removeChild(notification);
            }, 300);
        }, 3000);
    }

    formatPrix(prix) {
        return new Intl.NumberFormat('fr-FR').format(prix);
    }

    escapeHtml(text) {
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
    }
}

// Ajouter les animations CSS nécessaires
const style = document.createElement('style');
style.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(100%);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(100%);
            opacity: 0;
        }
    }
`;
document.head.appendChild(style);

// Initialiser l'application quand le DOM est chargé
document.addEventListener('DOMContentLoaded', () => {
    new ObjetsCardsManager();
});

// Exporter pour utilisation externe si nécessaire
window.ObjetsCardsManager = ObjetsCardsManager;
