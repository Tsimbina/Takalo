// Statistiques Admin - Frontend

// Données mock pour démonstration (à remplacer par appels API)
const mockData = {
    totalUsers: 1247,
    totalExchanges: 856,
    usersGrowth: 12.5,
    exchangesGrowth: 8.3,
    todayUsers: 23,
    pendingExchanges: 45,
    completedExchanges: 342,
    monthlyUsers: [65, 78, 90, 81, 96, 105, 120, 135, 150, 165, 180, 200],
    monthlyExchanges: [30, 45, 60, 55, 70, 85, 90, 100, 110, 120, 130, 140]
};

// Configuration des mois
const months = ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin', 'Juil', 'Août', 'Sep', 'Oct', 'Nov', 'Déc'];

// Animation des nombres
function animateNumber(element, target, duration = 1500) {
    const start = 0;
    const startTime = performance.now();
    
    function updateNumber(currentTime) {
        const elapsed = currentTime - startTime;
        const progress = Math.min(elapsed / duration, 1);
        
        // Easing ease-out
        const easeOut = 1 - Math.pow(1 - progress, 3);
        const current = Math.floor(start + (target - start) * easeOut);
        
        element.textContent = current.toLocaleString('fr-FR');
        
        if (progress < 1) {
            requestAnimationFrame(updateNumber);
        } else {
            element.textContent = target.toLocaleString('fr-FR');
        }
    }
    
    requestAnimationFrame(updateNumber);
}

// Mise à jour des statistiques
function updateStats() {
    // Animation des cartes principales
    animateNumber(document.getElementById('totalUsers'), mockData.totalUsers);
    animateNumber(document.getElementById('totalExchanges'), mockData.totalExchanges);
    
    // Mise à jour des pourcentages
    document.getElementById('usersGrowth').textContent = `+${mockData.usersGrowth}%`;
    document.getElementById('exchangesGrowth').textContent = `+${mockData.exchangesGrowth}%`;
    
    // Animation des progress bars
    setTimeout(() => {
        document.getElementById('usersProgress').style.width = '75%';
        document.getElementById('exchangesProgress').style.width = '60%';
    }, 300);
    
    // Mini stats
    animateNumber(document.getElementById('todayUsers'), mockData.todayUsers, 1000);
    animateNumber(document.getElementById('pendingExchanges'), mockData.pendingExchanges, 1000);
    animateNumber(document.getElementById('completedExchanges'), mockData.completedExchanges, 1000);
}

// Création du graphique d'activité (barres)
function createActivityChart() {
    const ctx = document.getElementById('activityChart').getContext('2d');
    
    return new Chart(ctx, {
        type: 'bar',
        data: {
            labels: months,
            datasets: [
                {
                    label: 'Nouveaux Utilisateurs',
                    data: mockData.monthlyUsers,
                    backgroundColor: 'rgba(13, 110, 253, 0.8)',
                    borderColor: '#0d6efd',
                    borderWidth: 0,
                    borderRadius: 4,
                    borderSkipped: false
                },
                {
                    label: 'Échanges Effectués',
                    data: mockData.monthlyExchanges,
                    backgroundColor: 'rgba(25, 135, 84, 0.8)',
                    borderColor: '#198754',
                    borderWidth: 0,
                    borderRadius: 4,
                    borderSkipped: false
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'top',
                    labels: {
                        usePointStyle: true,
                        padding: 20,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    titleFont: {
                        size: 14
                    },
                    bodyFont: {
                        size: 13
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)',
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        },
                        color: '#6c757d'
                    }
                },
                x: {
                    grid: {
                        display: false,
                        drawBorder: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        },
                        color: '#6c757d'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
}

// Création du graphique de répartition (doughnut)
function createDistributionChart() {
    const ctx = document.getElementById('distributionChart').getContext('2d');
    
    return new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: ['Échanges Complétés', 'Échanges en Attente', 'Échanges Refusés'],
            datasets: [{
                data: [342, 45, 12],
                backgroundColor: [
                    'rgba(25, 135, 84, 0.8)',   // Success
                    'rgba(255, 193, 7, 0.8)',   // Warning
                    'rgba(220, 53, 69, 0.8)'    // Danger
                ],
                borderColor: [
                    '#198754',
                    '#ffc107',
                    '#dc3545'
                ],
                borderWidth: 2,
                hoverOffset: 4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        usePointStyle: true,
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    cornerRadius: 8,
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

// Simulation de récupération des données (à remplacer par vrai appel API)
async function fetchStatsData() {
    // Simuler un délai réseau
    await new Promise(resolve => setTimeout(resolve, 500));
    return mockData;
}

// Fonction de refresh
async function refreshStats() {
    const btn = document.getElementById('refreshBtn');
    const icon = btn.querySelector('i');
    
    // Animation du bouton
    btn.classList.add('spinning');
    btn.disabled = true;
    
    try {
        // Récupérer les données
        const data = await fetchStatsData();
        
        // Mettre à jour les valeurs mock
        mockData.totalUsers = data.totalUsers + Math.floor(Math.random() * 10);
        mockData.totalExchanges = data.totalExchanges + Math.floor(Math.random() * 5);
        
        // Réinitialiser les animations
        document.getElementById('usersProgress').style.width = '0%';
        document.getElementById('exchangesProgress').style.width = '0%';
        
        // Relancer les stats
        updateStats();
        
        // Mettre à jour les graphiques si nécessaire
        if (window.activityChart) {
            window.activityChart.update('active');
        }
        if (window.distributionChart) {
            window.distributionChart.update('active');
        }
        
    } catch (error) {
        console.error('Erreur lors du rafraîchissement:', error);
        alert('Erreur lors du rafraîchissement des statistiques');
    } finally {
        // Arrêter l'animation
        setTimeout(() => {
            btn.classList.remove('spinning');
            btn.disabled = false;
        }, 1000);
    }
}

// Initialisation
document.addEventListener('DOMContentLoaded', function() {
    // Lancer les animations
    updateStats();
    
    // Créer les graphiques
    window.activityChart = createActivityChart();
    window.distributionChart = createDistributionChart();
    
    // Event listener pour le bouton refresh
    document.getElementById('refreshBtn').addEventListener('click', refreshStats);
});
