<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');

// Validation
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo('Recette invalide');
    exit;
}

$id = (int)$_GET['id'];

// Charger recette + commentaires + auteur + notes + auteurs des commentaires
$stmt = $mysqlClient->prepare('
    SELECT 
        r.*, 
        c.comment_id, 
        c.comment, 
        c.user_id AS comment_user,
        DATE_FORMAT(c.created_at, "%d/%m/%Y") AS comment_date,
        ru.full_name AS recipe_author,
        cu.full_name AS comment_author,
        (SELECT ROUND(AVG(review),1) FROM comment WHERE recipe_id = r.recipe_id) AS rating
    FROM recipe r
    LEFT JOIN comment c ON r.recipe_id = c.recipe_id
    LEFT JOIN user ru ON r.user_id = ru.user_id
    LEFT JOIN user cu ON c.user_id = cu.user_id
    WHERE r.recipe_id = ?
');
$stmt->execute([$id]);

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {
    echo('Recette non trouvée');
    exit;
}

// Préparer les données
$recipe = $rows[0];
$recipe['comments'] = [];
$recipe['author'] = $recipe['recipe_author'] ?? 'Anonyme';

foreach ($rows as $row) {
    if (!empty($row['comment_id'])) {
        $recipe['comments'][] = [
            'id' => $row['comment_id'],
            'content' => $row['comment'],
            'author' => $row['comment_author'] ?? 'Anonyme',
            'date' => $row['comment_date'],
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($recipe['title']) ?> – Détail</title>
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
            margin: 2rem 0 1rem;
        }
        .container {
            max-width: 720px;
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
<body class="d-flex flex-column min-vh-100">
<?php require_once(__DIR__ . '/header.php'); ?>

<main class="flex-fill">
    <div class="container py-4">
        <h1><?= htmlspecialchars($recipe['title']) ?></h1>
        <p class="text-muted text-center"><i>Par <?= htmlspecialchars($recipe['author']) ?></i></p>

        <?php if (!empty($recipe['rating'])): ?>
            <p class="text-center">Note moyenne : <strong><?= $recipe['rating'] ?>/5</strong></p>
        <?php endif; ?>

        <hr>
        <p><?= nl2br(htmlspecialchars($recipe['recipe'])) ?></p>

        <hr>
        <h3>Commentaires</h3>
        <?php if ($recipe['comments']): ?>
            <?php foreach ($recipe['comments'] as $c): ?>
                <div class="border rounded p-2 mb-2 bg-white">
                    <p class="mb-1"><strong><?= htmlspecialchars($c['author']) ?></strong> – <?= $c['date'] ?></p>
                    <p class="mb-0"><?= htmlspecialchars($c['content']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Aucun commentaire.</p>
        <?php endif; ?>

        <?php if (!empty($_SESSION['LOGGED_USER'])): ?>
            <hr>
            <?php require_once(__DIR__ . '/comments_create.php'); ?>
        <?php endif; ?>
    </div>
</main>

<?php require_once(__DIR__ . '/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
