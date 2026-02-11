<?php
?>

<div class="container mt-4">
    <div id="alert-container"></div>
    <div class="row">
        <div class="col-12">
            <h2 class="mb-4">Mes propositions d'échange</h2>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php endif; ?>
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <!-- Onglets pour basculer entre les propositions reçues et envoyées -->
            <ul class="nav nav-tabs mb-4" id="propositionTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="recues-tab" data-bs-toggle="tab" data-bs-target="#recues" type="button" role="tab">
                        Reçues
                        <?php if ($nbPropositionsRecues > 0): ?>
                            <span class="badge bg-danger"><?= $nbPropositionsRecues ?></span>
                        <?php endif; ?>
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="envoyees-tab" data-bs-toggle="tab" data-bs-target="#envoyees" type="button" role="tab">
                        Envoyées
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="propositionTabsContent">
                <!-- Onglet Propositions Reçues -->
                <div class="tab-pane fade show active" id="recues" role="tabpanel">
                    <?php if (empty($propositionsRecues)): ?>
                        <div class="alert alert-info">
                            Vous n'avez aucune proposition d'échange reçue pour le moment.
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($propositionsRecues as $proposition): ?>
                                <div class="col-md-6 mb-4" data-proposition-id="<?= $proposition['id'] ?>">
                                    <div class="card h-100 shadow-sm proposition-card">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Proposition #<?= $proposition['id'] ?></h5>
                                            <span class="badge bg-<?= $proposition['statut'] === 'En attente' ? 'warning' : ($proposition['statut'] === 'Accepté' ? 'success' : 'danger') ?>">
                                                <?= htmlspecialchars($proposition['statut']) ?>
                                            </span>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Votre objet :</h6>
                                                    <div class="card mb-3">
                                                        <div class="card-body">
                                                            <h5 class="card-title"><?= htmlspecialchars($proposition['objet2']['titre']) ?></h5>
                                                            <?php if (!empty($proposition['objet2']['images'])): ?>
                                                                <img src="/data/<?= htmlspecialchars($proposition['objet2']['images'][0]['nomFichier']) ?>" 
                                                                     class="img-fluid mb-2" alt="<?= htmlspecialchars($proposition['objet2']['titre']) ?>" 
                                                                     style="max-height: 150px; width: auto;">
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Objet proposé :</h6>
                                                    <div class="card mb-3">
                                                        <div class="card-body">
                                                            <h5 class="card-title"><?= htmlspecialchars($proposition['objet1']['titre']) ?></h5>
                                                            <?php if (!empty($proposition['objet1']['images'])): ?>
                                                                <img src="/data/<?= htmlspecialchars($proposition['objet1']['images'][0]['nomFichier']) ?>" 
                                                                     class="img-fluid mb-2" alt="<?= htmlspecialchars($proposition['objet1']['titre']) ?>" 
                                                                     style="max-height: 150px; width: auto;">
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-muted mb-2">
                                                <small>Proposé par : <?= htmlspecialchars($proposition['proposeur']['nom'] ?? 'Utilisateur inconnu') ?></small><br>
                                                <small>Date : <?= date('d/m/Y H:i', strtotime($proposition['dateEchange'])) ?></small>
                                            </p>
                                        </div>
                                        <?php if ($proposition['statut'] === 'En attente'): ?>
                                            <div class="card-footer bg-transparent d-flex justify-content-between">
                                                <form action="/echange/<?= $proposition['id'] ?>/accepter" method="post" class="d-inline proposition-form" data-action="accepter" data-proposition-id="<?= $proposition['id'] ?>">
                                                    <button type="submit" class="btn btn-success btn-sm action-btn" data-original-text="<i class='fas fa-check'></i> Accepter">
                                                        <i class="fas fa-check"></i> Accepter
                                                    </button>
                                                </form>
                                                <form action="/echange/<?= $proposition['id'] ?>/refuser" method="post" class="d-inline proposition-form" data-action="refuser" data-proposition-id="<?= $proposition['id'] ?>">
                                                    <button type="submit" class="btn btn-danger btn-sm action-btn" data-original-text="<i class='fas fa-times'></i> Refuser">
                                                        <i class="fas fa-times"></i> Refuser
                                                    </button>
                                                </form>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>

                <!-- Onglet Propositions Envoyées -->
                <div class="tab-pane fade" id="envoyees" role="tabpanel">
                    <?php if (empty($propositionsEnvoyees)): ?>
                        <div class="alert alert-info">
                            Vous n'avez envoyé aucune proposition d'échange pour le moment.
                        </div>
                    <?php else: ?>
                        <div class="row">
                            <?php foreach ($propositionsEnvoyees as $proposition): ?>
                                <div class="col-md-6 mb-4">
                                    <div class="card h-100 shadow-sm">
                                        <div class="card-header d-flex justify-content-between align-items-center">
                                            <h5 class="mb-0">Proposition #<?= $proposition['id'] ?></h5>
                                            <span class="badge bg-<?= $proposition['statut'] === 'En attente' ? 'warning' : ($proposition['statut'] === 'Accepté' ? 'success' : 'danger') ?>">
                                                <?= htmlspecialchars($proposition['statut']) ?>
                                            </span>
                                        </div>
                                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <h6>Votre offre :</h6>
                                                    <div class="card mb-3">
                                                        <div class="card-body">
                                                            <h5 class="card-title"><?= htmlspecialchars($proposition['objet1']['titre']) ?></h5>
                                                            <?php if (!empty($proposition['objet1']['images'])): ?>
                                                                <img src="/data/<?= htmlspecialchars($proposition['objet1']['images'][0]['nomFichier']) ?>" 
                                                                     class="img-fluid mb-2" alt="<?= htmlspecialchars($proposition['objet1']['titre']) ?>" 
                                                                     style="max-height: 150px; width: auto;">
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col-md-6">
                                                    <h6>Objet demandé :</h6>
                                                    <div class="card mb-3">
                                                        <div class="card-body">
                                                            <h5 class="card-title"><?= htmlspecialchars($proposition['objet2']['titre']) ?></h5>
                                                            <?php if (!empty($proposition['objet2']['images'])): ?>
                                                                <img src="/data/<?= htmlspecialchars($proposition['objet2']['images'][0]['nomFichier']) ?>" 
                                                                     class="img-fluid mb-2" alt="<?= htmlspecialchars($proposition['objet2']['titre']) ?>" 
                                                                     style="max-height: 150px; width: auto;">
                                                            <?php endif; ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-muted mb-2">
                                                <small>Destinataire : <?= htmlspecialchars($proposition['destinataire']['nom'] ?? 'Utilisateur inconnu') ?></small><br>
                                                <small>Date : <?= date('d/m/Y H:i', strtotime($proposition['dateEchange'])) ?></small>
                                            </p>
                                        </div>
                                        <?php if ($proposition['statut'] === 'En attente'): ?>
                                            <div class="card-footer bg-transparent">
                                                <form action="/echange/<?= $proposition['id'] ?>/annuler" method="post" class="d-inline"
                                                      onsubmit="return confirm('Êtes-vous sûr de vouloir annuler cette proposition ?')">
                                                    <button type="submit" class="btn btn-warning btn-sm">
                                                        <i class="fas fa-times-circle"></i> Annuler la proposition
                                                    </button>
                                                </form>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- CSS externe -->
<link rel="stylesheet" href="/css/propositions.css">

<!-- JavaScript externe -->
<script src="/js/propositions.js"></script>