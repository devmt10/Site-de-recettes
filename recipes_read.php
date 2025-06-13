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

// Chargement des infos + commentaires
$stmt = $mysqlClient->prepare('
    SELECT r.*, c.comment_id, c.comment, c.user_id AS comment_user, DATE_FORMAT(c.created_at, "%d/%m/%Y") AS comment_date,
           u.full_name AS author, 
           (SELECT ROUND(AVG(review),1) FROM comment WHERE recipe_id = r.recipe_id) AS rating
      FROM recipe r
 LEFT JOIN comment c ON r.recipe_id = c.recipe_id
 LEFT JOIN user u ON r.user_id = u.user_id
     WHERE r.recipe_id = ?
');
$stmt->execute([$id]);

$rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

if (!$rows) {
    echo('Recette non trouvée');
    exit;
}

// Traitement des résultats
$recipe = $rows[0];
$recipe['comments'] = [];

foreach ($rows as $row) {
    if (!empty($row['comment_id'])) {
        $recipe['comments'][] = [
            'id' => $row['comment_id'],
            'content' => $row['comment'],
            'author' => $row['full_name'],
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light d-flex flex-column min-vh-100">
<?php require_once(__DIR__ . '/header.php'); ?>
<div class="container py-4">
    <h1><?= htmlspecialchars($recipe['title']) ?></h1>
    <p><i>Par <?= htmlspecialchars($recipe['full_name'] ?? 'Anonyme') ?></i></p>
    <?php if (!empty($recipe['rating'])): ?>
        <p>Note moyenne : <strong><?= $recipe['rating'] ?>/5</strong></p>
    <?php endif; ?>
    <hr>
    <p><?= nl2br(htmlspecialchars($recipe['recipe'])) ?></p>

    <hr>
    <h3>Commentaires</h3>
    <?php if ($recipe['comments']): ?>
        <?php foreach ($recipe['comments'] as $c): ?>
            <div class="border rounded p-2 mb-2 bg-white">
                <p class="mb-1"><strong><?= $c['author'] ?></strong> – <?= $c['date'] ?></p>
                <p class="mb-0"><?= htmlspecialchars($c['content']) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-muted">Aucun commentaire.</p>
    <?php endif; ?>

    <?php if (isset($_SESSION['LOGGED_USER'])): ?>
        <hr>
        <?php require_once(__DIR__ . '/comments_create.php'); ?>
    <?php endif; ?>
</div>
<?php require_once(__DIR__ . '/footer.php'); ?>
</body>
</html>
