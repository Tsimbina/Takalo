<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Explorer les Objets - Takalo</title>
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
      transition: transform .2s;
    }

    .card:hover {
      transform: translateY(-5px);
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
  </style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
  <div class="container">
    <a class="navbar-brand fw-bold text-primary" href="/"><i class="bi bi-box-seam"></i> Takalo</a>
    <div class="d-flex gap-2">
      <a href="/objet" class="btn btn-outline-primary btn-sm"><i class="bi bi-bag"></i> Mes Objets</a>
      <a href="/objet/propositions" class="btn btn-outline-success btn-sm"><i class="bi bi-inbox"></i> Propositions</a>
      <a href="/logout" class="btn btn-outline-danger btn-sm"><i class="bi bi-box-arrow-right"></i> Déconnexion</a>
    </div>
  </div>
</nav>

<main class="container py-5">
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h4 mb-0"><i class="bi bi-bag"></i> Objets disponibles pour l'échange</h1>
    <a href="/objet/create" class="btn btn-primary"><i class="bi bi-plus-lg"></i> Ajouter un objet</a>
  </div>

  <?php if (!empty($objets)): ?>
  <div class="row g-4">
    <?php foreach ($objets as $index => $objet): ?>
    <!-- Objet -->
    <div class="col-12 col-md-6 col-lg-4">
      <div class="card h-100">
        <?php
        // Récupérer les images pour cet objet
        $stmt = Flight::db()->prepare('SELECT image FROM imageObjet WHERE idObjet = ? ORDER BY id ASC');
        $stmt->execute([$objet['id']]);
        $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>
        
        <?php if (!empty($images)): ?>
        <div id="carousel<?php echo (int)$objet['id']; ?>" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner">
            <?php foreach ($images as $imgIndex => $image): ?>
            <div class="carousel-item <?php echo $imgIndex === 0 ? 'active' : ''; ?>">
              <img src="<?php echo htmlspecialchars($image['image']); ?>" 
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
            <div class="btn-group btn-group-sm" role="group">
              <a href="/objet/detail/<?php echo (int)$objet['id']; ?>" class="btn btn-outline-info">
                <i class="bi bi-eye"></i>
              </a>
              <a href="/objet/<?php echo (int)$objet['id']; ?>/propose" class="btn btn-outline-success">
                <i class="bi bi-arrow-repeat"></i> Échanger
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
  <?php else: ?>
  <div class="alert alert-info text-center py-5">
    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
    <h5 class="mt-3">Aucun objet disponible</h5>
    <p class="text-muted">Revenez plus tard pour découvrir des objets à échanger</p>
  </div>
  <?php endif; ?>
</main>

<footer class="text-center text-muted small py-4 mt-5 border-top">
  © 2026 Takalo | Échangez librement vos objets
</footer>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>
