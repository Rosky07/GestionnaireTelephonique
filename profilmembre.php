<?php
require_once('connexion.php');

$feedback = null;

function clean_input($value)
{
    return trim($value ? $value : '');
}

// Actualisation de la photo.
if (isset($_POST['submitPhoto'])) {
    $id_membre = filter_input(INPUT_POST, 'id_membre', FILTER_VALIDATE_INT);
    if ($id_membre && isset($_FILES['photo_membre']) && $_FILES['photo_membre']['error'] === UPLOAD_ERR_OK) {
        $allowedExt = array('jpg', 'jpeg', 'png', 'webp');
        $maxSize = 2 * 1024 * 1024; // 2 Mo
        $ext = strtolower(pathinfo($_FILES['photo_membre']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt, true)) {
            $feedback = array('type' => 'danger', 'message' => "Format de photo non supporté.");
        } elseif ($_FILES['photo_membre']['size'] > $maxSize) {
            $feedback = array('type' => 'danger', 'message' => "La photo est trop lourde (max 2 Mo).");
        } else {
            $uniqueName = date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
            $destination = __DIR__ . '/photomembres/' . $uniqueName;
            if (move_uploaded_file($_FILES['photo_membre']['tmp_name'], $destination)) {
                $req = $bdd->prepare("UPDATE membre SET photo_membre = :photo_membre WHERE id_membre = :id_membre");
                $req->execute(array('photo_membre' => $uniqueName, 'id_membre' => $id_membre));
                $feedback = array('type' => 'success', 'message' => "Photo actualisée avec succès.");
            } else {
                $feedback = array('type' => 'danger', 'message' => "Impossible d'enregistrer la photo.");
            }
        }
    }
}

// Modification du profil.
if (isset($_POST['modifier'])) {
    $id_membre = filter_input(INPUT_POST, 'id_membre', FILTER_VALIDATE_INT);
    if ($id_membre) {
        $civilite = clean_input(isset($_POST['civilite']) ? $_POST['civilite'] : '');
        $nom_membre = clean_input(isset($_POST['nom_membre']) ? $_POST['nom_membre'] : '');
        $prenom_membre = clean_input(isset($_POST['prenom_membre']) ? $_POST['prenom_membre'] : '');
        $contact_membre = clean_input(isset($_POST['contact_membre']) ? $_POST['contact_membre'] : '');
        $datenaissance = clean_input(isset($_POST['datenaissance']) ? $_POST['datenaissance'] : '');
        $lieunaissance = clean_input(isset($_POST['lieunaissance']) ? $_POST['lieunaissance'] : '');
        $sexe = clean_input(isset($_POST['sexe']) ? $_POST['sexe'] : '');
        $email = clean_input(isset($_POST['email']) ? $_POST['email'] : '');

        $update = $bdd->prepare(
            "UPDATE membre
                SET civilite = :civilite,
                    nom_membre = :nom_membre,
                    prenom_membre = :prenom_membre,
                    datenaissance = :datenaissance,
                    lieunaissance = :lieunaissance,
                    sexe = :sexe,
                    contact_membre = :contact_membre,
                    email = :email
              WHERE id_membre = :id_membre"
        );
        $update->execute(array(
            'id_membre' => $id_membre,
            'civilite' => $civilite,
            'nom_membre' => $nom_membre,
            'prenom_membre' => $prenom_membre,
            'datenaissance' => $datenaissance,
            'lieunaissance' => $lieunaissance,
            'sexe' => $sexe,
            'contact_membre' => $contact_membre,
            'email' => $email,
        ));

        $feedback = array('type' => 'success', 'message' => "Profil modifié avec succès.");
    } else {
        $feedback = array('type' => 'danger', 'message' => "Impossible de modifier le profil.");
    }
}

// Récupération du profil à afficher.
$id_membre = filter_input(INPUT_GET, 'cle', FILTER_VALIDATE_INT);
if (!$id_membre) {
    $id_membre = filter_input(INPUT_POST, 'id_membre', FILTER_VALIDATE_INT);
}

$voirprofil = null;
if ($id_membre) {
    $req = $bdd->prepare("SELECT * FROM membre WHERE id_membre = :id_membre");
    $req->execute(array('id_membre' => $id_membre));
    $voirprofil = $req->fetch();

    // Si le profil n'existe pas, rediriger vers la liste des membres
    if (!$voirprofil) {
        header('Location: membres.php');
        exit;
    }
} else {
    // Si aucun ID n'est fourni, rediriger vers la liste
    header('Location: membres.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Profil Membre | Gestionnaire</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="MDB/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body class="page-profile">
    <?php include('include/header.php'); ?>
    <?php include('include/nav.php'); ?>

    <main class="container my-5">
        <h1 class="text-center futur-title mb-4">Mon Profil</h1>

        <?php if ($feedback): ?>
            <div class="alert alert-<?= htmlspecialchars($feedback['type']) ?> text-center">
                <?= htmlspecialchars($feedback['message']) ?>
            </div>
        <?php endif; ?>

        <?php if ($voirprofil): ?>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Formulaire de modification de la photo -->
                    <form class="mb-4" action="" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_membre" value="<?= htmlspecialchars($voirprofil['id_membre']) ?>" />
                        <div class="d-flex flex-column align-items-center">
                            <?php
                            $photoPath = !empty($voirprofil['photo_membre'])
                                ? 'photomembres/' . $voirprofil['photo_membre']
                                : 'avatar.jpg';
                            ?>
                            <img
                                src="<?= htmlspecialchars($photoPath) ?>"
                                alt="Photo"
                                class="img-thumbnail rounded-circle mb-3"
                                style="width:120px;height:120px;object-fit:cover;">
                            <input type="file" class="form-control futur-input mb-2" name="photo_membre" accept="image/*" />
                            <button class="btn futur-btn" type="submit" name="submitPhoto">
                                <i class="fa-solid fa-camera me-2"></i>Actualiser votre photo
                            </button>
                        </div>
                    </form>

                    <!-- Formulaire de modification du profil -->
                    <form action="" method="POST" class="futur-fieldset p-4 rounded-4 shadow-sm bg-white">
                        <input type="hidden" name="id_membre" value="<?= htmlspecialchars($voirprofil['id_membre']) ?>" />
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="futur-label" for="profilCivilite">Civilité</label>
                                <input class="form-control futur-input" type="text" id="profilCivilite" name="civilite" value="<?= htmlspecialchars($voirprofil['civilite']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="futur-label" for="profilNom">Nom</label>
                                <input type="text" class="form-control futur-input" id="profilNom" name="nom_membre" value="<?= htmlspecialchars($voirprofil['nom_membre']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="futur-label" for="profilPrenom">Prénom</label>
                                <input type="text" class="form-control futur-input" id="profilPrenom" name="prenom_membre" value="<?= htmlspecialchars($voirprofil['prenom_membre']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="futur-label" for="profilDatenaissance">Date de naissance</label>
                                <input type="date" class="form-control futur-input" id="profilDatenaissance" name="datenaissance" value="<?= htmlspecialchars($voirprofil['datenaissance']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="futur-label" for="profilContact">Contact</label>
                                <input type="tel" class="form-control futur-input" id="profilContact" name="contact_membre" value="<?= htmlspecialchars($voirprofil['contact_membre']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="futur-label" for="profilEmail">Email</label>
                                <input type="email" class="form-control futur-input" id="profilEmail" name="email" value="<?= htmlspecialchars($voirprofil['email']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="futur-label" for="profilLieu">Lieu de naissance</label>
                                <input type="text" class="form-control futur-input" id="profilLieu" name="lieunaissance" value="<?= htmlspecialchars($voirprofil['lieunaissance']) ?>">
                            </div>
                            <div class="col-md-6">
                                <label class="futur-label" for="profilSexe">Sexe</label>
                                <input type="text" class="form-control futur-input" id="profilSexe" name="sexe" value="<?= htmlspecialchars($voirprofil['sexe']) ?>">
                            </div>
                        </div>
                        <div class="mt-4">
                            <button class="btn futur-btn w-100" type="submit" name="modifier">
                                <i class="fa-solid fa-pen-to-square me-2"></i>Modifier Profil
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-warning text-center">Aucun profil trouvé.</div>
        <?php endif; ?>
    </main>

    <?php include('include/footer.php'); ?>
    <script src="MDB/js/bootstrap.bundle.min.js"></script>
</body>

</html>