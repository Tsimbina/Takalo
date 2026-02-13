<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rechercher des objets</title>
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/bootstrap-icons/font/bootstrap-icons.min.css">
    <style>
        body {
            background: radial-gradient(1200px circle at 10% 10%, rgba(13, 110, 253, .10), transparent 55%),
                        radial-gradient(900px circle at 90% 20%, rgba(111, 66, 193, .08), transparent 55%),
                        linear-gradient(180deg, #f8f9fa, #ffffff);
        }

        .search-card {
            border: 1px solid rgba(0,0,0,.06);
            border-radius: 1rem;
            background: rgba(255,255,255,.9);
        }

        .objet-card {
            border: 1px solid rgba(0,0,0,.06);
            border-radius: 1rem;
            overflow: hidden;
            transition: transform .15s ease, box-shadow .15s ease;
            background: #fff;
        }

        .objet-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 .75rem 1.5rem rgba(0,0,0,.08);
        }

        .thumb {
            height: 180px;
            background: #ffffff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-bottom: 1px solid rgba(0,0,0,.06);
        }

        .thumb i {
            font-size: 2rem;
            color: rgba(0,0,0,.35);
        }

        .pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .25rem .6rem;
            border-radius: 999px;
            background: rgba(13,110,253,.10);
            color: #0d6efd;
            font-weight: 600;
            font-size: .8rem;
        }
    </style>
</head>
<body>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1 fw-bold">Recherche d'objets</h1>
            <div class="text-muted small">Trouvez des objets à échanger (design uniquement)</div>
        </div>
        <a class="btn btn-outline-secondary" href="/objet">
            <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="card search-card shadow-sm mb-4">
        <div class="card-body p-3 p-md-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div class="d-flex align-items-center gap-2">
                    <div class="rounded-3 d-inline-flex align-items-center justify-content-center" style="width:44px;height:44px;background:rgba(13,110,253,.12);color:#0d6efd;">
                        <i class="bi bi-search fs-5"></i>
                    </div>
                    <div>
                        <div class="fw-semibold">Filtrer</div>
                        <div class="text-muted small">Mot-clé et catégorie</div>
                    </div>
                </div>
                <span class="pill"><i class="bi bi-lightning-charge"></i>Template</span>
            </div>

            <form>
                <div class="row g-2">
                    <div class="col-12 col-md-6">
                        <label class="form-label">Mot-clé</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-type"></i></span>
                            <input class="form-control" type="text" placeholder="Ex: iPhone, vélo, livre...">
                        </div>
                    </div>
                    <div class="col-12 col-md-4">
                        <label class="form-label">Catégorie</label>
                        <select class="form-select">
                            <option value="">Toutes</option>
                            <option>Électronique</option>
                            <option>Vêtements</option>
                            <option>Livres</option>
                            <option>Maison</option>
                            <option>Autres</option>
                        </select>
                    </div>
                    <div class="col-12 col-md-2 d-grid align-self-end">
                        <button class="btn btn-primary" type="button">
                            <i class="bi bi-search me-2"></i>Rechercher
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <div class="text-muted small">Objets trouvés</div>
        <div class="text-muted small">6 résultats (mock)</div>
    </div>

    <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
        <?php
            $objets = [
                ['titre' => 'Casque audio', 'categorie' => 'Électronique', 'etat' => 'Bon état', 'desc' => 'Son propre, fonctionne parfaitement.'],
                ['titre' => 'Vélo de ville', 'categorie' => 'Transport', 'etat' => 'Très bon état', 'desc' => 'Idéal pour les trajets quotidiens.'],
                ['titre' => 'Lot de livres', 'categorie' => 'Livres', 'etat' => 'Comme neuf', 'desc' => 'Romans et classiques, bien conservés.'],
                ['titre' => 'Veste hiver', 'categorie' => 'Vêtements', 'etat' => 'Neuf', 'desc' => 'Taille M, très chaude.'],
                ['titre' => 'Console de jeux', 'categorie' => 'Électronique', 'etat' => 'Bon état', 'desc' => 'Console avec manette, prête à échanger.'],
                ['titre' => 'Table basse', 'categorie' => 'Maison', 'etat' => 'Bon état', 'desc' => 'Bois massif, style moderne.'],
            ];
        ?>

        <?php foreach ($objets as $o) : ?>
            <div class="col">
                <div class="card objet-card h-100">
                    <div class="thumb"><i class="bi bi-image"></i></div>
                    <div class="card-body d-flex flex-column">
                        <div class="d-flex justify-content-between align-items-start gap-3 mb-2">
                            <div>
                                <h5 class="card-title mb-1"><?= htmlspecialchars((string)$o['titre'], ENT_QUOTES, 'UTF-8') ?></h5>
                                <div class="text-muted small">
                                    <i class="bi bi-shield-check me-1"></i><?= htmlspecialchars((string)$o['etat'], ENT_QUOTES, 'UTF-8') ?>
                                </div>
                            </div>
                            <span class="badge text-bg-primary align-self-start"><?= htmlspecialchars((string)$o['categorie'], ENT_QUOTES, 'UTF-8') ?></span>
                        </div>

                        <p class="card-text text-muted flex-grow-1 mb-3">
                            <?= htmlspecialchars((string)$o['desc'], ENT_QUOTES, 'UTF-8') ?>
                        </p>

                        <div class="d-grid gap-2">
                            <button class="btn btn-outline-primary" type="button">
                                <i class="bi bi-arrow-left-right me-2"></i>Proposer
                            </button>
                            <button class="btn btn-outline-secondary" type="button">
                                <i class="bi bi-eye me-2"></i>Détails
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="text-center text-muted small mt-4">
        Contenu template — aucune recherche DB n'est effectuée.
    </div>
</div>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
