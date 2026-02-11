<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            min-height: 100vh;
            background: radial-gradient(1200px circle at 15% 10%, rgba(13, 110, 253, .18), transparent 55%),
                        radial-gradient(900px circle at 90% 25%, rgba(111, 66, 193, .16), transparent 55%),
                        linear-gradient(180deg, #f8f9fa, #ffffff);
        }

        .auth-card {
            border: 1px solid rgba(255, 255, 255, .35);
            border-radius: 1rem;
            background: rgba(255, 255, 255, .78);
            backdrop-filter: blur(10px);
        }

        .brand-badge {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: rgba(111, 66, 193, .12);
            color: #6f42c1;
        }

        .form-control:focus {
            box-shadow: 0 0 0 .25rem rgba(111, 66, 193, .15);
        }

        .divider {
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            left: 0;
            right: 0;
            top: 50%;
            height: 1px;
            background: rgba(0, 0, 0, .08);
        }

        .divider span {
            position: relative;
            padding: 0 .75rem;
            background: rgba(255, 255, 255, .78);
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
                            <i class="bi bi-person-lock fs-4"></i>
                        </div>
                        <div>
                            <h1 class="h4 mb-1">Connexion</h1>
                            <div class="text-muted small">Accéder à votre compte</div>
                        </div>
                    </div>

                    <?php if (!empty($error)) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="/login" autocomplete="off">
                        <div class="mb-3">
                            <label for="login" class="form-label">Email ou login</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="login" name="login" placeholder="ex: nom@domaine.com" required>
                            </div>
                        </div>

                        <div class="mb-2">
                            <label for="password" class="form-label">Mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-key"></i></span>
                                <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                            </div>
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-3">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" value="1" id="remember">
                                <label class="form-check-label" for="remember">Se souvenir de moi</label>
                            </div>
                            <a href="#" class="text-decoration-none small">Mot de passe oublié ?</a>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 mt-4">
                            <i class="bi bi-box-arrow-in-right me-2"></i>Se connecter
                        </button>

                        <div class="divider text-center text-muted small my-4">
                            <span>ou</span>
                        </div>

                        <a href="/register" class="btn btn-outline-secondary w-100">
                            <i class="bi bi-person-plus me-2"></i>Créer un compte
                        </a>
                    </form>
                </div>
            </div>

            <div class="text-center text-muted small mt-3">
                <a class="text-decoration-none" href="/"><i class="bi bi-arrow-left me-1"></i>Retour</a>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
