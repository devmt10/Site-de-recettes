<?php

/**
 * On ne traite pas les super globales provenant de l'utilisateur directement,
 * ces données doivent être testées et vérifiées.
 */

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

$isFileLoaded = false;
// Testons si le fichier a bien été envoyé et s'il n'y a pas des erreurs
if (isset($_FILES['screenshot']) && $_FILES['screenshot']['error'] === 0) {
    // Testons si le fichier est trop volumineux
    if ($_FILES['screenshot']['size'] > 1000000) {
        echo "L'envoi n'a pas pu être effectué, erreur ou image trop volumineuse";
        return;
    }

    // Testons si l'extension n'est pas autorisée
    $fileInfo = pathinfo($_FILES['screenshot']['name']);
    $extension = strtolower($fileInfo['extension']);
    $allowedExtensions = ['jpg', 'jpeg', 'gif', 'png'];
    if (!in_array($extension, $allowedExtensions)) {
        echo "L'envoi n'a pas pu être effectué, l'extension {$extension} n'est pas autorisée";
        return;
    }

    // Testons si le dossier uploads est manquant
    $path = __DIR__ . '/uploads/';
    if (!is_dir($path)) {
        echo "L'envoi n'a pas pu être effectué, le dossier uploads est manquant";
        return;
    }

    // Créer un nom unique pour le fichier pour éviter les collisions
    $uniqueFileName = uniqid('screenshot_', true) . '.' . $extension;

    // On peut valider le fichier et le stocker définitivement
    if (move_uploaded_file($_FILES['screenshot']['tmp_name'], $path . $uniqueFileName)) {
        $isFileLoaded = true;
    } else {
        echo "Une erreur est survenue lors de l'envoi du fichier.";
        return;
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site de Recettes - Contact reçu</title>
    <!-- Bootstrap CSS -->
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
                <p class="card-text"><b>Email</b> : <?php echo($postData['email']); ?></p>
                <p class="card-text"><b>Message</b> : <?php echo(strip_tags($postData['message'])); ?></p>
                <?php if ($isFileLoaded) : ?>
                    <div class="alert alert-success" role="alert">
                        L'envoi a bien été effectué !
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="text-center mt-4">
            <a href="index.php" class="btn btn-primary">Retour à l'accueil</a>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>