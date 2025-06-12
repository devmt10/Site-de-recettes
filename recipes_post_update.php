<?php
ob_start();
session_start();
ini_set('display_errors',1);
error_reporting(E_ALL);

require_once(__DIR__.'/isConnect.php');
require_once(__DIR__.'/config/mysql.php');
require_once(__DIR__.'/databaseconnect.php');

function redirect_to(string $url) {
    if (!headers_sent()) {
        header("Location: {$url}");
        exit;
    }
    echo "<script>window.location.replace('{$url}');</script>";
    exit;
}

// Only POST allowed
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect_to('index.php');
}

// Validate and sanitize
$id         = (int)($_POST['id'] ?? 0);
$title      = trim($_POST['title'] ?? '');
$recipeText = trim($_POST['recipe'] ?? '');
$seasonId   = (int)($_POST['season'] ?? 0);
$type       = $_POST['type'] ?? '';
$status     = $_POST['status'] ?? '';

if (
    $id <= 0 ||
    $title === '' ||
    $recipeText === '' ||
    $seasonId <= 0 ||
    !in_array($type, ['sucré','salé'], true) ||
    !in_array($status, ['draft','published'], true)
) {
    $_SESSION['UPDATE_ERROR'] = 'Tous les champs sont requis et valides.';
    redirect_to("recipes_update.php?id={$id}");
}

// Update all fields
$update = $mysqlClient->prepare("
    UPDATE recipe
       SET title     = :title,
           recipe    = :recipe,
           season_id = :season,
           type      = :type,
           status    = :status
     WHERE recipe_id = :id
");
$update->execute([
    ':title'   => $title,
    ':recipe'  => $recipeText,
    ':season'  => $seasonId,
    ':type'    => $type,
    ':status'  => $status,
    ':id'      => $id
]);

// (Optional image upload code here)

redirect_to("recipes_read.php?id={$id}");
