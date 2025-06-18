<?php
session_start();
require_once(__DIR__ . '/isConnect.php');
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo "Requête invalide";
    exit;
}

$id = (int)($_POST['id'] ?? 0);
$title = trim($_POST['title'] ?? '');
$recipe = trim($_POST['recipe'] ?? '');
$seasonId = (int)($_POST['season_id'] ?? 0);
$type = trim($_POST['type'] ?? '');
$status = trim($_POST['status'] ?? 'draft');

if ($id <= 0 || $title === '' || $recipe === '' || $seasonId <= 0 || !in_array($type, ['sucré', 'salé']) || !in_array($status, ['draft', 'published'])) {
    $_SESSION['UPDATE_ERROR'] = "Champs manquants ou invalides.";
    header("Location: recipes_update.php?id=$id");
    exit;
}

// Mise à jour principale
$stmt = $mysqlClient->prepare("
    UPDATE recipe SET
        title = :title,
        recipe = :recipe,
        season_id = :season,
        type = :type,
        status = :status
    WHERE recipe_id = :id
");
$stmt->execute([
    ':title' => $title,
    ':recipe' => $recipe,
    ':season' => $seasonId,
    ':type' => $type,
    ':status' => $status,
    ':id' => $id
]);

// Traitement image si nouvelle
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $tmp = $_FILES['image']['tmp_name'];
    $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg','jpeg','png','gif'])) {
        $name = uniqid('img_') . '.' . $ext;
        move_uploaded_file($tmp, __DIR__ . "/uploads/$name");
        $mysqlClient->prepare("UPDATE recipe SET image = :img WHERE recipe_id = :id")
            ->execute([':img' => $name, ':id' => $id]);
    }
}

header("Location: recipes_read.php?id=$id");
exit;