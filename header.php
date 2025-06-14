<?php
if (session_status() === PHP_SESSION_NONE) session_start();
?>
<nav class="navbar navbar-expand-md navbar-light bg-white border-bottom py-2">
    <div class="container">
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <i class="bi bi-cloud-sun-fill me-2"></i>
            <span class="h4 mb-0" style="font-family:'Playfair Display',serif;">Saveurs &amp; Saisons</span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto mb-2 mb-md-0">
                <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="info.php">À propos</a></li>
                <li class="nav-item"><a class="nav-link" href="recipes_create.php">Proposer une recette</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>
            </ul>
            <div class="d-flex align-items-center">
                <?php if (isset($_SESSION['LOGGED_USER'])): ?>
                    <span class="me-3 px-3 py-1 rounded" style="border:1px solid #333;">
            <i class="bi bi-person-check me-1"></i>
            <?= htmlspecialchars($_SESSION['LOGGED_USER']['name']) ?>
          </span>
                    <a href="logout.php" class="btn btn-outline-dark btn-sm">Déconnexion</a>
                <?php else: ?>
                    <a href="login.php" class="btn btn-outline-dark btn-sm me-2">Connexion</a>
                    <a href="registration.php" class="btn btn-dark btn-sm">Inscription</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</nav>
