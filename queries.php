<?php
session_start();
require_once('connexion.php');

$erreur = null;
$voirprofil = null;

// Petites fonctions utilitaires pour garder le code lisible.
function clean_input($value)
{
    return trim(isset($value) ? $value : '');
}

function redirect_to($path)
{
    header('Location: ' . $path);
    exit;
}

function alert_and_back($message)
{
    $safe = json_encode($message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    echo "<script>alert($safe); window.history.back();</script>";
    exit;
}

function alert_and_redirect($message, $path)
{
    $safe = json_encode($message, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    $safePath = json_encode($path, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    echo "<script>alert($safe); window.location=$safePath;</script>";
    exit;
}

// -----------------------
// Connexion administrateur
// -----------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['connecter'])) {

    $login_admin = clean_input(isset($_POST['login_admin']) ? $_POST['login_admin'] : '');
    $motdepasse_admin = clean_input(isset($_POST['motdepasse_admin']) ? $_POST['motdepasse_admin'] : '');

    if ($login_admin === '' || $motdepasse_admin === '') {
        $erreur = "Veuillez remplir tous les champs.";
    } else {

        $req = $bdd->prepare("SELECT * FROM administrateur WHERE login_admin = :login_admin");
        $req->execute(array(
            'login_admin' => $login_admin
        ));

        $resultat = $req->fetch(PDO::FETCH_ASSOC);

        if ($resultat) {

            // Vérification sécurisée du mot de passe
            if (password_verify($motdepasse_admin, $resultat['motdepasse_admin'])) {

                session_regenerate_id(true);
                $_SESSION['login_admin'] = $resultat['login_admin'];

                redirect_to('identification.php');
            } else {
                $erreur = "Login ou mot de passe incorrect.";
            }
        } else {
            $erreur = "Login ou mot de passe incorrect.";
        }
    }
}

// -----------------------
// Inscription d'un membre
// -----------------------
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['envoyer'])) {
    $civilite = clean_input(isset($_POST['civilite']) ? $_POST['civilite'] : '');
    $nom_membre = clean_input(isset($_POST['nom_membre']) ? $_POST['nom_membre'] : '');
    $prenom_membre = clean_input(isset($_POST['prenom_membre']) ? $_POST['prenom_membre'] : '');
    $datenaissance = clean_input(isset($_POST['datenaissance']) ? $_POST['datenaissance'] : '');
    $lieunaissance = clean_input(isset($_POST['lieunaissance']) ? $_POST['lieunaissance'] : '');
    $sexe = clean_input(isset($_POST['sexe']) ? $_POST['sexe'] : '');
    $contact_membre = clean_input(isset($_POST['contact_membre']) ? $_POST['contact_membre'] : '');
    $email = clean_input(isset($_POST['email']) ? $_POST['email'] : '');

    if (
        $civilite === '' || $nom_membre === '' || $prenom_membre === '' || $datenaissance === '' ||
        $lieunaissance === '' || $sexe === '' || $contact_membre === '' || $email === ''
    ) {
        alert_and_back("Veuillez remplir tous les champs.");
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        alert_and_back("Veuillez saisir une adresse email valide.");
    }

    // Gestion sécurisée de la photo.
    $photo = '';
    if (!empty($_FILES['photo_membre']['name'])) {
        if ($_FILES['photo_membre']['error'] !== UPLOAD_ERR_OK) {
            alert_and_back("Erreur lors du chargement de la photo.");
        }

        $allowedExt = array('jpg', 'jpeg', 'png', 'webp');
        $maxSize = 2 * 1024 * 1024; // 2 Mo
        $ext = strtolower(pathinfo($_FILES['photo_membre']['name'], PATHINFO_EXTENSION));

        if (!in_array($ext, $allowedExt, true)) {
            alert_and_back("Format de photo non supporté (jpg, jpeg, png, webp).");
        }
        if ($_FILES['photo_membre']['size'] > $maxSize) {
            alert_and_back("La photo est trop lourde (max 2 Mo).");
        }

        $photoDir = __DIR__ . '/photomembres/';
        if (!is_dir($photoDir)) {
            mkdir($photoDir, 0755, true);
        }

        $uniqueName = date('YmdHis') . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destination = $photoDir . $uniqueName;
        if (!move_uploaded_file($_FILES['photo_membre']['tmp_name'], $destination)) {
            alert_and_back("Impossible d'enregistrer la photo.");
        }
        $photo = $uniqueName;
    }

    // Vérification d'unicité sur les champs critiques (email + contact + civilité + datenaissance).
    // Ne pas vérifier nom+prénom seuls (plusieurs personnes peuvent avoir le même nom).
    $verif = $bdd->prepare(
        'SELECT id_membre FROM membre
         WHERE email = :email
            OR contact_membre = :contact_membre
            OR (nom_membre = :nom_membre AND prenom_membre = :prenom_membre AND datenaissance = :datenaissance)'
    );
    $verif->execute(array(
        'email' => $email,
        'contact_membre' => $contact_membre,
        'nom_membre' => $nom_membre,
        'prenom_membre' => $prenom_membre,
        'datenaissance' => $datenaissance,
    ));
    if ($verif->fetch()) {
        alert_and_back("Erreur : cet email, ce téléphone ou cette identité existe déjà.");
    }

    $req = $bdd->prepare(
        'INSERT INTO membre
            (photo_membre, civilite, nom_membre, prenom_membre, datenaissance, lieunaissance, sexe, contact_membre, email)
         VALUES
            (:photo_membre, :civilite, :nom_membre, :prenom_membre, :datenaissance, :lieunaissance, :sexe, :contact_membre, :email)'
    );
    $success = $req->execute(array(
        'photo_membre' => $photo,
        'civilite' => $civilite,
        'nom_membre' => $nom_membre,
        'prenom_membre' => $prenom_membre,
        'datenaissance' => $datenaissance,
        'lieunaissance' => $lieunaissance,
        'sexe' => $sexe,
        'contact_membre' => $contact_membre,
        'email' => $email,
    ));

    if ($success) {
        alert_and_redirect("Inscription effectuée avec succès.", 'membres.php');
    }
}
