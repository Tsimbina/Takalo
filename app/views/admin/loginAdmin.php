<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Connexion</title>
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" >
    <link rel="stylesheet" href="/assets/bootstrap-icons/font/bootstrap-icons.min.css">
    <style>
        body {
            min-height: 100vh;
            background: radial-gradient(1200px circle at 15% 10%, rgba(13, 110, 253, .18), transparent 55%),
                        radial-gradient(900px circle at 90% 25%, rgba(32, 201, 151, .18), transparent 55%),
                        linear-gradient(180deg, #f8f9fa, #ffffff);
        }

        .auth-card {
            border: 1px solid rgba(255, 255, 255, .35);
            border-radius: 1rem;
            background: rgba(255, 255, 255, .75);
            backdrop-filter: blur(10px);
        }

        .brand-badge {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(13, 110, 253, .12);
            color: #0d6efd;
        }

        .form-control:focus {
            box-shadow: 0 0 0 .25rem rgba(13, 110, 253, .15);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-6 col-lg-5">
            <div class="auth-card shadow-sm mt-5">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="brand-badge">
                            <i class="bi bi-shield-lock-fill fs-4"></i>
                        </div>
                        <div>
                            <h1 class="h4 mb-1">Connexion administrateur</h1>
                            <div class="text-muted small">Accès réservé</div>
                        </div>
                    </div>

                    <?php if (!empty($error)) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="/admin/login" autocomplete="off">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="admin@takalo.local" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                        </button>
                    </form>

                    <hr class="my-4">

                    <div class="text-muted small d-flex align-items-start gap-2">
                        <i class="bi bi-info-circle mt-1"></i>
                        <div>Identifiants par défaut: admin@takalo.local / admin123</div>
                    </div>
                </div>
            </div>

            <div class="text-center text-muted small mt-3">
                <a class="text-decoration-none" href="/"><i class="bi bi-arrow-left me-1"></i>Retour</a>
            </div>
        </div>
    </div>
</div>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
