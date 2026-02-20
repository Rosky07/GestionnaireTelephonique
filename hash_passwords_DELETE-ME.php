<?php

/**
 * SCRIPT DE HACHAGE DES MOTS DE PASSE ADMINISTRATEUR
 * 
 * ⚠️ À EXÉCUTER UNE FOIS SEULEMENT
 * 
 * Utilisez ce script pour hacher les mots de passe existants.
 * Après exécution, supprimez ce fichier DELETE-ME.php
 */

require_once('connexion.php');

echo "=====================================\n";
echo "🔒 Hachage des mots de passe admin\n";
echo "=====================================\n\n";

// 1️⃣ RÉCUPÉRER LES ADMINISTRATEURS NON-HACHÉS
$req = $bdd->prepare("SELECT id, login_admin, motdepasse_admin FROM administrateur");
$req->execute();
$admins = $req->fetchAll();

if (empty($admins)) {
    echo "❌ Aucun administrateur trouvé !\n";
    exit;
}

echo "📋 Administrateurs trouvés : " . count($admins) . "\n\n";

// 2️⃣ HACHER CHAQUE MOT DE PASSE
$count = 0;
foreach ($admins as $admin) {
    $login = $admin['login_admin'];
    $password = $admin['motdepasse_admin'];

    // Vérifier si c'est déjà hachéisé (hash bcrypt commence par $2)
    if (substr($password, 0, 2) === '$2' || substr($password, 0, 3) === '$2y') {
        echo "⏭️  {$login} : Déjà hachéisé, passage...\n";
        continue;
    }

    // Hacher le mot de passe
    $hash = password_hash($password, PASSWORD_BCRYPT);

    // 🔄 METTRE À JOUR LA BASE DE DONNÉES
    $update = $bdd->prepare("UPDATE administrateur SET motdepasse_admin = :hash WHERE login_admin = :login");
    $success = $update->execute(array(
        'hash' => $hash,
        'login' => $login
    ));

    if ($success) {
        echo "✅ {$login} : Mot de passe hachéisé avec succès !\n";
        echo "   Ancien : " . substr($password, 0, 20) . "...\n";
        echo "   Nouveau : " . substr($hash, 0, 30) . "...\n";
        $count++;
    } else {
        echo "❌ {$login} : Erreur lors de la mise à jour !\n";
    }
}

echo "\n=====================================\n";
echo "✨ Résumé : {$count} mot(s) de passe hachéisé(s)\n";
echo "=====================================\n";
echo "\n⚠️  ACTION : Supprimez ce fichier (DELETE-ME.php) pour la sécurité !\n";

/**
 * Test de vérification du hachage
 * 
 * Pour tester que les mots de passe fonctionnent maintenant :
 * 
 * $motdePasse = "votre_mot_de_passe_en_clair";
 * $hashStocke = "$2y$10$..."; // Récupéré de la BD
 * 
 * if (password_verify($motdePasse, $hashStocke)) {
 *     echo "✅ Mot de passe correct !";
 * } else {
 *     echo "❌ Mot de passe incorrect !";
 * }
 */
