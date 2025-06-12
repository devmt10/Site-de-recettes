<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion – Saveurs &amp; Saisons</title>
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
    <!-- Vogue Serif -->
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
        .navbar {
            /* your existing header styling is in header.php */
        }
        main {
            flex: 1;
        }
        h4 {
            font-family: 'Playfair Display', serif;
            font-size: 1.75rem;
            text-align: center;
            margin-bottom: 1.5rem;
        }
        .login-card {
            max-width: 400px;
            margin: 2rem auto;
            border: none;
            border-radius: .5rem;
            box-shadow: 0 4px 20px rgba(0,0,0,0.05);
        }
        .login-card .card-body {
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

<main>
    <div class="container">
        <?php if (!isset($_SESSION['LOGGED_USER'])): ?>
            <div class="card login-card">
                <div class="card-body">
                    <h4>Se connecter</h4>

                    <?php if (!empty($_SESSION['LOGIN_ERROR_MESSAGE'])): ?>
                        <div class="alert alert-danger text-center">
                            <?= htmlspecialchars($_SESSION['LOGIN_ERROR_MESSAGE']); ?>
                        </div>
                        <?php unset($_SESSION['LOGIN_ERROR_MESSAGE']); ?>
                    <?php endif; ?>

                    <form action="submit_login.php" method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">
                                <i class="bi bi-envelope-fill me-1"></i> Adresse email
                            </label>
                            <input
                                    type="email"
                                    class="form-control"
                                    id="email"
                                    name="email"
                                    required
                                    placeholder="exemple@domaine.com"
                            >
                        </div>

                        <div class="mb-4">
                            <label for="password" class="form-label">
                                <i class="bi bi-lock-fill me-1"></i> Mot de passe
                            </label>
                            <input
                                    type="password"
                                    class="form-control"
                                    id="password"
                                    name="password"
                                    required
                                    placeholder="••••••••"
                            >
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-box-arrow-in-right me-1"></i> Connexion
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <div class="alert alert-success text-center mt-5">
                <i class="bi bi-person-check-fill me-1"></i>
                Bonjour <strong><?= htmlspecialchars($_SESSION['LOGGED_USER']['name']); ?></strong>, vous êtes connecté(e) !
            </div>
        <?php endif; ?>
    </div>
</main>

<?php require_once(__DIR__ . '/footer.php'); ?>

<script
        src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
></script>
</body>
</html>
