<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Échange d'Objets - Takalo</title>
        <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/bootstrap-icons/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/style/objet.css">
</head>
<body class="bg-light">
    <div class="container py-4">
        <!-- Header avec menu -->
        <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
            <h1 class="h2 mb-0 fw-bold">Objets Disponibles</h1>
        </div>

        <!-- Grille de cards d'objets -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <!-- Card 1 -->
            <div class="col">
                <div class="card objet-card h-100 shadow-sm position-relative">
                    <img src="https://via.placeholder.com/300x200/4A90E2/ffffff?text=Vélo" class="card-img-top" alt="Vélo">
                    <div class="card-body d-flex flex-column">
                        <div class="dropdown objet-card-menu">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Voir détails</a></li>
                                <li><a class="dropdown-item" href="#">Historique</a></li>
                            </ul>
                        </div>
                        <h5 class="card-title">Vélo de ville</h5>
                        <p class="card-text text-muted flex-grow-1">Vélo en excellent état, parfait pour les trajets quotidiens en ville.</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary">Transport</span>
                            <span class="badge bg-success">Disponible</span>
                        </div>
                        <button class="btn btn-primary w-100">Proposer un échange</button>
                    </div>
                </div>
            </div>

            <!-- Card 2 -->
            <div class="col">
                <div class="card objet-card h-100 shadow-sm position-relative">
                    <img src="https://via.placeholder.com/300x200/E74C3C/ffffff?text=Livre" class="card-img-top" alt="Livre">
                    <div class="card-body d-flex flex-column">
                        <div class="dropdown objet-card-menu">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Voir détails</a></li>
                                <li><a class="dropdown-item" href="#">Historique</a></li>
                            </ul>
                        </div>
                        <h5 class="card-title">Collection de livres</h5>
                        <p class="card-text text-muted flex-grow-1">Romans classiques et contemporains, bien conservés.</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary">Livres</span>
                            <span class="badge bg-success">Disponible</span>
                        </div>
                        <button class="btn btn-primary w-100">Proposer un échange</button>
                    </div>
                </div>
            </div>

            <!-- Card 3 -->
            <div class="col">
                <div class="card objet-card h-100 shadow-sm position-relative">
                    <img src="https://via.placeholder.com/300x200/27AE60/ffffff?text=Meuble" class="card-img-top" alt="Meuble">
                    <div class="card-body d-flex flex-column">
                        <div class="dropdown objet-card-menu">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Voir détails</a></li>
                                <li><a class="dropdown-item" href="#">Historique</a></li>
                            </ul>
                        </div>
                        <h5 class="card-title">Table basse en bois</h5>
                        <p class="card-text text-muted flex-grow-1">Table basse artisanale en bois massif, design moderne.</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary">Mobilier</span>
                            <span class="badge bg-warning text-dark">Réservé</span>
                        </div>
                        <button class="btn btn-primary w-100" disabled>Proposer un échange</button>
                    </div>
                </div>
            </div>

            <!-- Card 4 -->
            <div class="col">
                <div class="card objet-card h-100 shadow-sm position-relative">
                    <img src="https://via.placeholder.com/300x200/F39C12/ffffff?text=Électronique" class="card-img-top" alt="Électronique">
                    <div class="card-body d-flex flex-column">
                        <div class="dropdown objet-card-menu">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Voir détails</a></li>
                                <li><a class="dropdown-item" href="#">Historique</a></li>
                            </ul>
                        </div>
                        <h5 class="card-title">Console de jeux</h5>
                        <p class="card-text text-muted flex-grow-1">Console en bon état avec plusieurs jeux inclus.</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary">Électronique</span>
                            <span class="badge bg-success">Disponible</span>
                        </div>
                        <button class="btn btn-primary w-100">Proposer un échange</button>
                    </div>
                </div>
            </div>

            <!-- Card 5 -->
            <div class="col">
                <div class="card objet-card h-100 shadow-sm position-relative">
                    <img src="https://via.placeholder.com/300x200/9B59B6/ffffff?text=Vêtements" class="card-img-top" alt="Vêtements">
                    <div class="card-body d-flex flex-column">
                        <div class="dropdown objet-card-menu">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Voir détails</a></li>
                                <li><a class="dropdown-item" href="#">Historique</a></li>
                            </ul>
                        </div>
                        <h5 class="card-title">Vêtements d'hiver</h5>
                        <p class="card-text text-muted flex-grow-1">Manteau, écharpe et gants, taille M, comme neufs.</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary">Vêtements</span>
                            <span class="badge bg-success">Disponible</span>
                        </div>
                        <button class="btn btn-primary w-100">Proposer un échange</button>
                    </div>
                </div>
            </div>

            <!-- Card 6 -->
            <div class="col">
                <div class="card objet-card h-100 shadow-sm position-relative">
                    <img src="https://via.placeholder.com/300x200/1ABC9C/ffffff?text=Outils" class="card-img-top" alt="Outils">
                    <div class="card-body d-flex flex-column">
                        <div class="dropdown objet-card-menu">
                            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="bi bi-three-dots-vertical"></i>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Voir détails</a></li>
                                <li><a class="dropdown-item" href="#">Historique</a></li>
                            </ul>
                        </div>
                        <h5 class="card-title">Boîte à outils complète</h5>
                        <p class="card-text text-muted flex-grow-1">Ensemble d'outils pour bricolage et réparations diverses.</p>
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span class="badge bg-primary">Outils</span>
                            <span class="badge bg-success">Disponible</span>
                        </div>
                        <button class="btn btn-primary w-100">Proposer un échange</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
