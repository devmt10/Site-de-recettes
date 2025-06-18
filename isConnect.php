<?php
// 🔐 Sécurité : Redirection si l'utilisateur n'est pas connecté

if (empty($_SESSION['LOGGED_USER']) || empty($_SESSION['LOGGED_USER']['user_id'])) {
    echo('Il faut être authentifié pour cette action.');
    exit;
}
