<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>À propos – Saveurs &amp; Saisons</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
            rel="stylesheet"
    >
    <link
            href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css"
            rel="stylesheet"
    >
    <!-- Vogue‐style serif -->
    <link
            href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap"
            rel="stylesheet"
    >

    <style>
        body {
            background-color: #f9f6f1;
            font-family: 'Georgia', serif;
            color: #333;
        }
        h1 {
            font-family: 'Playfair Display', serif;
            font-size: 2.5rem;
            text-align: center;
            margin-top: 2rem;
        }
        .intro {
            max-width: 720px;
            margin: 1.5rem auto 3rem;
            font-size: 1.1rem;
            line-height: 1.6;
            text-align: center;
        }
        .chef-card {
            max-width: 400px;
            margin: 0 auto 2rem;
            border: none;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }
        .chef-img {
            width: 100%;
            object-fit: cover;
            border-top-left-radius: .375rem;
            border-top-right-radius: .375rem;
            max-height: 400px;
        }
        .chef-body {
            text-align: center;
            padding: 1rem;
        }
        .chef-name {
            font-family: 'Playfair Display', serif;
            font-size: 1.5rem;
            margin-bottom: .25rem;
        }
        .chef-role {
            font-style: italic;
            color: #777;
            margin-bottom: .75rem;
        }
        .features {
            max-width: 720px;
            margin: 0 auto 3rem;
        }
        .features li {
            margin-bottom: .5rem;
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<?php require_once(__DIR__ . '/header.php'); ?>

<main class="flex-fill">
    <div class="container">
        <h1>À propos de Saveurs &amp; Saisons</h1>
        <p class="intro">
            Saveurs &amp; Saisons est votre plateforme culinaire où chaque recette
            est inspirée par le goût authentique de chaque saison. Parcourez,
            partagez, commentez et notez vos plats favoris — vivez la cuisine en
            toute élégance.
        </p>

        <div class="card chef-card">
            <img
                    src="images/damecooking.png"
                    alt="Lara Croft"
                    class="chef-img img-fluid"
            >
            <div class="chef-body">
                <div class="chef-name">Lara Croft</div>
                <div class="chef-role">Fondatrice &amp; Chef du site</div>
                <p>
                    Passionnée par la gastronomie, Lara veille à vous proposer
                    des recettes raffinées, adaptées à chaque saison, et une expérience
                    conviviale pour toute la communauté.
                </p>
            </div>
        </div>

        <h2 class="text-center mb-3">Fonctionnalités clés</h2>
        <ul class="features list-unstyled">
            <li><i class="bi bi-cloud-sun-fill me-2"></i>Recettes triées par saisons</li>
            <li><i class="bi bi-star-fill me-2"></i>Système de notation par étoiles</li>
            <li><i class="bi bi-chat-dots me-2"></i>Commentaires et avis de la communauté</li>
            <li><i class="bi bi-upload me-2"></i>Ajout et modification d’images de vos plats</li>
            <li><i class="bi bi-plus-circle me-2"></i>Proposez et gérez vos propres recettes</li>
        </ul>
    </div>
</main>

<?php require_once(__DIR__ . '/footer.php'); ?>
<script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
></script>
</body>
</html>
