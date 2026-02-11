<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Inscription</title>
        <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
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
            background: rgba(32, 201, 151, .12);
            color: #20c997;
        }

        .form-control:focus {
            box-shadow: 0 0 0 .25rem rgba(32, 201, 151, .15);
        }

        .password-hint {
            font-size: .875rem;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-12 col-md-7 col-lg-6">
            <div class="auth-card shadow-sm mt-5">
                <div class="card-body p-4 p-md-5">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="brand-badge">
                            <i class="bi bi-person-plus-fill fs-4"></i>
                        </div>
                        <div>
                            <h1 class="h4 mb-1">Créer un compte</h1>
                            <div class="text-muted small">Inscription utilisateur</div>
                        </div>
                    </div>

                    <?php if (!empty($success)) : ?>
                        <div class="alert alert-success" role="alert">
                            <?= htmlspecialchars((string)$success, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($error)) : ?>
                        <div class="alert alert-danger" role="alert">
                            <?= htmlspecialchars((string)$error, ENT_QUOTES, 'UTF-8') ?>
                        </div>
                    <?php endif; ?>

                    <form method="post" action="/register" autocomplete="off">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" class="form-control" id="email" name="email" placeholder="ex: nom@domaine.com" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="login" class="form-label">Login</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" class="form-control" id="login" name="login" placeholder="Votre pseudo" required>
                            </div>
                        </div>

                        <div class="row g-3">
                            <div class="col-12 col-md-6">
                                <label for="password" class="form-label">Mot de passe</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-key"></i></span>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="••••••••" required>
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <label for="password_confirm" class="form-label">Confirmation</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-shield-check"></i></span>
                                    <input type="password" class="form-control" id="password_confirm" name="password_confirm" placeholder="••••••••" required>
                                </div>
                            </div>
                        </div>

                        <div class="text-muted password-hint mt-2">
                            Utilise au moins 8 caractères, avec lettres et chiffres.
                        </div>

                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" value="1" id="terms" required>
                            <label class="form-check-label" for="terms">
                                J'accepte les conditions d'utilisation
                            </label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 py-2 mt-4">
                            <i class="bi bi-person-plus me-2"></i>Créer un compte
                        </button>

                        <div class="text-center text-muted small mt-3">
                            Déjà un compte ? <a href="/user/login" class="text-decoration-none">Se connecter</a>
                        </div>
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
