<?php require_once(__DIR__ . '/isConnect.php'); ?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;700&display=swap" rel="stylesheet">

<style>
    .btn-primary {
        background-color: #6b5b95;
        border-color: #6b5b95;
    }
    .btn-primary:hover {
        background-color: #5a4a84;
        border-color: #5a4a84;
    }
    .form-label {
        font-weight: bold;
        font-family: 'Georgia', serif;
    }
    textarea, input {
        font-family: 'Georgia', serif;
    }
</style>

<form action="comments_post_create.php" method="POST" class="mt-4">
    <input type="hidden" name="recipe_id" value="<?= $recipe['recipe_id'] ?>" />

    <div class="mb-3">
        <label for="review" class="form-label">
            <i class="bi bi-star-fill me-1"></i> Note (de 1 à 5)
        </label>
        <input
                type="number"
                class="form-control"
                id="review"
                name="review"
                min="1"
                max="5"
                step="1"
                required
        >
    </div>

    <div class="mb-3">
        <label for="comment" class="form-label">
            <i class="bi bi-chat-left-text me-1"></i> Commentaire
        </label>
        <textarea
                class="form-control"
                id="comment"
                name="comment"
                placeholder="Soyez respectueux·se, nous sommes humain·es."
                rows="4"
                required
        ></textarea>
    </div>

    <div class="text-center mt-3">
        <button type="submit" class="btn btn-primary px-4">
            <i class="bi bi-send me-1"></i> Envoyer
        </button>
    </div>
</form>
