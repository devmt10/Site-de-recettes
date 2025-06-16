<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Saveurs & Saisons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/layout.css">
    <link rel="stylesheet" href="css/components.css">
    <link rel="stylesheet" href="css/header.css">
    <link rel="stylesheet" href="css/footer.css">
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<nav class="navbar navbar-expand-md navbar-light bg-white border-bottom w-100">
    <div class="container-fluid px-4">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <i class="bi bi-cloud-sun-fill me-2"></i>
            <span class="brand-text">Saveurs & Saisons</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="mainNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-between" id="mainNav">
            <ul class="navbar-nav mb-2 mb-md-0 d-flex flex-row gap-3">
                <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="info.php">À propos</a></li>
                <li class="nav-item"><a class="nav-link" href="recipes_create.php">Proposer une recette</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            </ul>

            <div class="d-flex align-items-center gap-2 flex-wrap">
                <?php if (isset($_SESSION['LOGGED_USER'])): ?>
                    <span class="user-name d-inline-flex align-items-center px-3 py-2 border">
                        <i class="bi bi-person-check me-1"></i>
                        <?= htmlspecialchars($_SESSION['LOGGED_USER']['full_name'] ?? 'Utilisateur') ?>
                    </span>
                    <a href="logout.php" class="btn btn-dark btn-sm">Déconnexion</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-dark btn-sm">Connexion</a>
                    <a href="registration.php" class="btn btn-dark btn-sm">Inscription</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
