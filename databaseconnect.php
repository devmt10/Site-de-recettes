<?php
// Connexion à la base de données
$mysqlClient = new PDO(
    'mysql:host=localhost;dbname=partage_de_recettes;charset=utf8',
    'root',
    'root',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);