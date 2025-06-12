<?php
// recipes_post_create.php

session_start();
require_once __DIR__.'/isConnect.php';
require_once __DIR__.'/config/mysql.php';
require_once __DIR__.'/databaseconnect.php';

function redirect(string $url) {
    header("Location: $url");
    exit;
}

// 1) Only POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('recipes_create.php');
}

// 2) Validate required fields
// Note: on lit maintenant 'season_id' au lieu de 'season'
if (
    empty(trim($_POST['title'])) ||
    empty(trim($_POST['recipe'])) ||
    empty($_POST['season_id']) ||
    empty($_POST['type'])
) {
    $_SESSION['CREATE_ERROR'] = 'Tous les champs (titre, description, saison, type) sont obligatoires.';
    redirect('recipes_create.php');
}

// 3) Sanitize
$title    = trim($_POST['title']);
$recipe   = trim($_POST['recipe']);
$seasonId = (int) $_POST['season_id'];
$type     = trim($_POST['type']);
$userId   = $_SESSION['LOGGED_USER']['user_id'];

// 4) Insert into database (status = draft, is_enabled = 1)
$stmt = $mysqlClient->prepare("
    INSERT INTO recipe
        (user_id, title, recipe, season_id, type, status, is_enabled)
    VALUES
        (:user, :title, :recipe, :season, :type, 'draft', 1)
");
$stmt->execute([
    ':user'   => $userId,
    ':title'  => $title,
    ':recipe' => $recipe,
    ':season' => $seasonId,
    ':type'   => $type
]);

// 5) Optional image upload
$lastId = $mysqlClient->lastInsertId();
if (
    isset($_FILES['image']) &&
    $_FILES['image']['error'] === UPLOAD_ERR_OK
) {
    $tmp  = $_FILES['image']['tmp_name'];
    $name = basename($_FILES['image']['name']);
    $ext  = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    $allow = ['jpg','jpeg','png','gif'];
    if (in_array($ext, $allow, true)) {
        $newName = uniqid('img_').'.'.$ext;
        $dir = __DIR__.'/uploads/';
        if (move_uploaded_file($tmp, $dir.$newName)) {
            $up = $mysqlClient->prepare("
                UPDATE recipe
                   SET image = :img
                 WHERE recipe_id = :id
            ");
            $up->execute([':img'=>$newName, ':id'=>$lastId]);
        }
    }
}

// 6) Redirect back
redirect("recipes_read.php?id={$lastId}");
