<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Modifier un objet</title>
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/bootstrap-icons/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="/assets/css/objet/create.css">
</head>
<body class="bg-light">

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4 pb-3 border-bottom">
        <div>
            <h1 class="h3 mb-0 fw-bold">Modifier un objet</h1>
            <div class="text-muted small">Modifiez les informations de votre objet</div>
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
                    <form action="/objet/<?= (int)($objet['id'] ?? 0) ?>/edit" method="post" enctype="multipart/form-data">
                        <div class="row g-3">
                            <div class="col-12">
                                <label class="form-label">Titre</label>
                                <input type="text" name="titre" class="form-control" required value="<?= htmlspecialchars((string)($objet['titre'] ?? ''), ENT_QUOTES, 'UTF-8') ?>">
                            </div>

                            <div class="col-12 col-md-4">
                                <label class="form-label">Prix (Ar)</label>
                                <input type="number" step="0.01" min="0" name="prix" class="form-control" value="<?= htmlspecialchars((string)($objet['prix'] ?? '0'), ENT_QUOTES, 'UTF-8') ?>">
                            </div>

                            <div class="col-12 col-md-8">
                                <label class="form-label">Catégorie</label>
                                <select name="idCateg" class="form-select" required>
                                    <option value="">-- Choisir --</option>
                                    <?php foreach (($categories ?? []) as $c) : ?>
                                        <?php $selected = ((int)($objet['idCateg'] ?? 0) === (int)($c['id'] ?? 0)) ? 'selected' : ''; ?>
                                        <option value="<?= (int)($c['id'] ?? 0) ?>" <?= $selected ?>>
                                            <?= htmlspecialchars((string)($c['libele'] ?? ''), ENT_QUOTES, 'UTF-8') ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Description</label>
                                <textarea name="description" class="form-control" rows="4"><?= htmlspecialchars((string)($objet['description'] ?? ''), ENT_QUOTES, 'UTF-8') ?></textarea>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Images actuelles</label>
                                <?php if (!empty($images)) : ?>
                                    <div class="row g-2 mb-3">
                                        <?php foreach ($images as $img) : ?>
                                            <div class="col-6 col-md-4 col-lg-3">
                                                <div class="card">
                                                    <img src="/<?= htmlspecialchars((string)($img['image'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" class="card-img-top" alt="<?= htmlspecialchars((string)($img['alt'] ?? ''), ENT_QUOTES, 'UTF-8') ?>" style="height: 120px; object-fit: cover;">
                                                    <div class="card-body p-2">
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox" name="delete_images[]" value="<?= (int)($img['id'] ?? 0) ?>" id="delete_img_<?= (int)($img['id'] ?? 0) ?>">
                                                            <label class="form-check-label small" for="delete_img_<?= (int)($img['id'] ?? 0) ?>">
                                                                Supprimer
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                <?php else : ?>
                                    <p class="text-muted">Aucune image pour cet objet.</p>
                                <?php endif; ?>
                            </div>

                            <div class="col-12">
                                <label class="form-label">Ajouter de nouvelles images</label>
                                <div class="file-upload-container mb-3">
                                    <div class="drop-zone" id="dropZone">
                                        <div class="drop-zone__prompt">
                                            <i class="bi bi-cloud-arrow-up display-4 text-muted"></i>
                                            <div class="mt-2">Glissez-déposez vos images ici ou cliquez pour sélectionner</div>
                                            <div class="small text-muted">Formats acceptés: JPG, JPEG, PNG, WEBP, GIF (max 5 Mo par image)</div>
                                        </div>
                                        <input type="file" name="images[]" id="fileInput" class="drop-zone__input" multiple accept="image/*">
                                    </div>
                                    <div id="fileList" class="mt-3">
                                        <!-- Les aperçus des images seront ajoutés ici -->
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 d-flex justify-content-end gap-2 mt-2">
                                <a class="btn btn-light" href="/objet">Annuler</a>
                                <button class="btn btn-primary" type="submit">
                                    <i class="bi bi-check2-circle me-2"></i>Enregistrer les modifications
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
    <script src="/assets/js/objet/create.js"></script>
</body>
</html>
