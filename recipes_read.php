<?php
session_start();

require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/variables.php');   // defines $users if needed
require_once(__DIR__ . '/functions.php');   // defines displayAuthor()

// 1) Validate & fetch recipe ID
$getData = $_GET;
if (!isset($getData['id']) || !is_numeric($getData['id'])) {
    echo 'La recette n\'existe pas';
    return;
}

// 2) Fetch recipe, poster, and comments
$retrieveStmt = $mysqlClient->prepare(
    'SELECT 
        r.*,
        u_poster.full_name AS poster_name,
        r.user_id         AS poster_id,
        c.comment_id, c.comment,
        DATE_FORMAT(c.created_at, "%d/%m/%Y %Hh%i") AS comment_date,
        u_comment.full_name AS commenter_name
     FROM recipe AS r
     LEFT JOIN user AS u_poster
       ON r.user_id = u_poster.user_id
     LEFT JOIN comment AS c 
       ON r.recipe_id = c.recipe_id
     LEFT JOIN user AS u_comment
       ON c.user_id = u_comment.user_id
     WHERE r.recipe_id = ?
     ORDER BY c.created_at ASC'
);
$retrieveStmt->execute([ $getData['id'] ]);
$rows = $retrieveStmt->fetchAll(PDO::FETCH_ASSOC);

if (empty($rows)) {
    echo 'La recette n\'existe pas';
    return;
}

// 3) Build the $recipe array
$recipe = [
    'recipe_id' => $rows[0]['recipe_id'],
    'title'     => $rows[0]['title'],
    'content'   => $rows[0]['recipe'],
    'image'     => $rows[0]['image'] ?? null,
    'poster'    => $rows[0]['poster_name'] ?? 'Anonyme',
    'poster_id' => $rows[0]['poster_id'],      // user_id of the author
    'comments'  => []
];
foreach ($rows as $row) {
    if (!empty($row['comment_id'])) {
        $recipe['comments'][] = [
            'id'         => $row['comment_id'],
            'text'       => $row['comment'],
            'author'     => $row['commenter_name'] ?? 'Inconnu',
            'created_at' => $row['comment_date'],
        ];
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Saveurs &amp; Saisons – <?= htmlspecialchars($recipe['title']) ?></title>
    <meta name="viewport" content="width=device-width,initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <style>
        body { background-color: #f9f6f1; font-family: 'Georgia', serif; color: #333; }
        h1 { font-family: 'Didot', serif; font-size: 2.5rem; text-align: center; margin: 2rem 0; }
        .recipe-img { max-height: 350px; object-fit: cover; border-radius: .375rem; box-shadow: 0 4px 12px rgba(0,0,0,0.1); margin-bottom: 1.5rem; }
        .card { border: none; box-shadow: 0 4px 12px rgba(0,0,0,0.05); margin-bottom: 2rem; }
        .card-body { font-size: 1.05rem; line-height: 1.6; }
        .card-footer { background: #fff; border-top: none; display: flex; justify-content: space-between; align-items: center; font-size: 0.95rem; }
        .author { font-style: italic; color: #777; }
        .comment-box { background: #fff; border-radius: .375rem; box-shadow: 0 2px 6px rgba(0,0,0,0.05); padding: 1rem; margin-bottom: 1rem; }
    </style>
</head>
<body class="d-flex flex-column min-vh-100 bg-light">
<div class="container my-5">
    <?php require_once(__DIR__ . '/header.php'); ?>

    <!-- Recipe Title -->
    <h1><?= htmlspecialchars($recipe['title']) ?></h1>

    <!-- Image Preview -->
    <?php if (!empty($recipe['image'])): ?>
        <div class="text-center mb-4">
            <img src="uploads/<?= htmlspecialchars($recipe['image']) ?>"
                 alt="Image de <?= htmlspecialchars($recipe['title']) ?>"
                 class="recipe-img img-fluid">
        </div>
    <?php endif; ?>

    <!-- Recipe Card -->
    <div class="card mb-5">
        <div class="card-body">
            <?= nl2br(htmlspecialchars($recipe['content'])) ?>
        </div>
        <div class="card-footer">
        <span class="author">
          Contribué par <?= htmlspecialchars($recipe['poster']) ?>
        </span>
        </div>
    </div>

    <!-- Comments Section -->
    <h2 class="mb-4">Commentaires</h2>
    <?php if (!empty($recipe['comments'])): ?>
        <?php foreach ($recipe['comments'] as $c): ?>
            <div class="comment-box">
                <p class="mb-1">
                    <strong><?= htmlspecialchars($c['author']) ?></strong>
                    <small class="text-muted"><?= $c['created_at'] ?></small>
                </p>
                <p class="mb-0"><?= nl2br(htmlspecialchars($c['text'])) ?></p>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p class="text-muted">Aucun commentaire pour le moment.</p>
    <?php endif; ?>

    <!-- Comment Form (only for non-authors) -->
    <?php if (
        isset($_SESSION['LOGGED_USER'])
        && $_SESSION['LOGGED_USER']['user_id'] !== $recipe['poster_id']
    ): ?>
        <?php require_once(__DIR__ . '/comments_create.php'); ?>
    <?php endif; ?>

</div>

<?php require_once(__DIR__ . '/footer.php'); ?>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
