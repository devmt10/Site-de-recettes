<?php
session_start();
require_once __DIR__.'/isConnect.php';
require_once __DIR__.'/config/mysql.php';
require_once __DIR__.'/databaseconnect.php';

// Load enabled seasons
$seasonStmt = $mysqlClient->prepare('SELECT season_id, title FROM SEASON WHERE is_enabled = 1');
$seasonStmt->execute();
$seasons = $seasonStmt->fetchAll(PDO::FETCH_ASSOC);
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Proposer une recette – Saveurs &amp; Saisons</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background: #f9f6f1; font-family: Georgia, serif; color: #333; }
    main { flex: 1; padding: 2rem 0; }
    .form-card { max-width: 720px; margin: 0 auto; }
    .card { border: none; border-radius: .5rem; box-shadow: 0 4px 20px rgba(0,0,0,0.05); }
    .card-body { padding: 2rem; }
    h1 { font-family: 'Playfair Display', serif; text-align: center; margin-bottom: 1.5rem; }
    .btn-primary { background: #6b5b95; border-color: #6b5b95; }
    .btn-primary:hover { background: #5a4a84; border-color: #5a4a84; }
  </style>
</head>
<body class="d-flex flex-column min-vh-100">

<?php require __DIR__.'/header.php'; ?>

<main>
  <div class="container form-card">
    <h1>Proposer une recette</h1>
    <div class="card">
      <div class="card-body">

        <?php if (!empty($_SESSION['CREATE_ERROR'])): ?>
          <div class="alert alert-danger text-center">
            <?= htmlspecialchars($_SESSION['CREATE_ERROR']) ?>
            <?php unset($_SESSION['CREATE_ERROR']); ?>
          </div>
        <?php endif; ?>

        <form action="recipes_post_create.php" method="POST" enctype="multipart/form-data">
          <div class="row g-3">

            <!-- Title -->
            <div class="col-12">
              <label class="form-label" for="title">
                <i class="bi bi-card-text me-1"></i> Titre
              </label>
              <input type="text" id="title" name="title" class="form-control" required placeholder="Ex. Tarte tatin aux pommes">
            </div>

            <!-- Recipe -->
            <div class="col-12">
              <label class="form-label" for="recipe">
                <i class="bi bi-journals me-1"></i> Description
              </label>
              <textarea id="recipe" name="recipe" class="form-control" rows="5" required placeholder="Décrivez la préparation…"></textarea>
            </div>

            <!-- Season -->
            <div class="col-md-6">
              <label class="form-label" for="season_id">
                <i class="bi bi-calendar-event me-1"></i> Saison
              </label>
              <select id="season_id" name="season_id" class="form-select" required>
                <option value="" disabled selected>Sélectionnez</option>
                <?php foreach ($seasons as $s): ?>
                  <option value="<?= $s['season_id'] ?>"><?= ucfirst(htmlspecialchars($s['title'])) ?></option>
                <?php endforeach; ?>
              </select>
            </div>

            <!-- Type -->
            <div class="col-md-6">
              <label class="form-label" for="type">
                <i class="bi bi-tag me-1"></i> Type
              </label>
              <select id="type" name="type" class="form-select" required>
                <option value="" disabled selected>Sélectionnez</option>
                <option value="sucré">Sucrée</option>
                <option value="salé">Salée</option>
              </select>
            </div>

            <!-- Image -->
            <div class="col-12">
              <label class="form-label" for="image">
                <i class="bi bi-image me-1"></i> Image (optionnel)
              </label>
              <input type="file" id="image" name="image" accept="image/*" class="form-control">
              <div class="form-text">Formats JPG/PNG/GIF, max. 2 Mo</div>
            </div>

            <!-- Status -->
            <div class="col-md-6">
              <label class="form-label" for="status">
                <i class="bi bi-clipboard-check me-1"></i> Statut
              </label>
              <select id="status" name="status" class="form-select" required>
                <option value="published">Publié</option>
                <option value="draft">Brouillon</option>
              </select>
            </div>

            <!-- Submit -->
            <div class="col-12 text-center mt-4">
              <button type="submit" class="btn btn-primary px-4">
                <i class="bi bi-plus-circle me-1"></i> Ajouter la recette
              </button>
            </div>
          </div>
        </form>

      </div>
    </div>
  </div>
</main>

<?php require __DIR__.'/footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
