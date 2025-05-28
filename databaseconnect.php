<?php
require_once(__DIR__ . '/config/mysql.php'); // Cette ligne reste inchangée

try {
    // Ici on crée bien une instance de PDO et non d'une variable non définie
    $mysqlClient = new PDO(
        sprintf('mysql:host=%s;dbname=%s;port=%s;charset=utf8', MYSQL_HOST, MYSQL_NAME, MYSQL_PORT),
        'root',
        'root'
    );
    $mysqlClient->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (Exception $exception) {
    die('Erreur : ' . $exception->getMessage());
}