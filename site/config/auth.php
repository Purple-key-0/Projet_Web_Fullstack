<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function estConnecte(): bool {
    return !empty($_SESSION['user']['id']);
}

function estAdmin(): bool {
    $roles = json_decode($_SESSION['user']['roles'] ?? '[]', true) ?: [];
    return in_array('ROLE_ADMIN', $roles, true);
}

function exigerConnexion(): void {
    if (!estConnecte()) { header('Location: connexion.php'); exit; }
}

function exigerAdmin(): void {
    if (!estAdmin()) { header('Location: index.php'); exit; }
}