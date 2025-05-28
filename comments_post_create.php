<?php
session_start();

require_once(__DIR__ . '/isConnect.php');
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');

$postData = $_POST;

if (
    !isset($postData['comment']) ||
    !isset($postData['recipe_id']) ||
    !is_numeric($postData['recipe_id']) ||
    !is_numeric($postData['review'])
) {
    echo('Le commentaire ou la note sont invalides.');
    return;
}

$comment = trim(strip_tags($postData['comment']));
$recipeId = (int)$postData['recipe_id'];
$review = (int)$postData['review'];

if ($review < 1 || $review > 5) {
    echo 'La note doit être comprise entre 1 et 5';
    return;
}

if ($comment === '') {
    echo 'Le commentaire ne peut pas être vide.';
    return;
}

$insertRecipe = $mysqlClient->prepare('INSERT INTO comments(comment, recipe_id, user_id, review) VALUES (:comment, :recipe_id, :user_id, :review)');
$insertRecipe->execute([
    'comment' => $comment,
    'recipe_id' => $recipeId,
    'user_id' => $_SESSION['LOGGED_USER']['user_id'],
    'review' => $review,
]);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Site de Recettes - Création de commentaire</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<div class="container mt-5">

    <?php require_once(__DIR__ . '/header.php'); ?>

    <h1 class="mb-4">Commentaire ajouté avec succès !</h1>

    <div class="card mb-3">
        <div class="card-body">
            <p class="card-text"><strong>Note</strong> : <?php echo $review; ?> / 5</p>
            <p class="card-text"><strong>Votre commentaire</strong> : <?php echo strip_tags($comment); ?></p>
        </div>
    </div>

    <a href="index.php" class="btn btn-primary">Retour à l'accueil</a>

</div>

<?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>