<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Inscription – Saveurs &amp; Saisons</title>
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
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        h3 {
            font-family: 'Playfair Display', serif;
            text-align: center;
            margin-bottom: 1.5rem;
            font-size: 1.75rem;
        }
        .register-card {
            max-width: 420px;
            margin: 2rem auto;
            border: none;
            border-radius: .5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .register-card .card-body {
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
    </style>
</head>
<body>

<?php require_once(__DIR__ . '/header.php'); ?>

<main class="flex-fill">
    <div class="container">
        <div class="card register-card">
            <div class="card-body">
                <h3>Créer un compte</h3>

                <?php if (!empty($_SESSION['REGISTER_MESSAGE'])): ?>
                    <div class="alert <?= $_SESSION['REGISTER_SUCCESS'] ? 'alert-success' : 'alert-danger' ?> text-center">
                        <?= htmlspecialchars($_SESSION['REGISTER_MESSAGE']) ?>
                    </div>
                    <?php unset($_SESSION['REGISTER_MESSAGE'], $_SESSION['REGISTER_SUCCESS']); ?>
                <?php endif; ?>

                <form action="submit_registration.php" method="POST">
                    <div class="mb-3">
                        <label for="full_name" class="form-label">
                            <i class="bi bi-person-fill me-1"></i> Nom complet
                        </label>
                        <input
                                type="text"
                                id="full_name"
                                name="full_name"
                                class="form-control"
                                required
                                placeholder="Maria Teresa Gueli"
                        >
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">
                            <i class="bi bi-envelope-fill me-1"></i> Adresse e-mail
                        </label>
                        <input
                                type="email"
                                id="email"
                                name="email"
                                class="form-control"
                                required
                                placeholder="vous@exemple.com"
                        >
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">
                            <i class="bi bi-lock-fill me-1"></i> Mot de passe
                        </label>
                        <input
                                type="password"
                                id="password"
                                name="password"
                                class="form-control"
                                required
                                placeholder="••••••••"
                        >
                    </div>

                    <div class="mb-4">
                        <label for="age" class="form-label">
                            <i class="bi bi-calendar-fill me-1"></i> Âge
                        </label>
                        <input
                                type="number"
                                id="age"
                                name="age"
                                class="form-control"
                                required
                                placeholder="30"
                                min="1"
                        >
                    </div>

                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-person-plus me-1"></i> S'inscrire
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
