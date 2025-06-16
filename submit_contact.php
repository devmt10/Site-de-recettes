<?php
session_start();
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');

    // Validation
    if (empty($email) || empty($message)) {
        $error = 'Veuillez remplir tous les champs obligatoires.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Adresse email invalide.';
    } else {
        try {
            $stmt = $mysqlClient->prepare('INSERT INTO contact (email, message, created_at) VALUES (?, ?, NOW())');
            if ($stmt->execute([$email, $message])) {
                header('Location: contact.php?success=1');
                exit;
            } else {
                $error = 'Erreur lors de l\'envoi du message.';
            }
        } catch (PDOException $e) {
            $error = 'Erreur serveur : ' . $e->getMessage();
        }
    }
} else {
    $error = 'Méthode non autorisée.';
}

// Redirect with error
header('Location: contact.php?error=' . urlencode($error));
exit;
?>