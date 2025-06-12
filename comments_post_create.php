<?php
session_start();
var_dump($SESSION);

require_once(__DIR__ . '/isConnect.php');
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');

$postData = $_POST;

// Vérification des champs du formulaire
if (
    !isset($postData['comment']) ||
    !isset($postData['recipe_id']) ||
    !is_numeric($postData['recipe_id']) ||
    !isset($postData['review']) ||
    !is_numeric($postData['review'])
) {
    $_SESSION['FLASH_MESSAGE'] = 'Le commentaire ou la note sont invalides.';
    header('Location: recipe.php?id=' . $postData['recipe_id']);
    exit;
}

$comment = trim(strip_tags($postData['comment']));
$recipeId = (int)$postData['recipe_id'];
$review = (int)$postData['review'];

// Vérification de la note
if ($review < 1 || $review > 5) {
    $_SESSION['FLASH_MESSAGE'] = 'La note doit être comprise entre 1 et 5.';
    header('Location: index.php?id=' . $recipeId);
    exit;
}

// Vérification du commentaire vide
if ($comment === '') {
    $_SESSION['FLASH_MESSAGE'] = 'Le commentaire ne peut pas être vide.';
    header('Location: index.php?id=' . $recipeId);
    exit;
}

// Vérification que l'utilisateur est connecté
if (!isset($_SESSION['LOGGED_USER']) || !isset($_SESSION['LOGGED_USER']['user_id'])) {
    $_SESSION['FLASH_MESSAGE'] = 'Vous devez être connecté pour laisser un commentaire.';
    header('Location: index.php?id=' . $recipeId);
    exit;
}

// Récupération de l'ID utilisateur
$userId = (int)$_SESSION['LOGGED_USER']['user_id'];

try {
    $insertRecipe = $mysqlClient->prepare('
        INSERT INTO comment (comment, recipe_id, user_id, review)
        VALUES (:comment, :recipe_id, :user_id, :review)
    ');

    $success = $insertRecipe->execute([
        'comment' => $comment,
        'recipe_id' => $recipeId,
        'user_id' => $userId,
        'review' => $review,
    ]);

    if (!$success) {
        throw new Exception('Insertion failed');
    }

    $_SESSION['FLASH_MESSAGE'] = 'Commentaire ajouté avec succès !';
    header('Location: index.php?id=' . $recipeId);
    exit;

} catch (PDOException $e) {
    $_SESSION['FLASH_MESSAGE'] = 'Erreur lors de l\'ajout du commentaire : ' . $e->getMessage();
    header('Location: index.php?id=' . $recipeId);
    exit;
}