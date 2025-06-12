<?php
require_once(__DIR__ . '/config/mysql.php');
require_once(__DIR__ . '/databaseconnect.php');

$postData = $_POST;

if (
    !isset($postData['email'])
    || !filter_var($postData['email'], FILTER_VALIDATE_EMAIL)
    || !isset($postData['message'])
    || trim($postData['message']) === ''
) {
    echo('Il faut un email et un message valides pour soumettre le formulaire.');
    return;
}

$email = $postData['email'];
$message = trim($postData['message']);
$screenshotPath = null;

// Handle file upload
if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] === 0) {
    if ($_FILES['screenshot']['size'] > 1000000) {
        echo "L'envoi n'a pas pu être effectué, erreur ou image trop volumineuse";
        return;
    }

    $fileInfo = pathinfo($_FILES['screenshot']['name']);
    $extension = strtolower($fileInfo['extension']);
    $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
    if (!in_array($extension, $allowedExtensions)) {
        echo "L'envoi n'a pas pu être effectué, l'extension {$extension} n'est pas autorisée";
        return;
    }

    $path = __DIR__ . '/uploads/';
    if (!is_dir($path)) {
        if (!mkdir($path, 0755, true)) {
            echo "L'envoi n'a pas pu être effectué, impossible de créer le dossier uploads";
            return;
        }
    }

    $uniqueFileName = uniqid('screenshot_', true) . '.' . $extension;
    if (move_uploaded_file($_FILES['screenshot']['tmp_name'], $path . $uniqueFileName)) {
        $screenshotPath = 'uploads/' . $uniqueFileName;
    } else {
        echo "Une erreur est survenue lors de l'envoi du fichier.";
        return;
    }
}

// Save to database
try {
    $insertContact = $mysqlClient->prepare('
        INSERT INTO contact (email, message)
        VALUES (:email, :message)
    ');

    $insertContact->execute([
        'email' => $email,
        'message' => $message,
    ]);

    // Success - continue to display the confirmation page

} catch (PDOException $e) {
    echo "Une erreur est survenue lors de l'enregistrement du message: " . $e->getMessage();
    return;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de Recettes - Contact reçu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: Arial, sans-serif;
        }
        .container {
            margin-top: 50px;
        }
        .card {
            margin-top: 20px;
        }
        .alert-success {
            margin-top: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <?php require_once(__DIR__ . '/header.php'); ?>

    <h1 class="text-center mb-4">Message bien reçu !</h1>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Rappel de vos informations</h5>
            <p class="card-text"><b>Email</b> : <?php echo htmlspecialchars($email); ?></p>
            <p class="card-text"><b>Message</b> : <?php echo nl2br(htmlspecialchars($message)); ?></p>
            <?php if ($screenshotPath) : ?>
                <div class="alert alert-success" role="alert">
                    L'envoi a bien été effectué !<br>
                    Fichier: <?php echo htmlspecialchars($screenshotPath); ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="text-center mt-4">
        <a href="index.php" class="btn btn-primary">Retour à l'accueil</a>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>