<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Créer un objet</title>
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/bootstrap-icons/font/bootstrap-icons.min.css">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h1 class="h3 mb-0 fw-bold">Créer un objet</h1>
            <div class="text-muted small">Ajoutez un objet et ses images</div>
        </div>
        <a class="btn btn-outline-secondary" href="/objet">
            <i class="bi bi-arrow-left me-2"></i>Retour
        </a>
    </div>

    <?php if (!empty($error)) : ?>
        <div class="alert alert-danger" role="alert">
            <?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?>
        </div>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-12 col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <form action="/objet/create" method="post" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Titre</label>
                                <input type="text" name="titre" class="form-control" required value="<?= htmlspecialchars((string)($old['titre'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label">Prix (Ar)</label>
                                <input type="number" step="0.01" min="0" name="prix" class="form-control" value="<?= htmlspecialchars((string)($old['prix'] ?? '0'), ENT_QUOTES, 'UTF-8') ?>">
                            </div>

                            <div class="col-12 col-md-8">
                                <label class="form-label">Catégorie</label>
                                <select name="idCateg" class="form-select" required>
                                    <option value="">-- Choisir --</option>
                                    <?php foreach (($categories ?? []) as $c) : ?>
                                        <?php $selected = ((int)($old['idCateg'] ?? 0) === (int)($c['id'] ?? 0)) ? 'selected' : ''; ?>
                                        <option value="<?= (int)($c['id'] ?? 0) ?>" <?= $selected ?>>
                                            <?= htmlspecialchars((string)($c['libele'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars((string)($old['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Images (plusieurs)</label>
                                <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                                <div class="form-text">Formats acceptés: jpg, jpeg, png, webp, gif.</div>
                            </div>

                            <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                                <a class="btn btn-light" href="/objet">Annuler</a>
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-check2-circle me-2"></i>Créer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
