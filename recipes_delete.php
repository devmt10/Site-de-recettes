<?php
session_start();
require_once(__DIR__ . '/isConnect.php');

$getData = $_GET;
if (!isset($getData['id']) || !is_numeric($getData['id'])) {
    echo('Il faut un identifiant pour supprimer la recette.');
    return;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Supprimer la recette – Saveurs & Saisons</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body class="d-flex flex-column min-vh-100">
<div class="container py-4">
    <?php require_once(__DIR__ . '/header.php'); ?>
    <h1>Supprimer la recette ?</h1>
    <form action="recipes_post_delete.php" method="POST" class="form-container">
        <div class="mb-3 visually-hidden">
            <label for="id" class="form-label">Identifiant de la recette</label>
            <input type="hidden" class="form-control" id="id" name="id" value="<?= $getData['id']; ?>">
        </div>
        <div class="text-center">
            <button type="submit" class="btn btn-danger">La suppression est définitive</button>
        </div>
    </form>
</div>
<?php require_once(__DIR__ . '/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>