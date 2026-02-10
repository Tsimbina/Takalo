<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - Takalo</title>
    <link rel="stylesheet" href="/assets/style/inscription.css">
</head>
<body>
    <div class="inscription-container">
        <div class="inscription-card">
            <div class="inscription-header">
                <h1>Créer un compte</h1>
                <p>Rejoignez-nous dès aujourd'hui</p>
            </div>

            <form class="inscription-form" method="POST">
                <div class="form-group">
                    <label for="login">Nom d'utilisateur</label>
                    <input 
                        type="text" 
                        id="login" 
                        name="login" 
                        placeholder="Entrez votre nom d'utilisateur"
                        required
                    >
                    <span class="error-message" id="login-error"></span>
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input 
                        type="email" 
                        id="email" 
                        name="email" 
                        placeholder="Entrez votre email"
                        required
                    >
                    <span class="error-message" id="email-error"></span>
                </div>

                <div class="form-group">
                    <label for="password">Mot de passe</label>
                    <input 
                        type="password" 
                        id="password" 
                        name="password" 
                        placeholder="Entrez votre mot de passe"
                        required
                    >
                    <span class="error-message" id="password-error"></span>
                </div>

                <button type="submit" class="btn-submit">S'inscrire</button>
            </form>

            <div class="inscription-footer">
                <p>Vous avez déjà un compte? <a href="/login">Se connecter</a></p>
            </div>
        </div>
    </div>
</body>
</html>