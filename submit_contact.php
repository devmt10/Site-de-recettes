<?php
session_start();
require_once(__DIR__ . '/header.php');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Message envoyé – Saveurs &amp; Saisons</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Vogue‐style serif -->
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

    <style>
        body {
            background-color: #f9f6f1;
            font-family: 'Georgia', serif;
            color: #333;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        .contact-feedback {
            max-width: 600px;
            margin: 4rem auto;
            background: #fff;
            border-radius: 0.5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
            padding: 2.5rem;
            text-align: center;
        }

        .contact-feedback h3 {
            font-family: 'Playfair Display', serif;
            margin-bottom: 1rem;
            font-size: 2rem;
        }

        .contact-feedback p {
            font-size: 1.05rem;
            color: #555;
        }

        .btn-back {
            margin-top: 2rem;
            background-color: #6b5b95;
            color: white;
            border: none;
            padding: 0.5rem 1.25rem;
            font-size: 0.95rem;
            border-radius: 0;
        }

        .btn-back:hover {
            background-color: #5a4a84;
        }
    </style>
</head>

<body>

<main class="flex-fill">
    <div class="contact-feedback">
        <h3><i class="bi bi-envelope-check-fill me-2"></i> Message envoyé</h3>

        <p>Merci pour votre message. Nous reviendrons vers vous dans les plus brefs délais.</p>

        <a href="contact.php" class="btn btn-back">
            <i class="bi bi-arrow-left"></i> Retour au formulaire
        </a>
    </div>
</main>

<?php require_once(__DIR__ . '/footer.php'); ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
