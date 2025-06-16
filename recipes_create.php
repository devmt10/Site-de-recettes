<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');

// Fetch seasons
$stmt = $mysqlClient->prepare('SELECT season_id, title FROM SEASON WHERE is_enabled = 1 ORDER BY season_id');
$stmt->execute();
$seasons = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Proposer une recette – Saveurs & Saisons</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body class="d-flex flex-column min-vh-100">
<?php require_once(__DIR__ . '/header.php'); ?>
<main class="flex-fill py-4">
    <div class="container">
        <h1>Proposer une recette</h1>
        <?php if (!isset($_SESSION['LOGGED_USER'])): ?>
            <div class="card contact-card">
                <div class="card-body text-center">
                    <h3 class="mb-3">Connectez-vous pour proposer une recette</h3>
                    <p class="mb-4">Vous devez être authentifié pour partager vos créations culinaires.</p>
                    <div class="d-flex justify-content-center gap-2">
                        <a href="login.php" class="btn btn-primary btn-sm">
                            <i class="bi bi-box-arrow-in-right me-1"></i> Connexion
                        </a>
                        <a href="registration.php" class="btn btn-primary btn-sm">
                            <i class="bi bi-person-plus-fill me-1"></i> Inscription
                        </a>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="card contact-card">
                <div class="card-body">
                    <form action="recipes_post_create.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="title" class="form-label">Titre de la recette</label>
                            <input type="text" class="form-control" id="title" name="title" required placeholder="Ex. Tarte aux pommes d'automne">
                        </div>
                        <div class="mb-3">
                            <label for="recipe" class="form-label">Description de la recette</label>
                            <textarea class="form-control" id="recipe" name="recipe" rows="6" required placeholder="Décrivez les étapes de votre recette…"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="image" class="form-label">Image de la recette (optionnel)</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        </div>
                        <div class="mb-3">
                            <label for="season_id" class="form-label">Saison</label>
                            <select class="form-select" id="season_id" name="season_id" required>
                                <option value="">Choisissez une saison</option>
                                <?php foreach ($seasons as $season): ?>
                                    <option value="<?= $season['season_id'] ?>"><?= htmlspecialchars($season['title']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Type de recette</label>
                            <div>
                                <input type="radio" id="type_sucré" name="type" value="sucré" required>
                                <label for="type_sucré" class="me-3">Sucré</label>
                                <input type="radio" id="type_salé" name="type" value="salé">
                                <label for="type_salé">Salé</label>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Statut</label>
                            <div>
                                <input type="radio" id="status_draft" name="status" value="draft" checked>
                                <label for="status_draft" class="me-3">Brouillon</label>
                                <input type="radio" id="status_published" name="status" value="published">
                                <label for="status_published">Publiée</label>
                            </div>
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-send-fill me-1"></i> Envoyer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php require_once(__DIR__ . '/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>