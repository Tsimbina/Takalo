<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin - Dashboard</title>
    <link href="/assets/bootstrap/css/bootstrap.min.css" rel="stylesheet" >
</head>
<body class="bg-light">

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/admin">Admin</a>
        <div class="ms-auto">
            <a class="btn btn-outline-light btn-sm" href="/admin/logout">Se déconnecter</a>
        </div>
    </div>
</nav>

<div class="container py-4">
    <div class="row">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h1 class="h5 mb-2">Dashboard</h1>
                    <div class="text-muted">
                        Connecté en tant que: <?= htmlspecialchars((string)($email ?? 'admin'), ENT_QUOTES, 'UTF-8') ?>
                    </div>
                </div>
            </div>

            <div class="alert alert-info mt-3" role="alert">
                Page admin minimale. Tu peux maintenant brancher ici tes pages de gestion.
            </div>
        </div>
    </div>
</div>

<script src="/assets/bootstrap/js/bootstrap.bundle.min.js" ></script>
</body>
</html>
