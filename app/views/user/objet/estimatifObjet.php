<?php
// Page d'affichage des cartes d'objets avec prix et bouton d'échange
// Variables PHP pour les données (peuvent être alimentées par le backend)
$pageTitle = "Estimation des Objets - Takalo";
$objets = $objets ?? []; // Sera alimenté par le contrôleur
$categories = $categories ?? ['tous', 'Électronique', 'Informatique', 'Gaming', 'Accessoires', 'Photo'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($pageTitle); ?></title>
    
    <!-- Bootstrap CSS local -->
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/bootstrap-icons/font/bootstrap-icons.min.css">
    
    <!-- CSS personnalisé -->
    <link rel="stylesheet" href="/css/objets-cards.css">
    
    <!-- Meta tags pour le SEO -->
    <meta name="description" content="Découvrez et échangez des objets sur Takalo. Trouvez le meilleur prix pour vos échanges.">
    <meta name="keywords" content="échange, objets, prix, takalo, troc">
    <meta name="author" content="Takalo">
    
    <!-- Open Graph -->
    <meta property="og:title" content="<?php echo htmlspecialchars($pageTitle); ?>">
    <meta property="og:description" content="Découvrez et échangez des objets sur Takalo">
    <meta property="og:type" content="website">
    <meta property="og:image" content="/images/takalo-og-image.jpg">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold text-primary" href="/">
                <i class="bi bi-box-seam"></i> Takalo
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/objet">
                            <i class="bi bi-bag"></i> Mes Objets
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="/objet/estimatif">
                            <i class="bi bi-calculator"></i> Estimation
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/objet/propositions">
                            <i class="bi bi-inbox"></i> Propositions
                        </a>
                    </li>
                </ul>
                
                <div class="d-flex gap-2">
                    <a href="/objet/create" class="btn btn-primary btn-sm">
                        <i class="bi bi-plus-lg"></i> Ajouter
                    </a>
                    <a href="/logout" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Déconnexion
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Contenu principal -->
    <main class="container-objets">
        <!-- En-tête -->
        <section class="header-section">
            <h1><i class="bi bi-calculator"></i> Estimation des Objets</h1>
            <p>Découvrez la valeur de nos objets disponibles pour l'échange</p>
        </section>

        <!-- Filtres -->
        <section class="filtre-section">
            <h3><i class="bi bi-funnel"></i> Filtrer par catégorie</h3>
            <div class="filtre-boutons">
                <?php foreach ($categories as $categorie): ?>
                    <button class="filtre-btn <?php echo $categorie === 'tous' ? 'active' : ''; ?>" 
                            data-categorie="<?php echo htmlspecialchars($categorie); ?>">
                        <?php 
                        $icon = match($categorie) {
                            'tous' => 'bi-grid-3x3-gap',
                            'Électronique' => 'bi-phone',
                            'Informatique' => 'bi-laptop',
                            'Gaming' => 'bi-controller',
                            'Accessoires' => 'bi-watch',
                            'Photo' => 'bi-camera',
                            default => 'bi-tag'
                        };
                        ?>
                        <i class="bi <?php echo $icon; ?>"></i>
                        <?php echo htmlspecialchars($categorie); ?>
                    </button>
                <?php endforeach; ?>
            </div>
        </section>

        <!-- Messages d'alerte -->
        <?php if (!empty($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle"></i> <?php echo htmlspecialchars($success); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle"></i> <?php echo htmlspecialchars($error); ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>

        <!-- Loading spinner -->
        <div class="loading-spinner">
            <div class="spinner"></div>
            <p class="mt-3">Chargement des objets...</p>
        </div>

        <!-- Grille des objets -->
        <section class="objets-grid">
            <!-- Les objets seront chargés dynamiquement par JavaScript -->
        </section>

        <!-- Statistiques (optionnel) -->
        <section class="stats-section" style="display: none;">
            <div class="row text-center text-white">
                <div class="col-md-4">
                    <div class="stat-item">
                        <h3 id="total-objets">0</h3>
                        <p>Objets disponibles</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-item">
                        <h3 id="prix-moyen">0 Ar</h3>
                        <p>Prix moyen</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-item">
                        <h3 id="echanges-total">0</h3>
                        <p>Échanges effectués</p>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="text-center text-muted small py-4 mt-5 border-top bg-white">
        <div class="container">
            <p>&copy; 2026 Takalo | Échangez librement vos objets</p>
            <div class="mt-2">
                <a href="/a-propos" class="text-muted me-3">À propos</a>
                <a href="/contact" class="text-muted me-3">Contact</a>
                <a href="/mentions-legales" class="text-muted">Mentions légales</a>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS local -->
    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
    
    <!-- JavaScript personnalisé -->
    <script src="/js/objets-cards.js"></script>

    <!-- Script pour passer les données PHP à JavaScript -->
    <script>
        // Données depuis PHP vers JavaScript
        window.objetsData = <?php echo json_encode($objets); ?>;
        window.categoriesData = <?php echo json_encode($categories); ?>;
        window.currentUser = <?php echo json_encode($currentUser ?? null); ?>;
        
        // Configuration de l'API
        window.apiConfig = {
            baseUrl: '/api',
            endpoints: {
                objets: '/objets',
                echange: '/echanger',
                categories: '/categories'
            }
        };
    </script>

    <!-- Service Worker pour PWA (optionnel) -->
    <script>
        if ('serviceWorker' in navigator) {
            // window.addEventListener('load', () => {
            //     navigator.serviceWorker.register('/sw.js');
            // });
        }
    </script>
</body>
</html>

<?php
// Fonctions utilitaires (peuvent être déplacées dans un fichier séparé)
if (!function_exists('formatPrix')) {
    function formatPrix($prix) {
        return number_format((float)$prix, 0, ',', ' ') . ' Ar';
    }
}

if (!function_exists('getObjetImage')) {
    function getObjetImage($objet) {
        // Logique pour récupérer l'image principale d'un objet
        if (!empty($objet['images'])) {
            return $objet['images'][0]['url'] ?? '/images/default-object.jpg';
        }
        return '/images/default-object.jpg';
    }
}

if (!function_exists('calculerPrixEstime')) {
    function calculerPrixEstime($objet) {
        // Logique de calcul du prix estimé basé sur différents facteurs
        $prixBase = $objet['prix'] ?? 0;
        $categorie = $objet['categorie'] ?? '';
        $etat = $objet['etat'] ?? 'bon';
        
        // Ajustements selon la catégorie
        $multiplicateurs = [
            'Électronique' => 0.8,
            'Informatique' => 0.7,
            'Gaming' => 0.85,
            'Accessoires' => 0.9,
            'Photo' => 0.75
        ];
        
        $multiplicateur = $multiplicateurs[$categorie] ?? 0.8;
        
        // Ajustements selon l'état
        $etatMultiplicateurs = [
            'neuf' => 1.0,
            'excellent' => 0.9,
            'bon' => 0.8,
            'correct' => 0.6,
            'usé' => 0.4
        ];
        
        $etatMultiplicateur = $etatMultiplicateurs[$etat] ?? 0.8;
        
        return round($prixBase * $multiplicateur * $etatMultiplicateur);
    }
}
?>