<?php
require_once('connexion.php');

// Suppression d'un membre (via POST pour plus de sécurité).
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_membre'])) {
    $membreId = filter_input(INPUT_POST, 'membre_id', FILTER_VALIDATE_INT);
    if ($membreId && $membreId > 0) {
        // Vérifier que le membre existe avant de supprimer
        $check = $bdd->prepare("SELECT id_membre FROM membre WHERE id_membre = ?");
        $check->execute(array($membreId));
        if ($check->fetch()) {
            $supp_membre = $bdd->prepare("DELETE FROM membre WHERE id_membre = ?");
            $supp_membre->execute(array($membreId));
        }
    }
    header('Location: membres.php');
    exit;
}

// Récupérer la liste des membres inscrits.
$query = $bdd->prepare("SELECT * FROM membre ORDER BY id_membre DESC");
$query->execute();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Liste des Membres | Gestionnaire</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;600;700&family=Source+Sans+3:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="MDB/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
</head>

<body class="page-members">
    <?php include('include/header.php'); ?>
    <?php include('include/nav.php'); ?>

    <main class="container my-5 membres-bg rounded-4 shadow-lg">
        <div class="d-flex flex-column flex-lg-row justify-content-between align-items-lg-center mb-4 gap-3">
            <div>
                <h1 class="futur-title mb-1">Les Membres Inscrits</h1>
                <p class="text-muted mb-0">Suivi et gestion des profils enregistrés.</p>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered table-hover align-middle">
                <thead class="table-info">
                    <tr>
                        <th>Photo</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Date de naissance</th>
                        <th>Lieu de naissance</th>
                        <th>Sexe</th>
                        <th>Contacts</th>
                        <th>Email</th>
                        <th>Date inscription</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($membre = $query->fetch()) { ?>
                        <?php
                        $photo = !empty($membre['photo_membre'])
                            ? 'photomembres/' . $membre['photo_membre']
                            : 'avatar.jpg';
                        ?>
                        <tr>
                            <td>
                                <img
                                    src="<?= htmlspecialchars($photo); ?>"
                                    class="img-fluid member-avatar"
                                    alt="Photo"
                                    loading="lazy">
                            </td>
                            <td><?= htmlspecialchars($membre['nom_membre']); ?></td>
                            <td><?= htmlspecialchars($membre['prenom_membre']); ?></td>
                            <td><?= (!empty($membre['datenaissance']) && strtotime($membre['datenaissance']) !== false) ? date("d-m-Y", strtotime($membre['datenaissance'])) : '<span class="text-warning">Non renseignée</span>'; ?></td>
                            <td><?= htmlspecialchars($membre['lieunaissance']); ?></td>
                            <td><?= htmlspecialchars($membre['sexe']); ?></td>
                            <td><?= htmlspecialchars($membre['contact_membre']); ?></td>
                            <td><?= htmlspecialchars($membre['email']); ?></td>
                            <td>
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
                                    <a href="profilmembre.php?cle=<?= $membre['id_membre']; ?>" class="btn btn-info btn-sm">
                                        <i class="fa-solid fa-user"></i> Profil
                                    </a>
                                    <form method="POST" style="display:inline;margin:0;padding:0;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce membre ?');">
                                        <input type="hidden" name="membre_id" value="<?= $membre['id_membre']; ?>">
                                        <button type="submit" name="delete_membre" class="btn btn-danger btn-sm w-100">
                                            <i class="fa-solid fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php include('include/footer.php'); ?>
    <script src="MDB/js/bootstrap.bundle.min.js"></script>
</body>

</html>