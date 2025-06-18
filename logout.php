<?php
// 🔐 Sécurité : Destruction propre de la session

session_start(); // Démarrez la session si ce n'est pas déjà fait

require_once(__DIR__ . '/functions.php');

// Détruire la session
session_unset();
session_unset();
session_destroy();
// 🔐 Sécurité : Nettoyage complet de la session

// Rediriger l'utilisateur vers la page d'accueil
redirectToUrl('index.php');
