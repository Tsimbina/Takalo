<!doctype html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Gestion des Catégories</title>
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/bootstrap-icons/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/table-admin.css">
</head>
<body>

<div class="container py-4 py-lg-5">
    <!-- Header -->
    <div class="d-flex align-items-center mb-4">
        <div class="flex-grow-1">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="bi bi-house-door"></i> Accueil</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Catégories</li>
                </ol>
            </nav>
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-gradient rounded-3 p-3 me-3">
                    <i class="bi bi-tags text-white fs-4"></i>
                </div>
                <div>
                    <h1 class="h3 mb-1 fw-bold text-dark">Gestion des Catégories</h1>
                    <p class="text-muted mb-0">Administrez et organisez vos catégories de produits</p>
                </div>
            </div>
        </div>
        <button id="btnAdd" class="btn btn-primary btn-lg shadow">
            <i class="bi bi-plus-circle me-2"></i>Nouvelle Catégorie
        </button>
    </div>

    <!-- Stats Card -->
    <div class="row mb-4">
        <div class="col-md-4">
            <div class="card border-0 bg-primary bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-primary text-uppercase small fw-bold">Total Catégories</h6>
                            <h3 class="mb-0 fw-bold" id="totalCount">3</h3>
                        </div>
                        <div class="bg-primary bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-collection text-primary fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-success bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-success text-uppercase small fw-bold">Actives</h6>
                            <h3 class="mb-0 fw-bold">3</h3>
                        </div>
                        <div class="bg-success bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-check-circle text-success fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-0 bg-warning bg-opacity-10">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-warning text-uppercase small fw-bold">Dernière modif.</h6>
                            <h6 class="mb-0 fw-bold">Aujourd'hui</h6>
                        </div>
                        <div class="bg-warning bg-opacity-25 p-3 rounded-circle">
                            <i class="bi bi-clock-history text-warning fs-4"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Card -->
    <div class="card shadow-lg border-0">
        <div class="card-header border-0">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h5 class="mb-0 text-white"><i class="bi bi-table me-2"></i>Liste des Catégories</h5>
                </div>
                <div class="d-flex gap-2">
                    <div class="input-group input-group-sm" style="width: 250px;">
                        <span class="input-group-text bg-white"><i class="bi bi-search"></i></span>
                        <input type="text" class="form-control" placeholder="Rechercher..." id="searchInput">
                    </div>
                    <button class="btn btn-sm btn-light">
                        <i class="bi bi-filter"></i>
                    </button>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="tableCategories">
                    <thead class="table-light">
                        <tr>
                            <th width="80" class="ps-4">ID</th>
                            <th>Libellé</th>
                            <th width="120">Statut</th>
                            <th width="180" class="text-end pe-4">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr data-id="1">
                            <td class="ps-4 fw-semibold text-primary">#001</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-cpu text-primary"></i>
                                    </div>
                                    <div>
                                        <span class="libele fw-medium">Électronique</span>
                                        <small class="d-block text-muted">12 produits</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 status-badge">
                                    <i class="bi bi-check-circle me-1"></i>Active
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-warning btn-edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr data-id="2">
                            <td class="ps-4 fw-semibold text-primary">#002</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-tshirt text-success"></i>
                                    </div>
                                    <div>
                                        <span class="libele fw-medium">Vêtements</span>
                                        <small class="d-block text-muted">24 produits</small>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 status-badge">
                                    <i class="bi bi-check-circle me-1"></i>Active
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-warning btn-edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        <tr data-id="3">
                            <td class="ps-4 fw-semibold text-primary">#003</td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                                        <i class="bi bi-watch text-warning"></i>
                                    </div>
                                    <div>
                                        <span class="libele fw-medium">Accessoires</span>
                                        <small class="d-block text-muted">8 produits</small>
                                    </div>
                                </td>
                            <td>
                                <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 status-badge">
                                    <i class="bi bi-check-circle me-1"></i>Active
                                </span>
                            </td>
                            <td class="text-end pe-4">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-warning btn-edit">
                                        <i class="bi bi-pencil-square"></i>
                                    </button>
                                    <button class="btn btn-outline-info">
                                        <i class="bi bi-eye"></i>
                                    </button>
                                    <button class="btn btn-outline-danger">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- Table Footer -->
            <div class="card-footer border-0 bg-light">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        <i class="bi bi-info-circle me-1"></i>
                        <span id="totalText">3 catégories au total</span>
                    </div>
                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-download"></i> Exporter
                        </button>
                        <button class="btn btn-sm btn-outline-secondary">
                            <i class="bi bi-printer"></i> Imprimer
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Back Button -->
    <div class="text-center mt-4">
        <a href="/" class="btn btn-outline-secondary px-4">
            <i class="bi bi-arrow-left me-2"></i> Retour au Tableau de Bord
        </a>
    </div>
</div>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="/assets/js/table-admin.js"></script>
</body>
</html>