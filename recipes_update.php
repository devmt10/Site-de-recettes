<?php
session_start();
require_once(__DIR__ . '/isConnect.php');
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');

// 1) Validate & fetch recipe
if (empty($_GET['id']) || !is_numeric($_GET['id'])) {
    echo '<p class="text-danger text-center mt-5">Identifiant invalide.</p>';
    exit;
}
$recipeId = (int)$_GET['id'];

// 2) Load recipe
$stmt = $mysqlClient->prepare('SELECT * FROM recipe WHERE recipe_id = ?');
$stmt->execute([$recipeId]);
$recipe = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$recipe) {
    echo '<p class="text-danger text-center mt-5">Recette introuvable.</p>';
    exit;
}

// 3) Ownership
if ($recipe['user_id'] !== $_SESSION['LOGGED_USER']['user_id']) {
    echo '<p class="text-danger text-center mt-5">Pas autorisé·e.</p>';
    exit;
}

// 4) Load seasons
$seasonStmt = $mysqlClient->prepare('SELECT season_id, title FROM SEASON WHERE is_enabled = 1');
$seasonStmt->execute();
$seasons = $seasonStmt->fetchAll(PDO::FETCH_ASSOC);

// 5) Status & type options
$statusOpts = ['draft'=>'Brouillon','published'=>'Publié'];
$typeOpts   = ['sucré'=>'Sucrée','salé'=>'Salée'];
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier : <?= htmlspecialchars($recipe['title']) ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Vogue Serif -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            background: #f9f6f1;
            font-family: 'Georgia', serif;
            color: #333;
        }
        /* Make header sticky */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        main {
            padding-top: 1rem; /* ensure content sits below navbar */
        }
        h1 {
            font-family: 'Playfair Display', serif;
            text-align: center;
            margin: 2rem 0 1.5rem;
        }
        .container-form {
            max-width: 720px;
            margin: 0 auto 2rem;
        }
        .current-img {
            max-height: 300px;
            width: 100%;
            object-fit: cover;
            border-radius: .375rem;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            margin-bottom: 1rem;
        }
        .btn-primary {
            background-color: #6b5b95;
            border-color: #6b5b95;
        }
        .btn-primary:hover {
            background-color: #5a4a84;
            border-color: #5a4a84;
        }
    </style>
</head>
<body>
<?php require_once(__DIR__ . '/header.php'); ?>

<main>
    <div class="container bg-white p-4 shadow-sm container-form">
        <h1>Modifier : <?= htmlspecialchars($recipe['title']) ?></h1>

        <?php if (!empty($_SESSION['UPDATE_ERROR'])): ?>
            <div class="alert alert-danger">
                <?= htmlspecialchars($_SESSION['UPDATE_ERROR']) ?>
                <?php unset($_SESSION['UPDATE_ERROR']); ?>
            </div>
        <?php endif; ?>

        <form action="recipes_post_update.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?= $recipeId ?>">

            <!-- Title -->
            <div class="mb-3">
                <label for="title" class="form-label">Titre</label>
                <input
                    type="text"
                    id="title"
                    name="title"
                    class="form-control"
                    required
                    value="<?= htmlspecialchars($recipe['title']) ?>">
            </div>

            <!-- Description -->
            <div class="mb-3">
                <label for="recipe" class="form-label">Description</label>
                <textarea
                    id="recipe"
                    name="recipe"
                    class="form-control"
                    rows="5"
                    required><?= htmlspecialchars($recipe['recipe']) ?></textarea>
            </div>

            <!-- Season & Type & Status -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label class="form-label">Saison</label>
                    <select name="season" class="form-select" required>
                        <?php foreach ($seasons as $s): ?>
                            <option value="<?= $s['season_id'] ?>" <?= $recipe['season_id']==$s['season_id']?'selected':''?>>
                                <?= htmlspecialchars($s['title']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select" required>
                        <?php foreach ($typeOpts as $val=>$label): ?>
                            <option value="<?= $val ?>" <?= $recipe['type']===$val?'selected':''?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-4">
                    <label class="form-label">Statut</label>
                    <select name="status" class="form-select" required>
                        <?php foreach ($statusOpts as $val=>$label): ?>
                            <option value="<?= $val ?>" <?= $recipe['status']===$val?'selected':''?>>
                                <?= $label ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Current Image -->
            <div class="mb-3">
                <label class="form-label">Image actuelle</label>
                <?php if ($recipe['image']): ?>
                    <img src="uploads/<?= htmlspecialchars($recipe['image']) ?>"
                         class="current-img img-fluid" alt="">
                <?php else: ?>
                    <p class="text-muted">Aucune image</p>
                <?php endif; ?>
            </div>

            <!-- Upload New Image -->
            <div class="mb-4">
                <label for="image" class="form-label">Ajouter / Modifier l’image</label>
                <input
                    type="file"
                    id="image"
                    name="image"
                    accept="image/*"
                    class="form-control">
                <div class="form-text">Formats JPG/PNG/GIF, max. 2 Mo</div>
            </div>

            <!-- Submit -->
            <div class="text-center">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-upload me-1"></i> Mettre à jour
                </button>
            </div>
        </form>
    </div>
</main>

<?php require_once(__DIR__ . '/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
