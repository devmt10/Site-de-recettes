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

// Load other fields
$seasons = $mysqlClient->query('SELECT season_id, title FROM SEASON WHERE is_enabled = 1')->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier : <?= htmlspecialchars($recipe['title']) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php require(__DIR__.'/header.php'); ?>
<div class="container py-4">
    <h1>Modifier la recette</h1>
    <form method="POST" action="recipes_post_update.php" enctype="multipart/form-data">
        <input type="hidden" name="id" value="<?= $recipe['recipe_id'] ?>">

        <div class="mb-3">
            <label class="form-label">Titre</label>
            <input type="text" class="form-control" name="title" value="<?= htmlspecialchars($recipe['title']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Description</label>
            <textarea class="form-control" name="recipe" rows="5" required><?= htmlspecialchars($recipe['recipe']) ?></textarea>
        </div>

        <div class="row mb-3">
            <div class="col">
                <label class="form-label">Saison</label>
                <select class="form-select" name="season_id" required>
                    <?php foreach ($seasons as $s): ?>
                        <option value="<?= $s['season_id'] ?>" <?= $s['season_id'] == $recipe['season_id'] ? 'selected' : '' ?>>
                            <?= htmlspecialchars($s['title']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col">
                <label class="form-label">Type</label>
                <select class="form-select" name="type" required>
                    <option value="sucré" <?= $recipe['type'] === 'sucré' ? 'selected' : '' ?>>Sucrée</option>
                    <option value="salé"  <?= $recipe['type'] === 'salé'  ? 'selected' : '' ?>>Salée</option>
                </select>
            </div>
            <div class="col">
                <label class="form-label">Statut</label>
                <select class="form-select" name="status" required>
                    <option value="draft"     <?= $recipe['status'] === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                    <option value="published" <?= $recipe['status'] === 'published' ? 'selected' : '' ?>>Publié</option>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label class="form-label">Image actuelle</label>
            <?php if ($recipe['image']): ?>
                <img src="uploads/<?= htmlspecialchars($recipe['image']) ?>" alt="" class="img-fluid rounded mb-2">
            <?php else: ?>
                <p class="text-muted">Aucune image</p>
            <?php endif; ?>
            <input type="file" name="image" class="form-control">
        </div>

        <div class="text-center">
            <button class="btn btn-primary" type="submit">Enregistrer</button>
        </div>
    </form>
</div>
<?php require(__DIR__.'/footer.php'); ?>
</body>
</html>
