<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Proposer un Échange - Takalo</title>
  <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="/assets/bootstrap-icons/font/bootstrap-icons.min.css">
  <style>
    body {
      background: linear-gradient(180deg, #f8f9fa, #ffffff);
      min-height: 100vh;
    }

    .card {
      border: none;
      border-radius: 1rem;
      box-shadow: 0 4px 12px rgba(0,0,0,0.1);
      transition: transform .2s, border-color .2s;
    }

    .card.object-target {
      border: 3px solid #0d6efd !important;
      background-color: rgba(13, 110, 253, 0.05);
    }

    .card.selectable {
      cursor: pointer;
    }

    .card.selectable:hover {
      transform: translateY(-5px);
    }

    .card.selected {
      border: 3px solid #198754 !important;
      background-color: rgba(25, 135, 84, 0.05);
    }

    .card input[type="radio"] {
      cursor: pointer;
    }

    .carousel-item img {
      height: 220px;
      object-fit: cover;
      border-top-left-radius: 1rem;
      border-top-right-radius: 1rem;
    }

    .badge-categ {
      background: rgba(13,110,253,0.1);
      color: #0d6efd;
      font-weight: 500;
    }

    .carousel-control-prev, .carousel-control-next {
      width: 10%;
    }

    .no-images-placeholder {
      height: 220px;
      background-color: #f8f9fa;
      border-top-left-radius: 1rem;
      border-top-right-radius: 1rem;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .section-title {
      font-size: 1.5rem;
      font-weight: 700;
      margin-bottom: 1.5rem;
      color: #212529;
    }

    .step-indicator {
      display: flex;
      gap: 1rem;
      margin-bottom: 3rem;
    }

    .step {
      flex: 1;
      text-align: center;
      padding: 1rem;
      border-radius: 0.5rem;
      background-color: #e9ecef;
      font-weight: 600;
      color: #6c757d;
    }

    .step.active {
      background-color: #198754;
      color: white;
    }

    .target-section {
      background: linear-gradient(135deg, rgba(13, 110, 253, 0.1), rgba(13, 110, 253, 0.05));
      border-radius: 1.5rem;
      padding: 2rem;
      margin-bottom: 4rem;
      border: 2px solid rgba(13, 110, 253, 0.2);
    }

    .arrow-divider {
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 3rem 0;
      position: relative;
    }

    .arrow-divider::before {
      content: '';
      flex: 1;
      height: 2px;
      background: linear-gradient(to right, transparent, #dee2e6, transparent);
    }

    .arrow-divider i {
      font-size: 2rem;
      color: #198754;
      margin: 0 1rem;
      transform: rotate(90deg);
    }

    .user-owner {
      color: #6c757d;
      font-size: 0.9rem;
      margin-top: 0.5rem;
    }

    .relationship-label {
      display: inline-block;
      background: #0d6efd;
      color: white;
      padding: 0.25rem 0.75rem;
      border-radius: 2rem;
      font-size: 0.85rem;
      font-weight: 600;
      margin-bottom: 1.5rem;
    }
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="/"><i class="bi bi-box-seam"></i> Takalo</a>
    <div class="d-flex gap-2">
      <a href="/objet" class="btn btn-outline-primary btn-sm"><i class="bi bi-bag"></i> Mes Objets</a>
      <a href="/objet/accueil" class="btn btn-outline-info btn-sm"><i class="bi bi-eye"></i> Explorer</a>
      <a href="/objet/propositions" class="btn btn-outline-success btn-sm"><i class="bi bi-inbox"></i> Propositions</a>
    </div>
  </div>
</nav>

<main class="container py-5">
  <?php if (isset($showPropositions) && $showPropositions): ?>
  <!-- Mode: Affichage des propositions reçues -->
  <!-- Breadcrumb pour propositions -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="bi bi-house-door"></i> Accueil</a></li>
      <li class="breadcrumb-item"><a href="/objet" class="text-decoration-none">Mes Objets</a></li>
      <li class="breadcrumb-item active" aria-current="page">Propositions reçues</li>
    </ol>
  </nav>

  <h1 class="section-title"><i class="bi bi-inbox"></i> Propositions d'échange reçues</h1>
  <p class="text-muted mb-4">Voici les propositions d'échange que vous avez reçues (acceptées ou refusées).</p>

  <?php if (!empty($success)): ?>
  <div class="alert alert-success alert-dismissible fade show" role="alert">
    <?php echo htmlspecialchars($success); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  <?php endif; ?>

  <?php if (!empty($error)): ?>
  <div class="alert alert-danger alert-dismissible fade show" role="alert">
    <?php echo htmlspecialchars($error); ?>
    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
  </div>
  <?php endif; ?>

  <?php if (!empty($propositions)): ?>
  <div class="row g-4">
    <?php foreach ($propositions as $proposition): ?>
    <div class="col-12 col-lg-6">
      <div class="card">
        <div class="card-body">
          <div class="row">
            <!-- Objet proposé (par l'autre utilisateur) -->
            <div class="col-6">
              <h6 class="text-muted">Objet proposé</h6>
              <img src="/<?php echo htmlspecialchars($proposition['image_objetPropose'] ?? 'data/placeholder.jpg'); ?>" 
                   class="img-thumbnail mb-2" style="height: 80px; width: 80px; object-fit: cover;">
              <h6><?php echo htmlspecialchars($proposition['titre_objetPropose']); ?></h6>
              <small class="text-muted"><?php echo number_format((float)$proposition['prix_objetPropose'], 0, ',', ' '); ?> Ar</small>
              <p><small class="text-muted">Par: <?php echo htmlspecialchars($proposition['nom_proposeur']); ?></small></p>
            </div>
            
            <!-- Flèche -->
            <div class="col-12 col-sm-auto d-flex align-items-center justify-content-center">
              <i class="bi bi-arrow-left-right text-success" style="font-size: 1.5rem;"></i>
            </div>
            
            <!-- Objet convoité (votre objet) -->
            <div class="col-6">
              <h6 class="text-muted">Votre objet</h6>
              <img src="/<?php echo htmlspecialchars($proposition['image_objetConvoite'] ?? 'data/placeholder.jpg'); ?>" 
                   class="img-thumbnail mb-2" style="height: 80px; width: 80px; object-fit: cover;">
              <h6><?php echo htmlspecialchars($proposition['titre_objetConvoite']); ?></h6>
              <small class="text-muted"><?php echo number_format((float)$proposition['prix_objetConvoite'], 0, ',', ' '); ?> Ar</small>
            </div>
          </div>
          
          <hr>
          
          <div class="d-flex justify-content-between align-items-center">
            <span class="badge bg-<?php echo $proposition['idStatutEchange'] == 1 ? 'success' : 'danger'; ?>">
              <?php echo htmlspecialchars($proposition['statusLibelle']); ?>
            </span>
            <small class="text-muted"><?php echo date('d/m/Y', strtotime($proposition['dateEchange'])); ?></small>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="alert alert-info text-center py-5">
    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
    <h5 class="mt-3">Aucune proposition</h5>
    <p class="text-muted">Vous n'avez encore reçu aucune proposition d'échange.</p>
  </div>
  <?php endif; ?>

  <?php else: ?>
  <!-- Mode: Formulaire de proposition d'échange (code original) -->
  <!-- Breadcrumb -->
  <nav aria-label="breadcrumb" class="mb-4">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="bi bi-house-door"></i> Accueil</a></li>
      <li class="breadcrumb-item"><a href="/objet/explore" class="text-decoration-none">Explorer</a></li>
      <li class="breadcrumb-item active" aria-current="page">Proposer un Échange</li>
    </ol>
  </nav>

  <!-- Step Indicator -->
  <div class="step-indicator">
    <div class="step active">
      <i class="bi bi-1-circle-fill"></i> L'objet convoité
    </div>
    <div class="step">
      <i class="bi bi-2-circle"></i> Sélectionner mon objet
    </div>
    <div class="step">
      <i class="bi bi-3-circle"></i> Confirmer
    </div>
  </div>

  <!-- Target Object Section (MÈRE) -->
  <section class="target-section">
    <span class="relationship-label"><i class="bi bi-star-fill"></i> L'objet convoité</span>
    <h2 class="section-title">Objet que vous désirez</h2>
    
    <?php if (!empty($objetTarget)): ?>
    <div class="row">
      <div class="col-lg-6">
        <?php
        // Récupérer les images de l'objet cible
        $stmt = Flight::db()->prepare('SELECT image FROM imageObjet WHERE idObjet = ? ORDER BY id ASC');
        $stmt->execute([$objetTarget['id']]);
        $images = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        ?>
        
        <?php if (!empty($images)): ?>
        <div id="carouselTarget" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <?php foreach ($images as $imgIndex => $image): ?>
            <div class="carousel-item <?php echo $imgIndex === 0 ? 'active' : ''; ?>">
              <img src="/<?php echo htmlspecialchars($image['image']); ?>" 
                   class="d-block w-100 rounded" 
                   alt="<?php echo htmlspecialchars($objetTarget['titre']); ?>">
            </div>
            <?php endforeach; ?>
          </div>
          <?php if (count($images) > 1): ?>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselTarget" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselTarget" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
          <?php endif; ?>
        </div>
        <?php else: ?>
        <div class="no-images-placeholder rounded">
          <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
        </div>
        <?php endif; ?>
      </div>

      <div class="col-lg-6">
        <span class="badge badge-categ"><?php echo htmlspecialchars($objetTarget['categorie']); ?></span>
        <h3 class="mt-3 mb-2"><?php echo htmlspecialchars($objetTarget['titre']); ?></h3>
        <p class="text-muted"><?php echo htmlspecialchars($objetTarget['description']); ?></p>
        
        <div class="mt-4 pt-3 border-top">
          <div class="row">
            <div class="col-6">
              <small class="text-muted d-block">Valeur estimée</small>
              <span class="h5 text-primary fw-bold"><?php echo number_format((float)$objetTarget['prix'], 0, ',', ' '); ?> Ar</span>
            </div>
            <div class="col-6">
              <small class="text-muted d-block">Propriétaire</small>
              <span class="h5 fw-bold"><?php echo htmlspecialchars($objetTarget['nomUser']); ?></span>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endif; ?>
  </section>

  <!-- Arrow Divider -->
  <div class="arrow-divider">
    <i class="bi bi-arrow-down"></i>
  </div>

  <!-- Form and Selection Section (FILLES) -->
  <form method="POST" action="/objet/<?php echo (int)$idObjetTarget; ?>/propose">
    <h2 class="section-title"><i class="bi bi-gift"></i> Sélectionnez votre objet</h2>
    <p class="text-muted mb-4">Choisissez l'un de vos objets à proposer en échange.</p>

    <?php if (!empty($myObjets)): ?>
    <div class="row g-4 mb-5">
      <?php foreach ($myObjets as $index => $objet): ?>
      <!-- User's Object Card (FILLE) -->
      <div class="col-12 col-md-6 col-lg-4">
        <label class="card h-100 selectable" style="border: 2px solid transparent;">
          <div class="form-check position-absolute" style="top: 10px; right: 10px; z-index: 10;">
            <input class="form-check-input" type="radio" name="idObjet" value="<?php echo (int)$objet['id']; ?>" 
                   id="objet_<?php echo (int)$objet['id']; ?>" onchange="updateCard(this)">
          </div>

          <?php
          // Récupérer les images pour cet objet
          $stmt = Flight::db()->prepare('SELECT image FROM imageObjet WHERE idObjet = ? ORDER BY id ASC');
          $stmt->execute([$objet['id']]);
          $images = $stmt->fetchAll(\PDO::FETCH_ASSOC);
          ?>
          
          <?php if (!empty($images)): ?>
          <div id="carousel<?php echo (int)$objet['id']; ?>" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
              <?php foreach ($images as $imgIndex => $image): ?>
              <div class="carousel-item <?php echo $imgIndex === 0 ? 'active' : ''; ?>">
                <img src="/<?php echo htmlspecialchars($image['image']); ?>" 
                     class="d-block w-100" 
                     alt="<?php echo htmlspecialchars($objet['titre']); ?>">
              </div>
              <?php endforeach; ?>
            </div>
            <?php if (count($images) > 1): ?>
            <button class="carousel-control-prev" type="button" data-bs-target="#carousel<?php echo (int)$objet['id']; ?>" data-bs-slide="prev">
              <span class="carousel-control-prev-icon"></span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carousel<?php echo (int)$objet['id']; ?>" data-bs-slide="next">
              <span class="carousel-control-next-icon"></span>
            </button>
            <?php endif; ?>
          </div>
          <?php else: ?>
          <div class="no-images-placeholder">
            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
          </div>
          <?php endif; ?>
          
          <div class="card-body">
            <span class="badge badge-categ mb-2"><?php echo htmlspecialchars($objet['categorie']); ?></span>
            <h5 class="card-title"><?php echo htmlspecialchars($objet['titre']); ?></h5>
            <p class="card-text text-muted small"><?php echo htmlspecialchars($objet['description']); ?></p>
            <div class="d-flex justify-content-between align-items-center">
              <span class="fw-bold text-primary"><?php echo number_format((float)$objet['prix'], 0, ',', ' '); ?> Ar</span>
            </div>
          </div>
        </label>
      </div>
      <?php endforeach; ?>
    </div>

    <!-- Action Buttons -->
    <div class="row mt-5">
      <div class="col-lg-8 mx-auto">
        <div class="d-grid gap-2">
          <button type="submit" class="btn btn-success btn-lg" id="submitBtn" disabled>
            <i class="bi bi-check-circle me-2"></i>Confirmer la proposition d'échange
          </button>
          <a href="/objet/explore" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left me-2"></i>Annuler
          </a>
        </div>
      </div>
    </div>

    <?php else: ?>
    <div class="alert alert-warning text-center py-5">
      <i class="bi bi-exclamation-triangle" style="font-size: 3rem;"></i>
      <h5 class="mt-3">Vous n'avez pas d'objets</h5>
      <p class="text-muted">Veuillez ajouter au moins un objet avant de proposer un échange.</p>
      <a href="/objet/create" class="btn btn-primary mt-3">
        <i class="bi bi-plus-lg me-2"></i>Ajouter un objet
      </a>
    </div>
    <?php endif; ?>
  </form>
  <?php endif; ?> <!-- Fin du mode proposition d'échange -->
</main>

<footer class="text-center text-muted small py-4 mt-5 border-top">
  © 2026 Takalo | Échangez librement vos objets
</footer>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
function updateCard(radio) {
  // Remove selected class from all cards
  document.querySelectorAll('label.card.selectable').forEach(card => {
    card.classList.remove('selected');
    card.style.borderColor = 'transparent';
  });
  
  // Add selected class to the checked card
  if (radio.checked) {
    radio.closest('label.card').classList.add('selected');
  }
  
  // Enable/disable submit button
  const submitBtn = document.getElementById('submitBtn');
  submitBtn.disabled = !document.querySelector('input[name="idObjet"]:checked');
}

// Initialize on page load
document.addEventListener('DOMContentLoaded', () => {
  const checked = document.querySelector('input[name="idObjet"]:checked');
  const submitBtn = document.getElementById('submitBtn');
  submitBtn.disabled = !checked;
});
</script>
</body>
</html>
