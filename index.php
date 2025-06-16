<?php
session_start();
require_once __DIR__ . '/config/mysql.php';
require_once __DIR__ . '/databaseconnect.php';
require_once __DIR__ . '/functions.php';

// Charger toutes les recettes actives
$stmt = $mysqlClient->prepare('SELECT * FROM recipe WHERE is_enabled = 1');
$stmt->execute();
$allRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Charger la liste des utilisateurs
$userStmt = $mysqlClient->prepare('SELECT user_id, full_name FROM user');
$userStmt->execute();
$users = $userStmt->fetchAll(PDO::FETCH_ASSOC);

// Charger la liste des saisons
$seasonStmt = $mysqlClient->prepare('SELECT season_id, title FROM SEASON WHERE is_enabled = 1 ORDER BY season_id');
$seasonStmt->execute();
$seasonsRaw = $seasonStmt->fetchAll(PDO::FETCH_ASSOC);
$seasons = [];
foreach ($seasonsRaw as $s) {
    $seasons[(int)$s['season_id']] = $s['title'];
}

// Affichage selon statut + utilisateur
$displayRecipes = [];
$currentUserId = $_SESSION['LOGGED_USER']['user_id'] ?? null;
foreach ($allRecipes as $r) {
    if ($r['status'] === 'published') {
        $displayRecipes[] = $r;
    } elseif ($r['status'] === 'draft' && $currentUserId !== null && (int)$r['user_id'] === (int)$currentUserId) {
        $displayRecipes[] = $r;
    }
}

// Filtres GET
$authorFilter = (int)($_GET['author'] ?? 0);
$seasonFilter = (int)($_GET['season'] ?? 0);
if ($authorFilter > 0) {
    $displayRecipes = array_filter($displayRecipes, fn($r) => (int)$r['user_id'] === $authorFilter);
}
if ($seasonFilter > 0) {
    $displayRecipes = array_filter($displayRecipes, fn($r) => (int)$r['season_id'] === $seasonFilter);
}

// Trier
usort($displayRecipes, fn($a, $b) => $a['season_id'] <=> $b['season_id']);

// Préparer la requête de notation
$ratingStmt = $mysqlClient->prepare('
    SELECT AVG(review) AS avg_review, COUNT(review) AS count_review
    FROM comment
    WHERE recipe_id = ?
');
?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Saveurs & Saisons – Recettes</title>
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
<?php require_once __DIR__ . '/header.php'; ?>
<div class="container flex-fill py-4">
    <h1>Recettes de Saison</h1>
    <p class="intro">
        Découvrez des recettes inspirées par le rythme des saisons, guidées par la météo et la passion culinaire.
    </p>
    <div class="weather-input">
        <input type="text" id="cityInput" class="form-control" placeholder="Entrez une ville (ex : Paris)" aria-label="Ville">
        <button class="btn btn-primary" id="fetchWeather">Voir la météo</button>
    </div>
    <div id="meteo-box">
        <div class="alert alert-info">Cuisine inspirée par le ciel...</div>
    </div>
    <form method="get" class="filters">
        <select name="author" class="form-select">
            <option value="0">Tous les auteurs</option>
            <?php foreach($users as $u): ?>
                <option value="<?= $u['user_id'] ?>" <?= $authorFilter === $u['user_id'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($u['full_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <select name="season" class="form-select">
            <option value="0">Toutes les saisons</option>
            <?php foreach($seasons as $id => $label): ?>
                <option value="<?= $id ?>" <?= $seasonFilter === $id ? 'selected' : '' ?>>
                    <?= htmlspecialchars($label) ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary">Filtrer</button>
    </form>
    <div class="row g-4">
        <?php if (empty($displayRecipes)): ?>
            <div class="col-12 text-center">
                <p class="text-muted">Aucune recette trouvée.</p>
            </div>
        <?php else: ?>
            <?php foreach($displayRecipes as $r):
                $ratingStmt->execute([$r['recipe_id']]);
                $rate = $ratingStmt->fetch(PDO::FETCH_ASSOC);
                $avg = $rate['avg_review'] ? round($rate['avg_review'], 1) : 0;
                $count = $rate['count_review'];
                $seasonId = (int)$r['season_id'];
                $seasonName = htmlspecialchars($seasons[$seasonId] ?? '—');
                $seasonClass = 'season-' . $seasonId;
                $badgeClass = $r['type'] === 'sucré' ? 'badge-sucré' : 'badge-salé';
                $authorName = getAuthorName((int)$r['user_id'], $users);
                $isOwner = !empty($_SESSION['LOGGED_USER']['user_id']) && $_SESSION['LOGGED_USER']['user_id'] === (int)$r['user_id'];
                ?>
                <div class="col-md-6 col-lg-4">
                    <a href="recipes_read.php?id=<?= $r['recipe_id'] ?>" class="text-decoration-none">
                        <div class="card recipe-card d-flex flex-column <?= $seasonClass ?>">
                            <?php if($r['status'] === 'draft'): ?>
                                <div class="ribbon">Brouillon</div>
                            <?php endif; ?>
                            <?php if(!empty($r['image'])): ?>
                                <img src="Uploads/<?= htmlspecialchars($r['image']) ?>" class="recipe-img" alt="<?= htmlspecialchars($r['title']) ?>">
                            <?php else: ?>
                                <div class="no-image">
                                    <span>Aucune image</span>
                                </div>
                            <?php endif; ?>
                            <div class="card-body flex-grow-1">
                                <h5 class="card-title"><?= htmlspecialchars($r['title']) ?></h5>
                                <div class="season-label">Saison : <?= $seasonName ?></div>
                                <span class="badge <?= $badgeClass ?> text-white">
                                    <?= ucfirst(htmlspecialchars($r['type'])) ?>
                                </span>
                                <p class="card-text">
                                    <?= nl2br(htmlspecialchars(mb_strimwidth($r['recipe'], 0, 80, '...'))) ?>
                                </p>
                                <p class="author">Par <?= $authorName ?></p>
                                <div class="rating">
                                    <?php for($i = 1; $i <= 5; $i++): ?>
                                        <?php if($i <= floor($avg)): ?>
                                            <i class="bi bi-star-fill"></i>
                                        <?php elseif($i - $avg < 1): ?>
                                            <i class="bi bi-star-half"></i>
                                        <?php else: ?>
                                            <i class="bi bi-star"></i>
                                        <?php endif; ?>
                                    <?php endfor; ?>
                                    <small>(<?= $count ?> avis)</small>
                                </div>
                            </div>
                            <?php if($isOwner): ?>
                                <div class="card-footer bg-transparent border-0 d-flex gap-2">
                                    <a href="recipes_update.php?id=<?= $r['recipe_id'] ?>" class="btn btn-outline-dark btn-sm">
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <?php if($r['status'] === 'draft'): ?>
                                        <a href="recipes_publish.php?id=<?= $r['recipe_id'] ?>" class="btn btn-success btn-sm">
                                            <i class="bi bi-upload"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a href="recipes_delete.php?id=<?= $r['recipe_id'] ?>" class="btn btn-outline-danger btn-sm" onclick="return confirm('Supprimer cette recette ?')">
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php require_once __DIR__ . '/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script src="js/weather.js?v=<?= time() ?>"></script>
</body>
</html>