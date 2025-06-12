<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contact – Saveurs &amp; Saisons</title>
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
            margin: 2rem 0 1rem;
        }
        .contact-card {
            max-width: 600px;
            margin: 0 auto 3rem;
            background: #fff;
            border: none;
            border-radius: .5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .contact-card .card-body {
            padding: 2rem;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-primary {
            background-color: #6b5b95;
            border-color: #6b5b95;
        }
        .btn-primary:hover {
            background-color: #5a4a84;
            border-color: #5a4a84;
        }
        .footer-link img {
            filter: invert(1);
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

<?php require_once(__DIR__ . '/header.php'); ?>

<main class="flex-fill py-4">
    <div class="container">
        <h1>Contactez-nous</h1>
        <div class="card contact-card">
            <div class="card-body">
                <form action="submit_contact.php" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label for="email" class="form-label">Votre email</label>
                        <input
                                type="email"
                                class="form-control"
                                id="email"
                                name="email"
                                required
                                placeholder="exemple@domaine.com"
                        >
                        <div class="form-text">Nous ne revendrons pas votre adresse.</div>
                    </div>

                    <div class="mb-4">
                        <label for="message" class="form-label">Votre message</label>
                        <textarea
                                class="form-control"
                                id="message"
                                name="message"
                                rows="5"
                                required
                                placeholder="Écrivez-nous vos impressions, questions ou suggestions…"
                        ></textarea>
                    </div>

                    <div class="mb-4">
                        <label for="screenshot" class="form-label">Joindre une capture d’écran (optionnel)</label>
                        <input
                                type="file"
                                class="form-control"
                                id="screenshot"
                                name="screenshot"
                                accept="image/*"
                        >
                    </div>

                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="bi bi-envelope-fill me-1"></i> Envoyer
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</main>

<?php require_once(__DIR__ . '/footer.php'); ?>

<script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
></script>
</body>
</html>
