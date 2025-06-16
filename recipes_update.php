<?php
session_start();
require_once(__DIR__ . '/isConnect.php');
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo 'ID invalide';
    exit;
}

$id = (int)$_GET['id'];
$stmt = $mysqlClient->prepare('SELECT * FROM recipe WHERE recipe_id = ?');
$stmt->execute([$id]);
$recipe = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$recipe || $recipe['user_id'] !== $_SESSION['LOGGED_USER']['user_id']) {
    echo 'Non autorisé';
    exit;
}

$seasons = $mysqlClient->query('SELECT season_id, title FROM SEASON WHERE is_enabled = 1')->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier : <?= htmlspecialchars($recipe['title']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body class="d-flex flex-column min-vh-100">
<?php require(__DIR__.'/header.php'); ?>
<main class="flex-fill">
    <div class="container py-4">
        <h1>Modifier la recette</h1>
        <form method="POST" action="recipes_post_update.php" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $recipe['recipe_id'] ?>">
            <div class="mb-3">
                <label for="title" class="form-label">Titre de la recette</label>
                <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($recipe['title']) ?>" required>
            </div>
            <div class="mb-3">
                <label for="recipe" class="form-label">Description de la recette</label>
                <textarea class="form-control" id="recipe" name="recipe" rows="6" required><?= htmlspecialchars($recipe['recipe']) ?></textarea>
            </div>
            <div class="mb-3">
                <label for="season" class="form-label">Saison</label>
                <select class="form-select" id="season" name="season_id" required>
                    <?php foreach ($seasons as $s): ?>
                        <option value="<?= $s['season_id'] ?>" <?= $s['season_id'] == $recipe['season_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select class="form-select" id="type" name="type" required>
                    <option value="sucré" <?= $recipe['type'] === 'sucré' ? 'selected' : '' ?>>Sucré</option>
                    <option value="salé" <?= $recipe['type'] === 'salé' ? 'selected' : '' ?>>Salé</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="status" class="form-label">Statut</label>
                <select class="form-select" id="status" name="status" required>
                    <option value="draft" <?= $recipe['status'] === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                    <option value="published" <?= $recipe['status'] === 'published' ? 'selected' : '' ?>>Publié</option>
                </select>
            </div>
            <div class="mb-3">
                <label for="image" class="form-label">Image (facultatif)</label>
                <?php if ($recipe['image']): ?>
                    <img src="Uploads/<?= htmlspecialchars($recipe['image']) ?>" class="recipe-img mb-2" alt="<?= htmlspecialchars($recipe['title']) ?>">
                <?php endif; ?>
                <input type="file" class="form-control" id="image" name="image" accept="image/*">
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Enregistrer</button>
        </form>
    </div>
</main>
<?php require(__DIR__.'/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>