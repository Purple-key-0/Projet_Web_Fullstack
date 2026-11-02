<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function estConnecte(): bool {
    return !empty($_SESSION['user']['id']);
}

function estAdmin(): bool {
    $valeur = (string) ($_SESSION['user']['roles'] ?? '');

    // Cas 1 : le rôle est stocké en JSON, par exemple ["ROLE_ADMIN"].
    // json_decode() le transforme en tableau PHP.
    $roles = json_decode($valeur, true);

    // Cas 2 : le rôle est un simple texte, par exemple ROLE_ADMIN
    // (json_decode ne donne alors pas de tableau).
    if (is_string($roles)) {
        $roles = [$roles];
    } elseif (!is_array($roles)) {
        $roles = [$valeur];
    }

    return in_array('ROLE_ADMIN', $roles, true);
}

function exigerConnexion(): void {
    if (!estConnecte()) { header('Location: connexion.php'); exit; }
}

function exigerAdmin(): void {
    if (!estAdmin()) { header('Location: index.php'); exit; }
}