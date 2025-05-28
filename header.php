<?php if (session_status() === PHP_SESSION_NONE) {
    session_start();
} ?>

<!-- Barre de navigation avec Bootstrap et champ de recherche stylisé -->
<nav class="navbar navbar-expand-lg navbar-light bg-light custom-navbar">
    <div class="container-fluid">
        <!-- Logo + nom du site -->
        <a class="navbar-brand d-flex align-items-center" href="index.php">
            <img src="images/food_meteo.png" alt="Logo Saveurs & Saisons">
            <span class="site-title">Saveurs & Saisons</span>
        </a>

        <!-- Bouton mobile -->
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Contenu menu + champ de recherche -->
        <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item"><a class="nav-link active" href="index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link active" href="info.php">À propos</a></li>
                <li class="nav-item"><a class="nav-link active" href="registration.php">S'inscrire</a></li>
                <li class="nav-item"><a class="nav-link" href="contact.php">Contact</a></li>

                <?php if (isset($_SESSION['LOGGED_USER'])) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Déconnexion</a>
                    </li>
                <?php else : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Se connecter</a>
                    </li>
                <?php endif; ?>
            </ul>

            <!-- 🔎 Champ de recherche -->
            <form class="d-flex ms-3 position-relative" id="search-form" autocomplete="off">
                <input class="form-control search-input" type="search" id="search-author" placeholder="Rechercher un auteur..." aria-label="Search">
                <div id="results" class="list-group search-results"></div>
            </form>
        </div>
    </div>
</nav>