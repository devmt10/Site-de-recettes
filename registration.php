<?php
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription</title>
    <style>
        body {
            font-family: "Segoe UI", sans-serif;
            max-width: 500px;
            margin: 50px auto;
            padding: 1rem;
            background-color: #f4f4f4;
            border-radius: 8px;
        }
        h1 {
            text-align: center;
        }
        form {
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }
        label {
            font-weight: bold;
        }
        input[type="text"], input[type="email"], input[type="password"] {
            padding: 0.5rem;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        input[type="submit"] {
            padding: 0.7rem;
            background-color: #4CAF50;
            color: white;
            font-weight: bold;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        .message {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 5px;
        }
        .success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>

    <h1>Créer un compte</h1>

    <?php if (isset($_SESSION['REGISTER_MESSAGE'])): ?>
        <div class="message <?php echo $_SESSION['REGISTER_SUCCESS'] ? 'success' : 'error'; ?>">
            <?php
            echo $_SESSION['REGISTER_MESSAGE'];
            unset($_SESSION['REGISTER_MESSAGE'], $_SESSION['REGISTER_SUCCESS']);
            ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="submit_registration.php">
        <label for="full_name">Nom complet</label>
        <input type="text" id="full_name" name="full_name" required>

        <label for="email">Adresse e-mail</label>
        <input type="email" id="email" name="email" required>

        <label for="password">Mot de passe</label>
        <input type="password" id="password" name="password" required>

        <label for="age">Âge</label>
        <input type="age" id="age" name="age" required>

        <input type="submit" value="S'inscrire">
    </form>

</body>
</html>