<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');

// Handle comment submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_SESSION['LOGGED_USER'])) {
    $comment = trim($_POST['comment'] ?? '');
    $review = isset($_POST['review']) ? (int)$_POST['review'] : 0;
    $recipe_id = (int)$_GET['id'];
    $user_id = $_SESSION['LOGGED_USER']['user_id'];

    if (empty($comment) || $review < 1 || $review > 5) {
        $error = 'Veuillez écrire un commentaire et donner une note entre 1 et 5.';
    } else {
        $stmt = $mysqlClient->prepare('INSERT INTO comment (recipe_id, user_id, comment, review, created_at) VALUES (?, ?, ?, ?, NOW())');
        if ($stmt->execute([$recipe_id, $user_id, $comment, $review])) {
            header("Location: recipes_read.php?id=$recipe_id");
            exit;
        } else {
            $error = 'Erreur lors de l\'ajout du commentaire.';
        }
    }
}

// Validation
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    echo('Recette invalide');
    exit;
}

$id = (int)$_GET['id'];

// Load recipe + comments + authors
$stmt = $mysqlClient->prepare('
    SELECT 
        r.*, 
        c.comment_id, 
        c.comment, 
        c.review,
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

// Prepare data
$recipe = $rows[0];
$recipe['comments'] = [];
$recipe['author'] = $recipe['recipe_author'] ?? 'Anonyme';
$isRecipeOwner = !empty($_SESSION['LOGGED_USER']) && $_SESSION['LOGGED_USER']['user_id'] === $recipe['user_id'];

foreach ($rows as $row) {
    if (!empty($row['comment_id'])) {
        $recipe['comments'][] = [
            'id' => $row['comment_id'],
            'content' => $row['comment'],
            'review' => $row['review'],
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
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body class="d-flex flex-column min-vh-100">
<?php require_once(__DIR__ . '/header.php'); ?>
<main class="flex-fill">
    <div class="container recipe-detail py-4">
        <h1><?= htmlspecialchars($recipe['title']) ?></h1>
        <p class="text-muted text-center"><i>Par <?= htmlspecialchars($recipe['author']) ?></i></p>
        <?php if (!empty($recipe['image'])): ?>
            <img src="Uploads/<?= htmlspecialchars($recipe['image']) ?>" class="recipe-img" alt="<?= htmlspecialchars($recipe['title']) ?>">
        <?php endif; ?>
        <?php if (!empty($recipe['rating'])): ?>
            <div class="rating text-center">
                <?php
                $avg = $recipe['rating'];
                for ($i = 1; $i <= 5; $i++):
                    if ($i <= floor($avg)):
                        echo '<i class="bi bi-star-fill"></i>';
                    elseif ($i - $avg < 1):
                        echo '<i class="bi bi-star-half"></i>';
                    else:
                        echo '<i class="bi bi-star"></i>';
                    endif;
                endfor;
                ?>
                <small>(<?= $recipe['rating'] ?>/5)</small>
            </div>
        <?php endif; ?>
        <hr>
        <p><?= nl2br(htmlspecialchars($recipe['recipe'])) ?></p>
        <hr>
        <h3>Commentaires</h3>
        <?php if ($recipe['comments']): ?>
            <?php foreach ($recipe['comments'] as $c): ?>
                <div class="border rounded p-2 mb-2 bg-white">
                    <p class="mb-1">
                        <strong><?= htmlspecialchars($c['author']) ?></strong> – <?= $c['date'] ?>
                        <?php if (!empty($c['review'])): ?>
                            <span class="rating ms-2">
                                <?php for ($i = 1; $i <= 5; $i++):
                                    if ($i <= $c['review']):
                                        echo '<i class="bi bi-star-fill"></i>';
                                    else:
                                        echo '<i class="bi bi-star"></i>';
                                    endif;
                                endfor; ?>
                            </span>
                        <?php endif; ?>
                    </p>
                    <p class="mb-0"><?= htmlspecialchars($c['content']) ?></p>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted">Aucun commentaire.</p>
        <?php endif; ?>
        <?php if (!empty($_SESSION['LOGGED_USER']) && !$isRecipeOwner): ?>
            <hr>
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>
            <form method="post" action="recipes_read.php?id=<?= htmlspecialchars($_GET['id']) ?>">
                <div class="mb-3">
                    <label for="comment" class="form-label">Votre commentaire</label>
                    <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
                </div>
                <div class="mb-3">
                    <label class="form-label">Note</label>
                    <div class="rating-input">
                        <?php for ($i = 5; $i >= 1; $i--): ?>
                            <input type="radio" name="review" id="review<?= $i ?>" value="<?= $i ?>" required>
                            <label for="review<?= $i ?>"><i class="bi bi-star"></i></label>
                        <?php endfor; ?>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary btn-sm">Envoyer</button>
            </form>
        <?php endif; ?>
    </div>
</main>
<?php require_once(__DIR__ . '/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>