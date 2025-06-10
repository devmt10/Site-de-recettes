<?php
require_once(__DIR__ . '/databaseconnect.php');

// Récupération des utilisateurs
$usersStatement = $mysqlClient->prepare('SELECT * FROM `user`'); // ou `users` selon ta table
$usersStatement->execute();
$users = $usersStatement->fetchAll();

// Récupération des recettes
$recipeStatement = $mysqlClient->prepare('SELECT * FROM recipe WHERE is_enabled IS TRUE');
$recipeStatement->execute();
$recipe = $recipeStatement->fetchAll();