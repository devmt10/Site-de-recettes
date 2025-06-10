<?php
session_start();
require_once('variables.php'); // Contient la connexion PDO

function isPasswordSecure($password) {
    return strlen($password) >= 8 &&
           preg_match('/[A-Z]/', $password) &&
           preg_match('/[a-z]/', $password) &&
           preg_match('/[0-9]/', $password) &&
           preg_match('/[\W]/', $password);
}

if (!empty($_POST['full_name']) && !empty($_POST['age']) && !empty($_POST['email']) && !empty($_POST['password'])) {
    $full_name = trim($_POST['full_name']);
    $age = (int)$_POST['age'];
    $email = strtolower(trim($_POST['email']));
    $password = $_POST['password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['REGISTER_MESSAGE'] = "Adresse email invalide.";
        $_SESSION['REGISTER_SUCCESS'] = false;
    } elseif (!isPasswordSecure($password)) {
        $_SESSION['REGISTER_MESSAGE'] = "Le mot de passe doit contenir au moins 8 caractères, une majuscule, un chiffre et un caractère spécial.";
        $_SESSION['REGISTER_SUCCESS'] = false;
    } else {
        $passwordHash = password_hash($password, PASSWORD_DEFAULT);

        try {
            $pdo = $mysqlClient;            $stmt = $pdo->prepare("SELECT COUNT(*) FROM user WHERE email = ?");
            $stmt->execute([$email]);
            $exists = $stmt->fetchColumn();

            if ($exists) {
                $_SESSION['REGISTER_MESSAGE'] = "Cet email est déjà utilisé.";
                $_SESSION['REGISTER_SUCCESS'] = false;
            } else {
                $stmt = $pdo->prepare("INSERT INTO user (full_name, age, email, password) VALUES (?, ?, ?, ?)");
                $stmt->execute([$full_name, $age, $email, $passwordHash]);

                $_SESSION['REGISTER_MESSAGE'] = "Inscription réussie. Vous pouvez maintenant vous connecter.";
                $_SESSION['REGISTER_SUCCESS'] = true;
            }
        } catch (PDOException $e) {
            $_SESSION['REGISTER_MESSAGE'] = "Erreur lors de l'inscription : " . $e->getMessage();
            $_SESSION['REGISTER_SUCCESS'] = false;
        }
    }
    header('Location: registration.php');
    exit;
} else {
    $_SESSION['REGISTER_MESSAGE'] = "Veuillez remplir tous les champs.";
    $_SESSION['REGISTER_SUCCESS'] = false;
    header('Location: registration.php');
    exit;
}