<?php
session_start();

require_once __DIR__ . '/config/mysql.php';
require_once __DIR__ . '/databaseconnect.php';
require_once __DIR__ . '/functions.php';  // getRecipes(), getAuthorName()

// 1) Charger toutes les recettes actives
$stmt = $mysqlClient->prepare('SELECT * FROM recipe WHERE is_enabled = 1');
$stmt->execute();
$allRecipes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 2) Charger la liste des utilisateurs (pour le filtre Auteur et l’affichage)
$userStmt = $mysqlClient->prepare('SELECT user_id, full_name FROM user');
$userStmt->execute();
$users = $userStmt->fetchAll(PDO::FETCH_ASSOC);

// 3) Charger la liste des saisons (pour le filtre et l’affichage)
$seasonStmt = $mysqlClient->prepare('SELECT season_id, title FROM SEASON WHERE is_enabled = 1 ORDER BY season_id');
$seasonStmt->execute();
$seasonsRaw = $seasonStmt->fetchAll(PDO::FETCH_ASSOC);
$seasons = [];
foreach ($seasonsRaw as $s) {
    $seasons[(int)$s['season_id']] = $s['title'];
}

// 4) Séparer publiées vs. brouillons de l’utilisateur
$published = getRecipes($allRecipes);
$drafts = [];
if (!empty($_SESSION['LOGGED_USER']['user_id'])) {
    $me = $_SESSION['LOGGED_USER']['user_id'];
    foreach ($allRecipes as $r) {
        if ($r['status'] === 'draft' && (int)$r['user_id'] === $me) {
            $drafts[] = $r;
        }
    }
}

// 5) Fusionner sans doublons par recipe_id
$combined = array_merge($published, $drafts);
$byId = [];
foreach ($combined as $r) {
    $byId[$r['recipe_id']] = $r;
}
$displayRecipes = array_values($byId);

// 6) Appliquer filtres GET (Auteur & Saison)
$authorFilter = (int)($_GET['author'] ?? 0);
$seasonFilter = (int)($_GET['season'] ?? 0);

if ($authorFilter > 0) {
    $displayRecipes = array_filter($displayRecipes, fn($r) => (int)$r['user_id'] === $authorFilter);
}

if ($seasonFilter > 0) {
    $displayRecipes = array_filter($displayRecipes, fn($r) => (int)$r['season_id'] === $seasonFilter);
}

// 7) Trier par saison
usort($displayRecipes, fn($a, $b) => $a['season_id'] <=> $b['season_id']);

// 8) Préparer la requête de notation
$ratingStmt = $mysqlClient->prepare('
    SELECT AVG(review) AS avg_review, COUNT(review) AS count_review
      FROM comment
     WHERE recipe_id = ?
');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Saveurs & Saisons – Recettes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <!-- Vogue-style serif -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

    <style>
        /* Global Styles */
        body {
            background-color: #f9f6f1;
            font-family: 'Georgia', serif;
            color: #333;
            line-height: 1.6;
        }

        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            text-align: center;
            margin: 2rem 0 1rem;
            color: #2d2d2d;
        }

        .intro {
            max-width: 720px;
            margin: 0 auto 2rem;
            font-size: 1.1rem;
            line-height: 1.6;
            text-align: center;
            color: #555;
        }

        /* Weather Section */
        .weather-input {
            max-width: 600px;
            margin: 0 auto 1rem;
            display: flex;
            justify-content: center;
            gap: 0.5rem;
        }
        .weather-input .form-control {
            max-width: 350px;
            font-size: 0.95rem;
            border: 1px solid #d4c9b8;
            border-radius: 0;
            padding: 0.5rem;
            background: #fff;
        }
        .weather-input .btn {
            background: #2d2d2d;
            border: none;
            color: #fff;
            font-family: 'Georgia', serif;
            font-size: 0.95rem;
            padding: 0.5rem 1rem;
            border-radius: 0;
            transition: background 0.3s ease;
        }
        .weather-input .btn:hover {
            background: #4a4a4a;
        }
        #meteo-box {
            max-width: 600px;
            margin: 0 auto 2rem;
        }
        #meteo-box .alert {
            font-size: 0.95rem;
            color: #555;
            background: #fff;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border-radius: 0;
            padding: 0.75rem;
            text-align: center;
        }
        #meteo-box .card {
            border: none;
            background: linear-gradient(135deg, #f9f6f1 0%, #fff 100%);
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
            border-radius: 0;
            padding: 1.25rem;
            position: relative;
            overflow: hidden;
            transition: transform 0.3s ease;
        }
        #meteo-box .card:hover {
            transform: translateY(-4px);
        }
        #meteo-box .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.8rem;
            color: #2d2d2d;
            margin-bottom: 0.5rem;
        }
        #meteo-box .card-text {
            font-size: 1rem;
            color: #555;
            line-height: 1.5;
        }
        #meteo-box .btn {
            background: #2d2d2d;
            color: #fff;
            font-family: 'Georgia', serif;
            font-size: 0.9rem;
            padding: 0.5rem 1rem;
            border-radius: 0;
            transition: background 0.3s ease;
        }
        #meteo-box .btn:hover {
            background: #4a4a4a;
        }
        /* Seasonal Weather Accents */
        #meteo-box .season-1 { border-left: 4px solid #2ecc71; } /* Printemps */
        #meteo-box .season-2 { border-left: 4px solid #f1c40f; } /* Été */
        #meteo-box .season-3 { border-left: 4px solid #e67e22; } /* Automne */
        #meteo-box .season-4 { border-left: 4px solid #3498db; } /* Hiver */
        /* Weather Icon Animation */
        #meteo-box .weather-icon {
            position: absolute;
            top: 1rem;
            right: 1rem;
            font-size: 2rem;
            opacity: 0.3;
            animation: pulse 3s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.2); }
            100% { transform: scale(1); }
        }

        /* Filters */
        .filters {
            max-width: 600px;
            margin: 0 auto 2rem;
            display: flex;
            justify-content: center;
            gap: 1rem;
        }
        .filters select {
            font-family: 'Georgia', serif;
            font-size: 0.95rem;
            border: 1px solid #d4c9b8;
            border-radius: 0;
            padding: 0.5rem;
            background: #fff;
        }
        .filters button {
            background: #2d2d2d;
            border: none;
            color: #fff;
            font-family: 'Georgia', serif;
            font-size: 0.95rem;
            padding: 0.5rem 1rem;
            border-radius: 0;
            transition: background 0.3s ease;
        }
        .filters button:hover {
            background: #4a4a4a;
        }

        /* Recipe Cards */
        .card {
            border: none;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            border-radius: 0;
            transition: transform 0.3s ease;
            position: relative;
            height: 100%;
        }
        .card:hover {
            transform: translateY(-4px);
        }
        .recipe-img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
        .no-image {
            width: 100%;
            height: 250px;
            background: #f5f0e8;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.95rem;
            color: #777;
            border-top-left-radius: 0;
            border-top-right-radius: 0;
        }
        .card-body {
            padding: 1rem;
            text-align: left;
        }
        .card-title {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            margin-bottom: 0.25rem;
            color: #2d2d2d;
        }
        .card-title a {
            color: #2d2d2d;
            text-decoration: none;
        }
        .card-title a:hover {
            color: #555;
        }
        .season-label {
            font-size: 0.85rem;
            color: #777;
            margin-bottom: 0.5rem;
        }
        .badge-sucré {
            background: #b85c8c !important;
            font-size: 0.8rem;
        }
        .badge-salé {
            background: #4a7c59 !important;
            font-size: 0.8rem;
        }
        .card-text {
            font-size: 0.9rem;
            color: #555;
            margin-bottom: 0.75rem;
        }
        .author {
            font-size: 0.85rem;
            font-style: italic;
            color: #777;
        }
        .rating {
            color: #d4a373; /* Gold */
            font-size: 0.9rem;
            margin-bottom: 0.5rem;
        }
        .rating small {
            color: #777;
            font-size: 0.75rem;
        }
        .ribbon {
            position: absolute;
            top: 0.5rem;
            right: 0;
            background: #d4a373; /* Gold */
            color: #fff;
            padding: 0.25rem 0.75rem;
            font-size: 0.75rem;
            font-weight: bold;
            border-top-right-radius: 0;
            border-bottom-left-radius: 0;
        }
        /* Season Borders */
        .season-1 { border-left: 4px solid #2ecc71; } /* Printemps */
        .season-2 { border-left: 4px solid #f1c40f; } /* Été */
        .season-3 { border-left: 4px solid #e67e22; } /* Automne */
        .season-4 { border-left: 4px solid #3498db; } /* Hiver */

        /* Buttons */
        .btn-outline-dark, .btn-outline-danger, .btn-success {
            font-size: 0.85rem;
            border-radius: 0;
            padding: 0.25rem 0.5rem;
        }
        .btn-outline-dark:hover {
            background: #2d2d2d;
            color: #fff;
        }
        .btn-outline-danger:hover {
            background: #dc3545;
            color: #fff;
        }
        .btn-success:hover {
            background: #28a745;
        }

        /* Responsive Adjustments */
        @media (max-width: 768px) {
            h1 { font-size: 2rem; }
            .intro { font-size: 1rem; }
            .weather-input { flex-direction: column; }
            .weather-input .form-control, .weather-input .btn { width: 100%; }
            .filters { flex-direction: column; gap: 0.5rem; }
            .card-title { font-size: 1.3rem; }
            .recipe-img, .no-image { height: 200px; }
            #meteo-box .card-title { font-size: 1.5rem; }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<!-- Header -->
<?php require_once __DIR__ . '/header.php'; ?>

<div class="container flex-fill py-4">
    <h1>Recettes de Saison</h1>
    <p class="intro">
        Découvrez des recettes inspirées par le rythme des saisons, guidées par la météo et la passion culinaire.
    </p>

    <!-- Weather Input and Box -->
    <div class="weather-input">
        <input
                type="text"
                id="cityInput"
                class="form-control"
                placeholder="Entrez une ville (ex : Paris)"
                aria-label="Ville"
        >
        <button class="btn btn-primary" id="fetchWeather">Voir la météo</button>
    </div>

    <div id="meteo-box">
        <div class="alert alert-info">Cuisine inspirée par le ciel...</div>
    </div>

    <!-- Filtres -->
    <form method="get" class="filters">
        <select name="author" class="form-select">
            <option value="0">Tous les auteurs</option>
            <?php foreach($users as $u): ?>
                <option
                        value="<?= $u['user_id'] ?>"
                    <?= $authorFilter === $u['user_id'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($u['full_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="season" class="form-select">
            <option value="0">Toutes les saisons</option>
            <?php foreach($seasons as $id => $label): ?>
                <option
                        value="<?= $id ?>"
                    <?= $seasonFilter === $id ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($label) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn btn-primary">Filtrer</button>
    </form>

    <!-- Grille -->
    <div class="row g-4">
        <?php if (empty($displayRecipes)): ?>
            <div class="col-12 text-center">
                <p class="text-muted">Aucune recette trouvée.</p>
            </div>
        <?php else: ?>
            <?php foreach($displayRecipes as $r):
                // Calcul note
                $ratingStmt->execute([$r['recipe_id']]);
                $rate  = $ratingStmt->fetch(PDO::FETCH_ASSOC);
                $avg   = $rate['avg_review'] ? round($rate['avg_review'], 1) : 0;
                $count = $rate['count_review'];

                $seasonId    = (int)$r['season_id'];
                $seasonName  = htmlspecialchars($seasons[$seasonId] ?? '—');
                $seasonClass = 'season-' . $seasonId;
                $badgeClass  = $r['type'] === 'sucré' ? 'badge-sucré' : 'badge-salé';
                $authorName  = getAuthorName((int)$r['user_id'], $users);
                $isOwner     = !empty($_SESSION['LOGGED_USER']['user_id'])
                    && $_SESSION['LOGGED_USER']['user_id'] === (int)$r['user_id'];
                ?>
                <div class="col-md-6 col-lg-4 d-flex">
                    <div class="card w-100 <?= $seasonClass ?>">
                        <?php if($r['status'] === 'draft'): ?>
                            <div class="ribbon">Brouillon</div>
                        <?php endif; ?>

                        <?php if(!empty($r['image'])): ?>
                            <img
                                    src="Uploads/<?= htmlspecialchars($r['image']) ?>"
                                    class="recipe-img"
                                    alt="<?= htmlspecialchars($r['title']) ?>"
                            >
                        <?php else: ?>
                            <div class="no-image">
                                <?php if($isOwner): ?>
                                    <a
                                            href="recipes_update.php?id=<?= $r['recipe_id'] ?>"
                                            class="btn btn-light btn-sm"
                                    >
                                        <i class="bi bi-camera"></i> Ajouter image
                                    </a>
                                <?php else: ?>
                                    <span>Aucune image</span>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>

                        <div class="card-body">
                            <h5 class="card-title">
                                <a
                                        href="recipes_read.php?id=<?= $r['recipe_id'] ?>"
                                        class="text-decoration-none"
                                >
                                    <?= htmlspecialchars($r['title']) ?>
                                </a>
                            </h5>

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

                            <?php if($isOwner): ?>
                                <div class="d-flex gap-2">
                                    <a
                                            href="recipes_update.php?id=<?= $r['recipe_id'] ?>"
                                            class="btn btn-outline-dark"
                                    >
                                        <i class="bi bi-pencil-square"></i>
                                    </a>
                                    <?php if($r['status'] === 'draft'): ?>
                                        <a
                                                href="recipes_publish.php?id=<?= $r['recipe_id'] ?>"
                                                class="btn btn-success"
                                        >
                                            <i class="bi bi-upload"></i>
                                        </a>
                                    <?php endif; ?>
                                    <a
                                            href="recipes_delete.php?id=<?= $r['recipe_id'] ?>"
                                            class="btn btn-outline-danger"
                                            onclick="return confirm('Supprimer cette recette ?')"
                                    >
                                        <i class="bi bi-trash"></i>
                                    </a>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Footer -->
<?php require_once __DIR__ . '/footer.php'; ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<!-- Weather Script -->
<script src="js/weather.js?v=<?= time() ?>"></script>
</body>
</html>