<?php
session_start();
require_once(__DIR__ . '/databaseconnect.php');

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

// Requête préparée pour sécuriser contre l'injection SQL
$stmt = $mysqlClient->prepare("SELECT * FROM users WHERE email = :email LIMIT 1");
$stmt->execute(['email' => $email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($user && password_verify($password, $user['password'])) {
    // Authentification réussie
    $_SESSION['LOGGED_USER'] = [
        'email' => $user['email'],
        'name' => $user['name']
    ];
    header('Location: index.php');
    exit;
} else {
    // Échec : message d’erreur stocké en session
    $_SESSION['LOGIN_ERROR_MESSAGE'] = "Identifiants incorrects. Veuillez réessayer.";
    header('Location: login.php');
    exit;
}