<?php
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Proposer un échange</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <p class="lead">Sélectionnez un de vos objets à échanger :</p>
                    
                    <form action="/objet/<?= $idObjetTarget ?>/propose" method="post" class="needs-validation" novalidate>
                        <div class="mb-4">
                            <?php if (empty($myObjets)): ?>
                                <div class="alert alert-warning">
                                    Vous n'avez aucun objet à échanger. <a href="/objet/create" class="alert-link">Ajoutez un objet</a> d'abord.
                                </div>
                            <?php else: ?>
                                <div class="row">
                                    <?php foreach ($myObjets as $objet): ?>
                                        <div class="col-md-6 mb-3">
                                            <div class="card h-100">
                                                <div class="card-body">
                                                    <div class="form-check">
                                                        <input class="form-check-input" type="radio" name="idObjet" 
                                                               id="objet-<?= $objet['id'] ?>" value="<?= $objet['id'] ?>" required>
                                                        <label class="form-check-label d-block" for="objet-<?= $objet['id'] ?>">
                                                            <h5 class="card-title"><?= htmlspecialchars($objet['titre']) ?></h5>
                                                            <?php if (!empty($objet['images'])): ?>
                                                                <img src="/data/<?= htmlspecialchars($objet['images'][0]['nomFichier']) ?>" 
                                                                     class="img-fluid mb-2" alt="<?= htmlspecialchars($objet['titre']) ?>" 
                                                                     style="max-height: 150px; width: auto;">
                                                            <?php endif; ?>
                                                            <p class="card-text">
                                                                <small class="text-muted">
                                                                    <?= htmlspecialchars($objet['categorie'] ?? 'Sans catégorie') ?> • 
                                                                    <?= number_format($objet['prix'], 0, ',', ' ') ?> Ar
                                                                </small>
                                                            </p>
                                                            <p class="card-text"><?= nl2br(htmlspecialchars(mb_substr($objet['description'], 0, 100) . (mb_strlen($objet['description']) > 100 ? '...' : ''))) ?></p>
                                                        </label>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="/objet/<?= $idObjetTarget ?>" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left"></i> Retour
                            </a>
                            <?php if (!empty($myObjets)): ?>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-exchange-alt"></i> Proposer l'échange
                                </button>
                            <?php endif; ?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
(function() {
    'use strict';
    window.addEventListener('load', function() {
        var forms = document.getElementsByClassName('needs-validation');
        var validation = Array.prototype.filter.call(forms, function(form) {
            form.addEventListener('submit', function(event) {
                if (form.checkValidity() === false) {
                    event.preventDefault();
                    event.stopPropagation();
                }
                form.classList.add('was-validated');
            }, false);
        });
    }, false);
})();
</script>