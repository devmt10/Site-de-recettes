<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require_once(__DIR__ . '/config/mysql.php');
    require_once(__DIR__ . '/databaseconnect.php');

    $comment = trim($_POST['comment']);
    $review = isset($_POST['review']) ? (int)$_POST['review'] : 0;
    $recipe_id = (int)$_GET['id'];
    $user_id = $_SESSION['LOGGED_USER']['user_id'] ?? 0;

    if (empty($comment) || $review < 1 || $review > 5 || $user_id === 0) {
        $error = 'Veuillez écrire un commentaire, donner une note entre 1 et 5, et être connecté.';
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
?>

<form method="post" action="comments_create.php?id=<?= htmlspecialchars($_GET['id']) ?>">
    <?php if (isset($error)): ?>
        <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
    <?php endif; ?>
    <div class="mb-3">
        <label for="comment" class="form-label">Votre commentaire</label>
        <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Note</label>
        <div class="rating-input">
            <?php for ($i = 5; $i >= 1; $i--): ?>
                <input type="radio" name="review" id="review<?php echo $i; ?>" value="<?php echo $i; ?>" required>
                <label for="review<?php echo $i; ?>"><i class="bi bi-star"></i></label>
            <?php endfor; ?>
        </div>
    </div>
    <button type="submit" class="btn btn-primary btn-sm">Envoyer</button>
</form>