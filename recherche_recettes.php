<?php
require_once 'databaseconnect.php';

$q = $_GET['q'] ?? '';

if (strlen($q) >= 2) {
    $stmt = $mysqlClient->prepare("SELECT id, titre, auteur FROM recettes WHERE auteur LIKE :auteur LIMIT 10");
    $stmt->execute(['auteur' => $q . '%']);
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($results) {
        foreach ($results as $recipe) {
            echo '<a href="recette.php?id=' . htmlspecialchars($recipe['id']) . '" class="list-group-item list-group-item-action">';
            echo htmlspecialchars($recipe['titre']) . ' — ' . htmlspecialchars($recipe['auteur']);
            echo '</a>';
        }
    } else {
        echo '<div class="list-group-item">Aucun résultat</div>';
    }
}
?>