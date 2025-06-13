<?php
// recipes_post_create.php – Handle recipe form submission

session_start();
require_once __DIR__ . '/isConnect.php';
require_once __DIR__ . '/config/mysql.php';
require_once __DIR__ . '/databaseconnect.php';

function redirect(string $url) {
    header("Location: $url");
    exit;
}

// 1) Ensure POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('recipes_create.php');
}

// 2) Validate required fields
if (
    empty(trim($_POST['title'])) ||
    empty(trim($_POST['recipe'])) ||
    empty($_POST['season_id']) ||
    empty($_POST['type']) ||
    empty($_POST['status'])
) {
    $_SESSION['CREATE_ERROR'] = 'Tous les champs (titre, description, saison, type, statut) sont obligatoires.';
    redirect('recipes_create.php');
}

// 3) Sanitize inputs
$title     = trim($_POST['title']);
$recipe    = trim($_POST['recipe']);
$seasonId  = (int) $_POST['season_id'];
$type      = trim($_POST['type']);
$status    = ($_POST['status'] === 'published') ? 'published' : 'draft';
$userId    = $_SESSION['LOGGED_USER']['user_id'];

// 4) Insert into database with selected status
$stmt = $mysqlClient->prepare("
    INSERT INTO recipe (user_id, title, recipe, season_id, type, status, is_enabled)
    VALUES (:user, :title, :recipe, :season, :type, :status, 1)
");
$stmt->execute([
    ':user'   => $userId,
    ':title'  => $title,
    ':recipe' => $recipe,
    ':season' => $seasonId,
    ':type'   => $type,
    ':status' => $status
]);

// 5) Handle optional image upload
$lastId = $mysqlClient->lastInsertId();
if (
    isset($_FILES['image']) &&
    $_FILES['image']['error'] === UPLOAD_ERR_OK
) {
    $tmp     = $_FILES['image']['tmp_name'];
    $name    = basename($_FILES['image']['name']);
    $ext     = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];

    if (in_array($ext, $allowed, true)) {
        $newName = uniqid('img_') . '.' . $ext;
        $dir     = __DIR__ . '/uploads/';
        if (move_uploaded_file($tmp, $dir . $newName)) {
            $updateStmt = $mysqlClient->prepare("
                UPDATE recipe
                   SET image = :img
                 WHERE recipe_id = :id
            ");
            $updateStmt->execute([':img' => $newName, ':id' => $lastId]);
        }
    }
}

// 6) Redirect to the recipe detail page
redirect("recipes_read.php?id={$lastId}");
