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
            <div>
                <h1 class="h2 mb-0 fw-bold">Vos objets</h1>
                <div class="text-muted small">Gérez vos objets (ajouter, modifier, supprimer)</div>
            </div>
            <a class="btn btn-primary" href="#">
                <i class="bi bi-plus-circle me-2"></i>Ajouter un objet
            </a>
        </div>

        <!-- Grille de cards d'objets -->
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            <?php if (empty($objets)) : ?>
                <div class="col-12">
                    <div class="alert alert-info mb-0" role="alert">
                        Aucun objet pour le moment.
                    </div>
                </div>
            <?php else : ?>
                <?php foreach ($objets as $objet) : ?>
                    <div class="col">
                        <div class="card objet-card h-100 shadow-sm position-relative">
                            <img src="https://via.placeholder.com/600x400/f1f3f5/495057?text=Objet" class="card-img-top" alt="<?= htmlspecialchars((string)($objet['titre'] ?? 'Objet'), ENT_QUOTES, 'UTF-8') ?>">
                            <div class="card-body d-flex flex-column">
                                <div class="dropdown objet-card-menu">
                                    <button class="btn btn-sm btn-light" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-eye me-2"></i>Voir</a></li>
                                        <li><a class="dropdown-item" href="#"><i class="bi bi-pencil-square me-2"></i>Modifier</a></li>
                                        <li><hr class="dropdown-divider"></li>
                                        <li><a class="dropdown-item text-danger" href="#"><i class="bi bi-trash3 me-2"></i>Supprimer</a></li>
                                    </ul>
                                </div>

                                <h5 class="card-title"><?= htmlspecialchars((string)($objet['titre'] ?? ''), ENT_QUOTES, 'UTF-8') ?></h5>

                                <?php if (!empty($objet['detail'])) : ?>
                                    <div class="text-muted small mb-2"><?= htmlspecialchars((string)$objet['detail'], ENT_QUOTES, 'UTF-8') ?></div>
                                <?php endif; ?>

                                <p class="card-text text-muted flex-grow-1"><?= htmlspecialchars((string)($objet['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></p>

                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <span class="badge bg-primary"><?= htmlspecialchars((string)($objet['categorie'] ?? 'Catégorie'), ENT_QUOTES, 'UTF-8') ?></span>
                                    <span class="badge bg-light text-dark">
                                        <?= number_format((float)($objet['prix'] ?? 0), 2, '.', ' ') ?> Ar
                                    </span>
                                </div>

                                <div class="d-grid gap-2">
                                    <a class="btn btn-outline-secondary" href="#"><i class="bi bi-pencil-square me-2"></i>Modifier</a>
                                    <button class="btn btn-outline-danger" type="button"><i class="bi bi-trash3 me-2"></i>Supprimer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>

    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
