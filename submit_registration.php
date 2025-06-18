<?php
// 🔐 Sécurité : Validation CSRF et des champs d'inscription
session_start();

if (!hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'] ?? '')) {
    die('Erreur de sécurité CSRF.');
}
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = filter_input(INPUT_POST, 'full_name', FILTER_SANITIZE_STRING);
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!$full_name || !$email || !$password) {
        $_SESSION['REGISTRATION_ERROR_MESSAGE'] = 'Veuillez remplir tous les champs.';
        header('Location: registration.php');
        exit;
    }

    try {
        // Check if email already exists
        $stmt = $mysqlClient->prepare('SELECT COUNT(*) FROM user WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetchColumn() > 0) {
            $_SESSION['REGISTRATION_ERROR_MESSAGE'] = 'Cet email est déjà utilisé.';
            header('Location: registration.php');
            exit;
        }

        // Hash password and insert user
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $insertStmt = $mysqlClient->prepare('INSERT INTO user (full_name, email, password) VALUES (?, ?, ?)');
        $insertStmt->execute([$full_name, $email, $hashed_password]);

        // Get the new user's ID
        $userId = $mysqlClient->lastInsertId();

        // Auto-login the user
        $_SESSION['LOGGED_USER'] = [
            'user_id' => $userId,
            'email' => $email,
            'full_name' => $full_name
        ];
        $_SESSION['FIRST_LOGIN'] = true;

        header('Location: index.php');
        exit;
    } catch (PDOException $e) {
        $_SESSION['REGISTRATION_ERROR_MESSAGE'] = 'Erreur lors de l\'inscription. Veuillez réessayer.';
        header('Location: registration.php');
        exit;
    }
} else {
    header('Location: registration.php');
    exit;
}
?>
