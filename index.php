<?php
require_once('queries.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <title>Connexion | Gestionnaire</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="MDB/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="page-auth">
    <main class="auth-layout">
        <section class="login-card shadow-lg">
            <div class="text-center mb-4">
                <div class="brand-badge mb-3">
                    <i class="fa-solid fa-address-book"></i>
                </div>
                <h1 class="futur-title">Connexion</h1>
                <p class="text-muted mb-0">Espace administrateur</p>
                <?php if (!empty($erreur)): ?>
                    <div class="alert alert-danger mt-3 py-2" role="alert" aria-live="polite">
                        <?= htmlspecialchars($erreur) ?>
                    </div>
                <?php endif; ?>
            </div>

            <form action="" method="POST" autocomplete="off" class="auth-form">
                <div class="form-group mb-3">
                    <label class="form-label futur-label" for="loginAdmin">Identifiant</label>
                    <input
                        type="text"
                        class="form-control futur-input"
                        name="login_admin"
                        id="loginAdmin"
                        placeholder="Votre login"
                        autocomplete="username"
                        required
                    >
                </div>
                <div class="form-group mb-4">
                    <label class="form-label futur-label" for="passwordAdmin">Mot de passe</label>
                    <input
                        type="password"
                        class="form-control futur-input"
                        name="motdepasse_admin"
                        id="passwordAdmin"
                        placeholder="Votre mot de passe"
                        autocomplete="current-password"
                        required
                    >
                </div>
                <button type="submit" name="connecter" class="btn futur-btn w-100">
                    <i class="fa-solid fa-right-to-bracket me-2"></i>Connexion
                </button>
            </form>
        </section>
    </main>
</body>
</html>
