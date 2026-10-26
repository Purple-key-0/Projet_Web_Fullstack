<?php
require __DIR__ . '/../config/auth.php';
require __DIR__ . '/../config/db.php';
header('Content-Type: application/json; charset=utf-8');

function repondre(bool $succes, string $message, int $code = 200): void {
    http_response_code($code);
    echo json_encode(['succes' => $succes, 'message' => $message]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') repondre(false, 'Méthode non autorisée', 405);
if (!estConnecte())                        repondre(false, 'Vous devez être connecté', 401);

$id    = $_SESSION['user']['id'];
$email = trim($_POST['email'] ?? '');
$mdp   = $_POST['mdp'] ?? '';

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) repondre(false, 'Adresse e-mail invalide', 400);

$stmt = $dbh->prepare('SELECT password FROM user WHERE id = ?');
$stmt->execute([$id]);
$hash = $stmt->fetchColumn();
if (!$hash || !password_verify($mdp, $hash)) repondre(false, 'Mot de passe incorrect', 403);

$stmt = $dbh->prepare('SELECT COUNT(*) FROM user WHERE email = ? AND id <> ?');
$stmt->execute([$email, $id]);
if ($stmt->fetchColumn() > 0) repondre(false, 'Cette adresse est déjà utilisée', 409);

$stmt = $dbh->prepare('UPDATE user SET email = ? WHERE id = ?');
$stmt->execute([$email, $id]);

repondre(true, 'Adresse e-mail modifiée');