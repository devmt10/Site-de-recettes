<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/functions.php');

if (!isset($_SESSION['LOGGED_USER'])) {
    header('Location: index.php');
    exit;
}

if (
    isset($_POST['title'], $_POST['recipe']) &&
    !empty($_POST['title']) && !empty($_POST['recipe'])
) {
    $title = trim($_POST['title']);
    $recipe = trim($_POST['recipe']);
    $author = $_SESSION['LOGGED_USER']['email'];

    try {
        $stmt = $mysqlClient->prepare('INSERT INTO recipes (title, recipe, author, is_enabled) VALUES (?, ?, ?, TRUE)');
        $stmt->execute([$title, $recipe, $author]);

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

// Redirection vers index.php
header('Location: index.php');
exit();