<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');
require_once(__DIR__ . '/functions.php');

if (!isset($_SESSION['LOGGED_USER'])) redirectToUrl('login.php');
if (empty($_GET['id']) || !is_numeric($_GET['id'])) redirectToUrl('index.php');

$id = (int)$_GET['id'];
// Verify ownership
$stmt = $mysqlClient->prepare('SELECT user_id FROM recipe WHERE recipe_id = ?');
$stmt->execute([$id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$row || $row['user_id'] !== $_SESSION['LOGGED_USER']['user_id']) {
    redirectToUrl('index.php');
}

// Publish
$up = $mysqlClient->prepare('UPDATE recipe SET status = "published" WHERE recipe_id = ?');
$up->execute([$id]);

redirectToUrl('index.php');
