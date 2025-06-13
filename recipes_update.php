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
    <style>
        body {
            background-color: #f9f6f1;
            font-family: 'Georgia', serif;
            color: #333;
        }
        h1 {
            font-family: 'Playfair Display', serif;
            text-align: center;
            margin: 2rem 0 1.5rem;
        }
        .form-container {
            max-width: 720px;
            margin: 0 auto 2rem;
            background: #fff;
            border: none;
            border-radius: .5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 2rem;
        }
        .btn-primary {
            background-color: #6b5b95;
            border-color: #6b5b95;
        }
        .btn-primary:hover {
            background-color: #5a4a84;
            border-color: #5a4a84;
        }
        .form-label {
            font-weight: bold;
        }
        img.preview {
            max-height: 300px;
            object-fit: cover;
            width: 100%;
            margin-bottom: 1rem;
            border-radius: .375rem;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<?php require(__DIR__.'/header.php'); ?>

<main class="flex-fill">
    <div class="container form-container">
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
                <div class="col-md-4">
                    <label class="form-label">Saison</label>
                    <select class="form-select" name="season_id" required>
                        <?php foreach ($seasons as $s): ?>
                            <option value="<?= $s['season_id'] ?>" <?= $s['season_id'] == $recipe['season_id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($s['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Type</label>
                    <select class="form-select" name="type" required>
                        <option value="sucré" <?= $recipe['type'] === 'sucré' ? 'selected' : '' ?>>Sucrée</option>
                        <option value="salé"  <?= $recipe['type'] === 'salé'  ? 'selected' : '' ?>>Salée</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Statut</label>
                    <select class="form-select" name="status" required>
                        <option value="draft" <?= $recipe['status'] === 'draft' ? 'selected' : '' ?>>Brouillon</option>
                        <option value="published" <?= $recipe['status'] === 'published' ? 'selected' : '' ?>>Publié</option>
                    </select>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Image actuelle</label>
                <?php if ($recipe['image']): ?>
                    <img src="uploads/<?= htmlspecialchars($recipe['image']) ?>" class="preview" alt="">
                <?php else: ?>
                    <p class="text-muted">Aucune image</p>
                <?php endif; ?>
                <input type="file" name="image" class="form-control">
            </div>

            <div class="text-center">
                <button class="btn btn-primary px-4" type="submit">
                    <i class="bi bi-upload me-1"></i> Enregistrer
                </button>
            </div>
        </form>
    </div>
</main>

<?php require(__DIR__.'/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
