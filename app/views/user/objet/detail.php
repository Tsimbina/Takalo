<!doctype html>
<html lang="fr" data-bs-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php echo htmlspecialchars($objet['titre'] ?? 'Objet'); ?> - Takalo</title>
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/bootstrap-icons/font/bootstrap-icons.min.css">
    <style>
        .image-gallery {
            max-height: 500px;
            overflow-y: auto;
        }
        .gallery-thumb {
            cursor: pointer;
            transition: opacity 0.3s ease;
            height: 80px;
            object-fit: cover;
            border-radius: 8px;
        }
        .gallery-thumb:hover {
            opacity: 0.7;
        }
        .gallery-thumb.active {
            opacity: 1;
            border: 3px solid #0d6efd;
        }
        .main-image {
            height: 500px;
            object-fit: cover;
            border-radius: 12px;
            background-color: #f8f9fa;
        }
        .main-image-placeholder {
            height: 500px;
            background-color: #f8f9fa;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
        }
    </style>
</head>
<body>

<div class="container py-5">
    <!-- Header -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/" class="text-decoration-none"><i class="bi bi-house-door"></i> Accueil</a></li>
            <li class="breadcrumb-item"><a href="/objet/explore" class="text-decoration-none">Explorer</a></li>
            <li class="breadcrumb-item active" aria-current="page"><?php echo htmlspecialchars($objet['titre']); ?></li>
        </ol>
    </nav>

    <div class="row">
        <!-- Images Section -->
        <div class="col-lg-6 mb-4 mb-lg-0">
            <div class="mb-3">
                <?php if (!empty($images)): ?>
                    <img id="mainImage" 
                         src="<?php echo htmlspecialchars($images[0]['image']); ?>" 
                         alt="<?php echo htmlspecialchars($objet['titre']); ?>"
                         class="main-image w-100">
                <?php else: ?>
                    <div class="main-image-placeholder w-100">
                        <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Thumbnails -->
            <?php if (count($images) > 1): ?>
            <div class="image-gallery">
                <div class="row g-2">
                    <?php foreach ($images as $index => $image): ?>
                    <div class="col-3">
                        <img src="<?php echo htmlspecialchars($image['image']); ?>" 
                             alt="Image <?php echo $index + 1; ?>"
                             class="gallery-thumb w-100 <?php echo $index === 0 ? 'active' : ''; ?>"
                             onclick="changeImage(this)">
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Details Section -->
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <!-- Title -->
                    <h1 class="h2 fw-bold text-dark mb-2">
                        <?php echo htmlspecialchars($objet['titre']); ?>
                    </h1>

                    <!-- Category & Price -->
                    <div class="d-flex align-items-center gap-3 mb-4">
                        <span class="badge bg-info text-dark fs-6">
                            <?php echo htmlspecialchars($objet['categorie']); ?>
                        </span>
                        <div class="fs-4 fw-bold text-warning">
                            <?php echo number_format((float)$objet['prix'], 2, ',', ' '); ?> €
                        </div>
                    </div>

                    <!-- Description -->
                    <h5 class="fw-bold text-dark mb-2">Description</h5>
                    <p class="text-muted mb-4" style="line-height: 1.6;">
                        <?php echo nl2br(htmlspecialchars($objet['description'])); ?>
                    </p>

                    <!-- Actions -->
                    <div class="d-grid gap-2">
                        <a href="/objet/<?php echo (int)$objet['id']; ?>/propose" class="btn btn-primary btn-lg">
                            <i class="bi bi-arrow-repeat me-2"></i>Proposer un Échange
                        </a>
                        <a href="/objet/accueil" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Retour
                        </a>
                    </div>

                    <!-- Info Card -->
                    <div class="card bg-light border-0 mt-4">
                        <div class="card-body">
                            <p class="text-muted small mb-0">
                                <i class="bi bi-info-circle me-2"></i>
                                Contactez le propriétaire pour discuter des conditions d'échange.
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Proposal Modal -->
<div class="modal fade" id="proposalModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Proposer un Échange</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-info">
                    <i class="bi bi-info-circle me-2"></i>
                    Fonctionnalité d'échange à venir
                </div>
                <p class="text-muted">
                    Le système d'échange sera bientôt disponible. Vous pourrez alors proposer vos objets en échange.
                </p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
<script>
function changeImage(element) {
    const mainImage = document.getElementById('mainImage');
    mainImage.src = element.src;
    
    // Update active thumbnail
    document.querySelectorAll('.gallery-thumb').forEach(thumb => {
        thumb.classList.remove('active');
    });
    element.classList.add('active');
}
</script>
</body>
</html>
