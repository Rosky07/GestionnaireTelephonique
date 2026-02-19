<?php
require_once('connexion.php');
require_once('queries.php'); // On déplace la logique PHP ici
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Connexion Gestionnaire</title>
    <link rel="stylesheet" href="MDB/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="container d-flex align-items-center justify-content-center min-vh-100">
        <div class="login-card shadow-lg p-4 rounded-4">
            <div class="text-center mb-4">
                <h2 class="futur-title">CONNEXION</h2>
                <h5 class="text-secondary">Administrateur</h5>
                <?php if(!empty($erreur)): ?>
                    <div class="alert alert-danger py-1"><?= $erreur ?></div>
                <?php endif; ?>
            </div>
            <form action="" method="POST" autocomplete="off">
                <div class="form-group mb-3">
                    <input type="text" class="form-control futur-input" name="login_admin" placeholder="Votre login" required>
                </div>
                <div class="form-group mb-4">
                    <input type="password" class="form-control futur-input" name="motdepasse_admin" placeholder="Mot de Passe" required>
                </div>
                <button type="submit" name="connecter" class="btn futur-btn btn-block w-100">Connexion</button>
            </form>
        </div>
    </div>
</body>
</html>

