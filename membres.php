<?php
require_once('connexion.php');

// Récupérer la liste des membres inscrits
$query = $bdd->prepare("SELECT * FROM membre ORDER BY id_membre DESC");
$query->execute();

// Suppression d'un membre
if (isset($_GET['supp']) && $_GET['supp'] != "") {
    $membre_sup = $_GET['supp'];
    $supp_membre = $bdd->prepare("DELETE FROM membre WHERE id_membre = ?");
    $supp_membre->execute([$membre_sup]);
    if ($supp_membre) {
        header('location:membres.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Site de Gestionnaire</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="MDB/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body>
    <?php include('include/header.php'); ?>
    <?php include('include/nav.php'); ?>

    <div class="container my-5 membres-bg rounded-4 shadow-lg">
        <h1 class="text-center futur-title mb-4">LES MEMBRES INSCRITS</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-info">
                    <tr>
                        <th>Photo</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Date de naissance</th>
                        <th>Lieu de Naissance</th>
                        <th>Sexe</th>
                        <th>Contacts</th>
                        <th>Email</th>
                        <th>Date inscription</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($membre = $query->fetch()) { ?>
                        <tr>
                            <td>
                                <img src="photomembres/<?= htmlspecialchars($membre['photo_membre']); ?>" class="img-fluid" style="max-width:70px;max-height:70px;" alt="Photo">
                            </td>
                            <td><?= htmlspecialchars($membre['nom_membre']); ?></td>
                            <td><?= htmlspecialchars($membre['prenom_membre']); ?></td>
                            <td><?= date("d-m-Y", strtotime($membre['datenaissance'])); ?></td>
                            <td><?= htmlspecialchars($membre['lieunaissance']); ?></td>
                            <td><?= htmlspecialchars($membre['sexe']); ?></td>
                            <td><?= htmlspecialchars($membre['contact_membre']); ?></td>
                            <td><?= htmlspecialchars($membre['email']); ?></td>
                            <td>
                                <span class="me-1">
                                    <i class="fa-solid fa-calendar-days text-info"></i>
                                </span>
                                <?php
                                if (!empty($membre['date']) && strtotime($membre['date']) !== false) {
                                    echo date("d-m-Y H:i", strtotime($membre['date']));
                                } else {
                                    echo '<span class="text-warning">Non renseignée</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <div class="d-flex flex-column gap-2">
                                    <a href="profilmembre.php?cle=<?= $membre['id_membre']; ?>" class="btn btn-info btn-sm mb-1">
                                        <i class="fa-solid fa-user"></i> Profil
                                    </a>
                                    <a href="membres.php?supp=<?= $membre['id_membre']; ?>" class="btn btn-danger btn-sm"
                                        onclick="return confirm('Etes-vous sûr de vouloir supprimer ce membre ?');">
                                        <i class="fa-solid fa-trash"></i> Supprimer
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include('include/footer.php'); ?>
    <script src="MDB/js/bootstrap.bundle.min.js"></script>
</body>

</html>