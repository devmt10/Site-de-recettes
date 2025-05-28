<?php
// Démarrer la session si nécessaire
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>À propos – Saveurs & Saisons</title>
    <!-- Lien Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        /* Centrer et redimensionner l'image */
        .about-image {
            width: 100%; /* Ajuster la largeur selon l'écran */
            max-width: 500px; /* Limiter la taille maximale */
            height: auto; /* Garder les proportions de l'image */
            display: block; /* S'assurer qu'elle est affichée en bloc */
            margin: 0 auto; /* Centrer l'image horizontalement */
        }
    </style>
</head>
<body>

    <?php include 'header.php'; ?>

    <main class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h1 class="mb-4 text-center text-primary-emphasis">À propos de Saveurs & Saisons</h1>
                <p class="lead text-justify">
                    <!-- Carousel -->
                    <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            <div class="carousel-item active">
                                <img class="d-block w-100" src="images/chef_plate.jpg" alt="Chef">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="images/friend_cooking.jpg" alt="Friends Cooking">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="images/kid_kitchen.jpg" alt="Kid Cooking">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="images/woman_cooking.jpg" alt="Woman Cooking">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="images/couple_cooking.jpg" alt="Couple cooking">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="images/man_cooking.jpg" alt="Man Cooking">
                            </div>
                            <div class="carousel-item">
                                <img class="d-block w-100" src="images/women_cooking.jpg" alt="Women Cooking">
                            </div>
                        </div>
                    </div>

                    <strong>Saveurs & Saisons</strong> est né d’une passion commune pour la cuisine de saison et le respect du rythme naturel des aliments. 
                    Notre idée est simple : <em>vous accompagner dans la découverte de recettes savoureuses</em>, inspirées par les produits du moment, en tenant compte de la météo, de vos envies, et de votre localisation.
                </p>
                <p class="text-justify">
                    Que vous soyez un amateur curieux ou un cuisinier passionné, notre site vous propose une expérience conviviale pour mieux manger, tout en respectant la nature et les saisons.
                    Nous croyons que <strong>la météo influence nos goûts</strong> : en hiver, on aime les plats réconfortants ; en été, on recherche la fraîcheur. 
                    C’est pourquoi nous avons décidé de combiner des données météo locales avec des suggestions culinaires adaptées.
                </p>
                <p class="text-justify">
                    <strong>Saveurs & Saisons</strong>, c’est un guide moderne, ancré dans la tradition, qui célèbre le bon sens paysan et la gastronomie durable.
                </p>
            </div>
        </div>
    </main>

    <!-- Script Bootstrap -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>