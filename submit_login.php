<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    if (!$email || !$password) {
        $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Veuillez remplir tous les champs.';
        header('Location: login.php');
        exit;
    }

    try {
        $stmt = $mysqlClient->prepare('SELECT user_id, email, password, full_name FROM user WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['LOGGED_USER'] = [
                'user_id' => $user['user_id'],
                'email' => $user['email'],
                'full_name' => $user['full_name']
            ];
            header('Location: index.php');
            exit;
        } else {
            $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Email ou mot de passe incorrect.';
            header('Location: login.php');
            exit;
        }
    } catch (PDOException $e) {
        $_SESSION['LOGIN_ERROR_MESSAGE'] = 'Erreur de connexion à la base de données.';
        header('Location: login.php');
        exit;
    }
} else {
    header('Location: login.php');
    exit;
}
?>