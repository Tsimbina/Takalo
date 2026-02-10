<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Takalo</title>
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/assets/style/inscription.css">
</head>
<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-xl-5 col-lg-6 col-md-8">
                <div class="card shadow-lg border-0 rounded-lg">
                    <div class="card-header bg-primary text-white text-center py-4">
                        <h1 class="h3 mb-1">Créer un compte</h1>
                        <p class="mb-0 small">Rejoignez-nous dès aujourd'hui</p>
                    </div>
                    <div class="card-body p-4">
                        <form method="POST">
                            <div class="mb-3">
                                <label for="login" class="form-label fw-semibold">Nom d'utilisateur</label>
                                <input 
                                    type="text" 
                                    class="form-control" 
                                    id="login" 
                                    name="login" 
                                    placeholder="Entrez votre nom d'utilisateur"
                                    required
                                >
                                <div class="invalid-feedback" id="login-error"></div>
                            </div>

                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">Adresse email</label>
                                <input 
                                    type="email" 
                                    class="form-control" 
                                    id="email" 
                                    name="email" 
                                    placeholder="Entrez votre adresse email"
                                    required
                                >
                                <div class="invalid-feedback" id="email-error"></div>
                            </div>

                            <div class="mb-4">
                                <label for="password" class="form-label fw-semibold">Mot de passe</label>
                                <input 
                                    type="password" 
                                    class="form-control" 
                                    id="password" 
                                    name="password" 
                                    placeholder="Entrez votre mot de passe"
                                    required
                                >
                                <div class="invalid-feedback" id="password-error"></div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary btn-lg">S'inscrire</button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3 border-top">
                        <small class="text-muted">Vous avez déjà un compte? <a href="/login" class="text-decoration-none">Se connecter</a></small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/assets/bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>