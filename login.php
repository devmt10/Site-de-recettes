<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
    <!-- Intégration de Bootstrap 5 depuis CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <?php if (!isset($_SESSION['LOGGED_USER'])) : ?>
                <div class="card shadow">
                    <div class="card-body">
                        <h4 class="card-title text-center mb-4">Se connecter</h4>

                        <?php if (isset($_SESSION['LOGIN_ERROR_MESSAGE'])) : ?>
                            <div class="alert alert-danger text-center">
                                <?= $_SESSION['LOGIN_ERROR_MESSAGE']; ?>
                            </div>
                            <?php unset($_SESSION['LOGIN_ERROR_MESSAGE']); ?>
                        <?php endif; ?>

                        <form action="submit_login.php" method="POST">
                            <div class="mb-3">
                                <label for="email" class="form-label">Adresse email :</label>
                                <input type="email" class="form-control" id="email" name="email" required placeholder="you@example.com">
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label">Mot de passe :</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">Se connecter</button>
                            </div>
                        </form>
                    </div>
                </div>
            <?php else : ?>
                <div class="alert alert-success text-center">
                    Bonjour <strong><?= htmlspecialchars($_SESSION['LOGGED_USER']['name']); ?></strong>, vous êtes connecté(e) !
                </div>
            <?php endif; ?>

        </div>
    </div>
</div>

</body>
</html>