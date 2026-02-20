<?php
// La logique d'inscription est centralisée dans queries.php.
require_once('queries.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Inscription | Gestionnaire</title>
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1"/>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="MDB/css/bootstrap.min.css">
    <link rel="stylesheet" href="MDB/css/mdb.min.css">
    <link rel="stylesheet" href="MDB/css/style.min.css">
    <link rel="stylesheet" href="bootstrap1.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="page-form">
    <?php include('include/header.php'); ?>
    <?php include('include/nav.php'); ?>

    <main class="main-bg py-5">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">
                    <fieldset class="col-12 mb-4 shadow-lg rounded-4 p-4 bg-white futur-fieldset">
                        <legend class="futur-legend text-primary">
                            <i class="fa-solid fa-user-plus me-2"></i>Informations Personnelles
                        </legend>
                        <form
                            class="form-elements"
                            action="<?php echo $_SERVER['PHP_SELF']; ?>"
                            method="POST"
                            enctype="multipart/form-data"
                            autocomplete="off"
                        >
                            <div class="row g-3">
                                <!-- Photo -->
                                <div class="col-md-4">
                                    <div class="form-group required text-center">
                                        <div id="imageResult" class="mb-2">
                                            <img src="avatar.jpg" alt="Avatar" class="img-fluid rounded-circle shadow" style="width:100px;height:100px;">
                                        </div>
                                        <label class="control-label futur-label" for="registerPhoto">
                                            <i class="fa-solid fa-image me-1"></i>Photo
                                        </label>
                                        <input
                                            type="file"
                                            name="photo_membre"
                                            class="form-control futur-input"
                                            id="registerPhoto"
                                            accept="image/*"
                                            required
                                        >
                                    </div>
                                </div>
                                <!-- Civilité -->
                                <div class="col-md-4">
                                    <div class="form-group dropdown-field required">
                                        <label class="control-label futur-label" for="registerCivilite">
                                            <i class="fa-solid fa-user-tag me-1"></i>Civilité
                                        </label>
                                        <select id="registerCivilite" name="civilite" class="form-control futur-input" required>
                                            <option disabled selected value="">-- Veuillez sélectionner --</option>
                                            <option value="Mademoiselle">Mademoiselle</option>
                                            <option value="Monsieur">Monsieur</option>
                                            <option value="Madame">Madame</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Nom -->
                                <div class="col-md-4">
                                    <div class="form-group required">
                                        <label class="control-label futur-label" for="registerNom">
                                            <i class="fa-solid fa-user me-1"></i>Nom
                                        </label>
                                        <input
                                            name="nom_membre"
                                            type="text"
                                            class="form-control futur-input"
                                            id="registerNom"
                                            required
                                            placeholder="Entrer le nom"
                                            autocomplete="family-name"
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <!-- Prénom -->
                                <div class="col-md-4">
                                    <div class="form-group required">
                                        <label class="control-label futur-label" for="registerPrenom">
                                            <i class="fa-solid fa-user-pen me-1"></i>Prénom
                                        </label>
                                        <input
                                            name="prenom_membre"
                                            type="text"
                                            class="form-control futur-input"
                                            id="registerPrenom"
                                            required
                                            placeholder="Entrer les prénoms"
                                            autocomplete="given-name"
                                        >
                                    </div>
                                </div>
                                <!-- Date de naissance -->
                                <div class="col-md-4">
                                    <div class="form-group required">
                                        <label class="control-label futur-label" for="registerDatenaissance">
                                            <i class="fa-solid fa-calendar-days me-1"></i>Date de naissance
                                        </label>
                                        <input
                                            name="datenaissance"
                                            type="date"
                                            class="form-control futur-input"
                                            id="registerDatenaissance"
                                            required
                                        >
                                    </div>
                                </div>
                                <!-- Lieu de naissance -->
                                <div class="col-md-4">
                                    <div class="form-group required">
                                        <label class="control-label futur-label" for="registerLieunaissance">
                                            <i class="fa-solid fa-location-dot me-1"></i>Lieu de naissance
                                        </label>
                                        <input
                                            name="lieunaissance"
                                            type="text"
                                            class="form-control futur-input"
                                            id="registerLieunaissance"
                                            required
                                            placeholder="Lieu de naissance"
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3">
                                <!-- Téléphone -->
                                <div class="col-md-4">
                                    <div class="form-group required">
                                        <label class="control-label futur-label" for="registerTelephone">
                                            <i class="fa-solid fa-phone me-1"></i>Téléphone
                                        </label>
                                        <input
                                            name="contact_membre"
                                            type="tel"
                                            class="form-control futur-input"
                                            id="registerTelephone"
                                            required
                                            placeholder="Votre numéro de téléphone"
                                            autocomplete="tel"
                                        >
                                    </div>
                                </div>
                                <!-- Sexe -->
                                <div class="col-md-4">
                                    <div class="form-group dropdown-field required">
                                        <label class="control-label futur-label" for="registerSexe">
                                            <i class="fa-solid fa-venus-mars me-1"></i>Sexe
                                        </label>
                                        <select id="registerSexe" name="sexe" class="form-control futur-input" required>
                                            <option disabled selected value="">-- Veuillez sélectionner --</option>
                                            <option value="Masculin">Masculin</option>
                                            <option value="Feminin">Féminin</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Email -->
                                <div class="col-md-4">
                                    <div class="form-group required">
                                        <label class="control-label futur-label" for="accountEmail">
                                            <i class="fa-solid fa-envelope me-1"></i>Email
                                        </label>
                                        <input
                                            name="email"
                                            type="email"
                                            class="form-control futur-input"
                                            id="accountEmail"
                                            required
                                            placeholder="Entrer votre email"
                                            autocomplete="email"
                                        >
                                    </div>
                                </div>
                            </div>
                            <div class="row g-3 mt-3">
                                <div class="col-md-6">
                                    <button type="submit" name="envoyer" class="btn futur-btn w-100">
                                        <i class="fa-solid fa-paper-plane me-1"></i>Inscription
                                    </button>
                                </div>
                                <div class="col-md-6">
                                    <button type="reset" name="Supprimer" class="btn btn-danger w-100">
                                        <i class="fa-solid fa-trash me-1"></i>Supprimer
                                    </button>
                                </div>
                            </div>
                        </form>
                    </fieldset>
                </div>
            </div>
        </div>
    </main>

    <?php include('include/footer.php'); ?>
    <script src="MDB/js/jquery-3.4.0.min.js"></script>
    <script src="MDB/js/bootstrap.bundle.min.js"></script>
    <script src="MDB/js/mdb.min.js"></script>
    <script src="popper.min.js"></script>
</body>
</html>
