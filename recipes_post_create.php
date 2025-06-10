<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/functions.php');

if (!isset($_SESSION['LOGGED_USER'])) {
    header('Location: index.php');
    exit;
}

// Vérifie la présence de tous les champs
if (
    isset($_POST['title'], $_POST['recipe'], $_POST['season'], $_POST['type']) &&
    !empty($_POST['title']) &&
    !empty($_POST['recipe']) &&
    !empty($_POST['season']) &&
    !empty($_POST['type'])
) {
    $title = trim($_POST['title']);
    $recipe = trim($_POST['recipe']);
    $season = trim($_POST['season']);
    $type = trim($_POST['type']);
    $author = $_SESSION['LOGGED_USER']['email'];

    try {
        $stmt = $mysqlClient->prepare(
            'INSERT INTO recipes (title, recipe, season, type, author, is_enabled) 
             VALUES (?, ?, ?, ?, ?, TRUE)'
        );
        $stmt->execute([$title, $recipe, $season, $type, $author]);

        $_SESSION['FLASH_MESSAGE'] = 'Recette ajoutée avec succès !';
    } catch (PDOException $e) {
        $_SESSION['FLASH_MESSAGE'] = 'Erreur lors de l\'ajout : ' . $e->getMessage();
    }

    header('Location: index.php');
    exit;
} else {
    $_SESSION['FLASH_MESSAGE'] = 'Veuillez remplir tous les champs.';
    header('Location: recipes_create.php');
    exit;
}