<?php
// Connexion PDO centralisée.
// Ajustez les identifiants si besoin.
try {
    $bdd = new PDO(
        "mysql:host=localhost;dbname=gestionnaire;charset=utf8mb4",
        'root',
        '',
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (Exception $e) {
    http_response_code(500);
    echo "La base de données n'est pas connectée : " . $e->getMessage();
    exit;
}
