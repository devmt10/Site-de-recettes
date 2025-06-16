<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contact – Saveurs & Saisons</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body class="d-flex flex-column min-vh-100">
<?php require_once(__DIR__ . '/header.php'); ?>
<main class="flex-fill py-4">
    <div class="container">
        <h1>Contactez-nous</h1>
        <?php if (isset($_GET['success'])): ?>
            <div class="contact-feedback">
                <h3><i class="bi bi-envelope-check-fill me-2"></i> Message envoyé</h3>
                <p>Merci pour votre message. Nous reviendrons vers vous dans les plus brefs délais.</p>
                <a href="index.php" class="btn btn-primary btn-sm">
                    <i class="bi bi-house-door-fill me-1"></i> Retour à l'accueil
                </a>
            </div>
        <?php elseif (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>
        <?php if (!isset($_GET['success'])): ?>
            <div class="card contact-card">
                <div class="card-body">
                    <form action="submit_contact.php" method="POST" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label for="email" class="form-label">Votre email</label>
                            <input type="email" class="form-control" id="email" name="email" required placeholder="exemple@domaine.com">
                            <div class="form-text">Nous ne revendrons pas votre adresse.</div>
                        </div>
                        <div class="mb-3">
                            <label for="message" class="form-label">Votre message</label>
                            <textarea class="form-control" id="message" name="message" rows="5" required placeholder="Écrivez-nous vos impressions, questions ou suggestions…"></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="screenshot" class="form-label">Joindre une capture d’écran (optionnel)</label>
                            <input type="file" class="form-control" id="screenshot" name="screenshot" accept="image/*">
                        </div>
                        <div class="text-center">
                            <button type="submit" class="btn btn-primary btn-sm">
                                <i class="bi bi-envelope-fill me-1"></i> Envoyer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php endif; ?>
    </div>
</main>
<?php require_once(__DIR__ . '/footer.php'); ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>