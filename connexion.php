<?php
try {
    // On se connecte à la base de données
    $bdd = new PDO("mysql:host=localhost;dbname=gestionnaire", 'root', '');
    // Option pour afficher les erreurs PDO
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $e) {
    echo "La base de données n'est pas connectée : " . $e->getMessage();
}
?>