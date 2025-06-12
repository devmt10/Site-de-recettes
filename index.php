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
    <title>Saveurs &amp; Saisons – Recettes</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
            rel="stylesheet"
    >
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"
            rel="stylesheet"
    >
    <!-- Vogue‐style serif -->
    <link
            href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap"
            rel="stylesheet"
    >

    <style>
        body { background:#f9f6f1; font-family:Georgia, serif; color:#333; }
        h1   { font-family:Didot, serif; text-align:center; margin:1.5rem 0; }

        .filters {
            max-width:500px; margin:0 auto 2rem;
            display:flex; justify-content:center; gap:1rem;
        }
        .filters select, .filters button { width:auto; }

        .card {
            border:none; box-shadow:0 4px 12px rgba(0,0,0,0.05);
            transition:transform .2s;
            display:flex; flex-direction:column; height:100%; position:relative;
        }
        .card:hover { transform:translateY(-4px); }

        .ribbon {
            position:absolute; top:.5rem; right:0;
            background:#ff9800; color:#fff;
            padding:.25rem .75rem; font-size:.75rem; font-weight:bold;
            border-top-right-radius:.375rem; border-bottom-left-radius:.375rem;
        }

        .recipe-img {
            width:100%; height:250px; object-fit:cover;
            border-top-left-radius:.375rem; border-top-right-radius:.375rem;
        }

        .card-body {
            flex:1; display:flex; flex-direction:column;
            padding:.75rem 1rem;
        }

        .season-label {
            font-size:.85rem; font-weight:500; color:#555;
            margin-bottom:.5rem;
        }

        .badge-sucré { background:#b85c8c!important; }
        .badge-salé  { background:#4a7c59!important; }

        .author { font-style:italic; color:#777; margin-top:.5rem; }
        .rating { color:#f1c40f; margin-bottom:.5rem; }
        .rating small { color:#555; font-size:.75rem; }

        /* Season color bars */
        .season-1 { border-top:4px solid #2ecc71; }   /* Printemps */
        .season-2 { border-top:4px solid #f1c40f; }   /* Été */
        .season-3 { border-top:4px solid #e67e22; }   /* Automne */
        .season-4 { border-top:4px solid #3498db; }   /* Hiver */
    </style>
</head>
<body class="bg-light d-flex flex-column min-vh-100">

<!-- Header -->
<?php require_once __DIR__.'/header.php'; ?>

<div class="container flex-fill py-4">
    <h1>Recettes de Saison</h1>

    <!-- Filtres -->
    <form method="get" class="filters">
        <select name="author" class="form-select form-select-sm">
            <option value="0">Tous les auteurs</option>
            <?php foreach($users as $u): ?>
                <option
                        value="<?=$u['user_id']?>"
                    <?= $authorFilter === $u['user_id'] ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($u['full_name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="season" class="form-select form-select-sm">
            <option value="0">Toutes les saisons</option>
            <?php foreach($seasons as $id => $label): ?>
                <option
                        value="<?=$id?>"
                    <?= $seasonFilter === $id ? 'selected' : '' ?>
                >
                    <?= htmlspecialchars($label) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <button type="submit" class="btn btn-sm btn-primary">Filtrer</button>
    </form>

    <!-- Grille -->
    <div class="row g-4">
        <?php foreach($displayRecipes as $r):
            // calcul note
            $ratingStmt->execute([$r['recipe_id']]);
            $rate  = $ratingStmt->fetch(PDO::FETCH_ASSOC);
            $avg   = $rate['avg_review'] ? round($rate['avg_review'],1) : 0;
            $count = $rate['count_review'];

            $seasonId    = (int)$r['season_id'];
            $seasonName  = htmlspecialchars($seasons[$seasonId] ?? '—');
            $seasonClass = 'season-'.$seasonId;
            $badgeClass  = $r['type'] === 'sucré' ? 'badge-sucré' : 'badge-salé';
            $authorName  = getAuthorName((int)$r['user_id'], $users);
            $isOwner     = !empty($_SESSION['LOGGED_USER']['user_id'])
                && $_SESSION['LOGGED_USER']['user_id'] === (int)$r['user_id'];
            ?>
            <div class="col-md-6 col-lg-4 d-flex">
                <div class="card w-100 <?=$seasonClass?>">
                    <?php if($r['status'] === 'draft'): ?>
                        <div class="ribbon">Brouillon</div>
                    <?php endif; ?>

                    <?php if(!empty($r['image'])): ?>
                        <img
                                src="uploads/<?= htmlspecialchars($r['image']) ?>"
                                class="recipe-img"
                                alt="<?= htmlspecialchars($r['title']) ?>"
                        >
                    <?php else: ?>
                        <div
                                class="d-flex align-items-center justify-content-center bg-secondary text-white"
                                style="height:250px;"
                        >
                            <?php if($isOwner): ?>
                                <a
                                        href="recipes_update.php?id=<?=$r['recipe_id']?>"
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
                        <h5 class="card-title mb-1">
                            <a
                                    href="recipes_read.php?id=<?=$r['recipe_id']?>"
                                    class="text-dark text-decoration-none"
                            >
                                <?= htmlspecialchars($r['title']) ?>
                            </a>
                        </h5>

                        <div class="season-label">Saison : <?=$seasonName?></div>

                        <span class="badge <?=$badgeClass?> mb-2 text-white">
              <?= ucfirst(htmlspecialchars($r['type'])) ?>
            </span>

                        <p class="card-text small text-muted mb-2">
                            <?= nl2br(htmlspecialchars(mb_strimwidth($r['recipe'], 0, 80, '...'))) ?>
                        </p>

                        <p class="author">Par <?=$authorName?></p>

                        <div class="rating mb-2">
                            <?php for($i=1; $i<=5; $i++): ?>
                                <?php if($i <= floor($avg)): ?>
                                    <i class="bi bi-star-fill"></i>
                                <?php elseif($i - $avg < 1): ?>
                                    <i class="bi bi-star-half"></i>
                                <?php else: ?>
                                    <i class="bi bi-star"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <small>(<?=$count?> avis)</small>
                        </div>

                        <?php if($isOwner): ?>
                            <div class="d-flex gap-2">
                                <a
                                        href="recipes_update.php?id=<?=$r['recipe_id']?>"
                                        class="btn btn-outline-dark btn-sm"
                                >
                                    <i class="bi bi-pencil-square"></i>
                                </a>
                                <?php if($r['status'] === 'draft'): ?>
                                    <a
                                            href="recipes_publish.php?id=<?=$r['recipe_id']?>"
                                            class="btn btn-success btn-sm"
                                    >
                                        <i class="bi bi-upload"></i>
                                    </a>
                                <?php endif; ?>
                                <a
                                        href="recipes_delete.php?id=<?=$r['recipe_id']?>"
                                        class="btn btn-outline-danger btn-sm"
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
    </div>

</div>

<!-- Footer -->
<?php require_once __DIR__.'/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
