<!doctype html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Statistiques</title>
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/bootstrap-icons/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/statistiques.css">
</head>
<body>

<div class="container py-4 py-lg-5">
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="bi bi-house-door"></i> Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Statistiques</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-gradient rounded-3 p-3 me-3">
                    <i class="bi bi-graph-up-arrow text-white fs-4"></i>
                </div>
                <div>
                    <h1 class="h3 mb-1 fw-bold text-dark">Tableau de Bord</h1>
                    <p class="text-muted mb-0">Statistiques et indicateurs clés de la plateforme</p>
                </div>
            </div>
        </div>
        <button id="refreshBtn" class="btn btn-outline-primary btn-lg shadow-sm">
            <i class="bi bi-arrow-clockwise me-2"></i>Actualiser
        </button>
    </div>

    <!-- Stats Cards -->
    <div class="row g-4 mb-4">
        <!-- Users Card -->
        <div class="col-md-6">
            <div class="card stat-card border-0 h-100" id="usersCard">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-uppercase small fw-bold text-muted mb-2">Utilisateurs Inscrits</h6>
                            <h2 class="mb-0 fw-bold display-5 stat-number" data-target="0" id="totalUsers">0</h2>
                            <p class="text-success mb-0 mt-2 small">
                                <i class="bi bi-arrow-up-short"></i>
                                <span id="usersGrowth">+0%</span> ce mois
                            </p>
                        </div>
                        <div class="stat-icon bg-primary bg-opacity-10 text-primary">
                            <i class="bi bi-people-fill fs-2"></i>
                        </div>
                    </div>
                    <div class="progress mt-4" style="height: 6px;">
                        <div class="progress-bar bg-primary" id="usersProgress" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exchanges Card -->
        <div class="col-md-6">
            <div class="card stat-card border-0 h-100" id="exchangesCard">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start">
                        <div>
                            <h6 class="text-uppercase small fw-bold text-muted mb-2">Échanges Effectués</h6>
                            <h2 class="mb-0 fw-bold display-5 stat-number" data-target="0" id="totalExchanges">0</h2>
                            <p class="text-success mb-0 mt-2 small">
                                <i class="bi bi-arrow-up-short"></i>
                                <span id="exchangesGrowth">+0%</span> ce mois
                            </p>
                        </div>
                        <div class="stat-icon bg-success bg-opacity-10 text-success">
                            <i class="bi bi-arrow-left-right fs-2"></i>
                        </div>
                    </div>
                    <div class="progress mt-4" style="height: 6px;">
                        <div class="progress-bar bg-success" id="exchangesProgress" style="width: 0%"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-bar-chart-line me-2 text-primary"></i>Évolution des Activités</h5>
                </div>
                <div class="card-body">
                    <canvas id="activityChart" height="300"></canvas>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 py-3">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-pie-chart me-2 text-success"></i>Répartition</h5>
                </div>
                <div class="card-body">
                    <canvas id="distributionChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4">
        <div class="col-md-4">
            <div class="card border-0 bg-light h-100">
                <div class="card-body text-center p-4">
                    <div class="mini-stat-icon bg-info bg-opacity-10 text-info mb-3 mx-auto">
                        <i class="bi bi-calendar-check fs-4"></i>
                    </div>
                    <h5 class="fw-bold mb-1" id="todayUsers">0</h5>
                    <p class="text-muted small mb-0">Nouveaux aujourd'hui</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-light h-100">
                <div class="card-body text-center p-4">
                    <div class="mini-stat-icon bg-warning bg-opacity-10 text-warning mb-3 mx-auto">
                        <i class="bi bi-hourglass-split fs-4"></i>
                    </div>
                    <h5 class="fw-bold mb-1" id="pendingExchanges">0</h5>
                    <p class="text-muted small mb-0">Échanges en attente</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-light h-100">
                <div class="card-body text-center p-4">
                    <div class="mini-stat-icon bg-danger bg-opacity-10 text-danger mb-3 mx-auto">
                        <i class="bi bi-check2-circle fs-4"></i>
                    </div>
                    <h5 class="fw-bold mb-1" id="completedExchanges">0</h5>
                    <p class="text-muted small mb-0">Échanges complétés</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="text-center mt-4">
        <a href="/admin" class="btn btn-outline-secondary px-4">
            <i class="bi bi-arrow-left me-2"></i> Retour au Tableau de Bord
        </a>
    </div>
</div>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="/assets/js/statistiques.js"></script>
</body>
</html>
