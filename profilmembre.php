<?php
require_once('connexion.php');

// Actualisation de la photo
if (isset($_POST['submitPhoto'])) {
    $id_membre = $_POST['id_membre'];
    if ($_FILES['photo_membre']['error'] == 0) {
        $photo_name = basename($_FILES['photo_membre']['name']);
        move_uploaded_file($_FILES['photo_membre']['tmp_name'], 'photomembres/' . $photo_name);
        $req = $bdd->prepare("UPDATE membre SET photo_membre=:photo_membre WHERE id_membre=:id_membre");
        $req->execute(['photo_membre' => $photo_name, 'id_membre' => $id_membre]);
    }
}

// Modification du profil
if (isset($_POST['modifier'])) {
    $id_membre = htmlentities($_POST['id_membre']);
    $civilite = htmlentities($_POST['civilite']);
    $nom_membre = htmlentities($_POST['nom_membre']);
    $prenom_membre = htmlentities($_POST['prenom_membre']);
    $contact_membre = htmlentities($_POST['contact_membre']);
    $datenaissance = htmlentities($_POST['datenaissance']);
    $lieunaissance = htmlentities($_POST['lieunaissance']);
    $sexe = htmlentities($_POST['sexe']);
    $email = htmlentities($_POST['email']);

    $update = $bdd->prepare("UPDATE membre SET civilite=:civilite, nom_membre=:nom_membre, prenom_membre=:prenom_membre, datenaissance=:datenaissance, lieunaissance=:lieunaissance, sexe=:sexe, contact_membre=:contact_membre, email=:email WHERE id_membre=:id_membre");
    $update->execute([
        'id_membre' => $id_membre,
        'civilite' => $civilite,
        'nom_membre' => $nom_membre,
        'prenom_membre' => $prenom_membre,
        'datenaissance' => $datenaissance,
        'lieunaissance' => $lieunaissance,
        'sexe' => $sexe,
        'contact_membre' => $contact_membre,
        'email' => $email
    ]);

    if ($update) {
        echo "<script>alert('Modifié avec succès');</script>";
    } else {
        echo "<script>alert('Erreur de modification !');</script>";
    }
}

// Récupération du profil à afficher
$id_membre = isset($_GET['cle']) ? intval($_GET['cle']) : (isset($_POST['id_membre']) ? intval($_POST['id_membre']) : 0);
$voirprofil = null;
if ($id_membre > 0) {
    $req = $bdd->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
    $req->execute(['id_membre' => $id_membre]);
    $voirprofil = $req->fetch();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Profil Membre</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="stylesheet" href="MDB/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<?php include('include/header.php'); ?>
<?php include('include/nav.php'); ?>

<div class="container my-5">
    <h1 class="text-center futur-title mb-4">MON PROFIL</h1>
    <?php if ($voirprofil): ?>
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <!-- Formulaire de modification de la photo -->
                <form class="mb-4" action="" method="POST" enctype="multipart/form-data">
                    <input type="hidden" name="id_membre" value="<?= htmlspecialchars($voirprofil['id_membre']) ?>"/>
                    <div class="d-flex flex-column align-items-center">
                        <img src="photomembres/<?= htmlspecialchars($voirprofil['photo_membre']) ?>" alt="Photo" class="img-thumbnail rounded-circle mb-3" style="width:120px;height:120px;object-fit:cover;">
                        <input type="file" class="form-control futur-input mb-2" name="photo_membre" accept="image/jpeg,image/png"/>
                        <button class="btn futur-btn" type="submit" name="submitPhoto">Actualiser votre photo</button>
                    </div>
                </form>

                <!-- Formulaire de modification du profil -->
                <form action="" method="POST" enctype="multipart/form-data" class="futur-fieldset p-4 rounded-4 shadow-sm bg-white">
                    <input type="hidden" name="id_membre" value="<?= htmlspecialchars($voirprofil['id_membre']) ?>"/>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="futur-label">Civilité :</label>
                            <input class="form-control futur-input" type="text" name="civilite" value="<?= htmlspecialchars($voirprofil['civilite']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="futur-label">Nom :</label>
                            <input type="text" class="form-control futur-input" name="nom_membre" value="<?= htmlspecialchars($voirprofil['nom_membre']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="futur-label">Prénom :</label>
                            <input type="text" class="form-control futur-input" name="prenom_membre" value="<?= htmlspecialchars($voirprofil['prenom_membre']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="futur-label">Date de Naissance :</label>
                            <input type="date" class="form-control futur-input" name="datenaissance" value="<?= htmlspecialchars($voirprofil['datenaissance']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="futur-label">Contact :</label>
                            <input type="tel" class="form-control futur-input" name="contact_membre" value="<?= htmlspecialchars($voirprofil['contact_membre']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="futur-label">Email :</label>
                            <input type="email" class="form-control futur-input" name="email" value="<?= htmlspecialchars($voirprofil['email']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="futur-label">Lieu de naissance :</label>
                            <input type="text" class="form-control futur-input" name="lieunaissance" value="<?= htmlspecialchars($voirprofil['lieunaissance']) ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="futur-label">Sexe :</label>
                            <input type="text" class="form-control futur-input" name="sexe" value="<?= htmlspecialchars($voirprofil['sexe']) ?>">
                        </div>
                    </div>
                    <div class="mt-4">
                        <button class="btn futur-btn w-100" type="submit" name="modifier">Modifier Profil</button>
                    </div>
                </form>
            </div>
        </div>
    <?php else: ?>
        <div class="alert alert-warning text-center">Aucun profil trouvé.</div>
    <?php endif; ?>
</div>

<?php include('include/footer.php'); ?>
<script src="MDB/js/bootstrap.bundle.min.js"></script>
</body>
</html>