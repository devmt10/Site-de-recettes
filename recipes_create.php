<?php
session_start();
require_once(__DIR__ . '/isConnect.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de Recettes - Ajout de recette</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100">
<div class="container">
    <?php require_once(__DIR__ . '/header.php'); ?>

    <h1>Ajouter une recette</h1>

    <form action="recipes_post_create.php" method="POST">
        <!-- Titre -->
        <div class="mb-3">
            <label for="title" class="form-label">Titre de la recette</label>
            <input type="text" class="form-control" id="title" name="title" required>
        </div>

        <!-- Description -->
        <div class="mb-3">
            <label for="recipe" class="form-label">Description de la recette</label>
            <textarea class="form-control" id="recipe" name="recipe" required></textarea>
        </div>

        <!-- Saison -->
        <div class="mb-3">
            <label for="season" class="form-label">Saison</label>
            <select class="form-select" id="season" name="season" required>
                <option value="">-- Sélectionner une saison --</option>
                <option value="printemps">Printemps</option>
                <option value="été">Été</option>
                <option value="automne">Automne</option>
                <option value="hiver">Hiver</option>
            </select>
        </div>

        <!-- Type : Salé ou Sucré -->
        <div class="mb-3">
            <label class="form-label">Type de recette</label>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type" id="sale" value="salé" required>
                <label class="form-check-label" for="sale">Salé</label>
            </div>
            <div class="form-check">
                <input class="form-check-input" type="radio" name="type" id="sucre" value="sucré">
                <label class="form-check-label" for="sucre">Sucré</label>
            </div>
        </div>

        <!-- Bouton envoyer -->
        <button type="submit" class="btn btn-primary">Envoyer</button>
    </form>
</div>

<?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>