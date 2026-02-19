<?php
session_start();
require_once('connexion.php');
$erreur = null;

if (isset($_POST['connecter'])) {
    $login_admin = htmlentities($_POST['login_admin']);
    $motdepasse_admin = htmlentities($_POST['motdepasse_admin']);

    if ($login_admin && $motdepasse_admin) {
        $req = $bdd->prepare("SELECT * FROM administrateur WHERE login_admin=:login_admin and motdepasse_admin=:motdepasse_admin");
        $req->execute([
            'login_admin' => $login_admin,
            'motdepasse_admin' => $motdepasse_admin
        ]);
        $resultat = $req->fetch();
        if ($resultat) {
            $_SESSION['login_admin'] = $resultat['login_admin'];
            $_SESSION['motdepasse_admin'] = $resultat['motdepasse_admin'];
            header('location:identification.php');
            exit;
        } else {
            $erreur = "Login ou mot de passe incorrect";
        }
    } else {
        $erreur = "Veuillez remplir tous les champs";
    }
}

// Traitement du formulaire d'inscription membre
if (isset($_POST['envoyer'])) {
    $civilite = htmlentities($_POST['civilite']);
    $nom_membre = htmlentities($_POST['nom_membre']);
    $prenom_membre = htmlentities($_POST['prenom_membre']);
    $datenaissance = htmlentities($_POST['datenaissance']);
    $lieunaissance = htmlentities($_POST['lieunaissance']);
    $sexe = htmlentities($_POST['sexe']);
    $contact_membre = htmlentities($_POST['contact_membre']);
    $email = htmlentities($_POST['email']);

    if (isset($_FILES['photo_membre']) && $_FILES['photo_membre']['error'] == 0) {
        move_uploaded_file($_FILES['photo_membre']['tmp_name'], 'photomembres/' . $_FILES['photo_membre']['name']);
        $photo = $_FILES['photo_membre']['name'];
    } else {
        $photo = '';
    }

    // Vérification d'unicité sur chaque champ clé
    $verif = $bdd->prepare('SELECT * FROM membre WHERE nom_membre = :nom_membre OR prenom_membre = :prenom_membre OR email = :email OR contact_membre = :contact_membre OR datenaissance = :datenaissance');
    $verif->execute([
        'nom_membre' => $nom_membre,
        'prenom_membre' => $prenom_membre,
        'email' => $email,
        'contact_membre' => $contact_membre,
        'datenaissance' => $datenaissance
    ]);
    if ($verif->fetch()) {
        echo "<script>alert('Erreur : Une information saisie existe déjà pour un autre membre.'); window.history.back();</script>";
        exit();
    }

    $req = $bdd->prepare('INSERT INTO membre (photo_membre, civilite, nom_membre, prenom_membre, datenaissance, lieunaissance, sexe, contact_membre, email) VALUES (:photo_membre, :civilite, :nom_membre, :prenom_membre, :datenaissance, :lieunaissance, :sexe, :contact_membre, :email)');
    $success = $req->execute([
        'photo_membre' => $photo,
        'civilite' => $civilite,
        'nom_membre' => $nom_membre,
        'prenom_membre' => $prenom_membre,
        'datenaissance' => $datenaissance,
        'lieunaissance' => $lieunaissance,
        'sexe' => $sexe,
        'contact_membre' => $contact_membre,
        'email' => $email
    ]);

    if ($success) {
        echo "<script>alert('Inscription effectuée avec succès'); window.location='membres.php';</script>";
        exit();
    }
}
