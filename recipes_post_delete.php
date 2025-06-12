<?php
require_once(__DIR__ . '/databaseconnect.php'); // Connexion à la base

if (!isset($_POST['id'])) {
    die('Identifiant de la recette manquant.');
}

$deleteRecipeStatement = $mysqlClient->prepare('DELETE FROM recipe WHERE recipe_id = :id');
$deleteRecipeStatement->execute([
    'id' => $_POST['id']
]);

// Redirection vers index.php
header('Location: index.php');
exit();