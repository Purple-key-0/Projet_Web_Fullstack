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

$id           = $_SESSION['user']['id'];
$ancien       = $_POST['ancien'] ?? '';
$nouveau      = $_POST['nouveau'] ?? '';
$confirmation = $_POST['confirmation'] ?? '';

if ($nouveau !== $confirmation) repondre(false, 'Les mots de passe ne correspondent pas', 400);
if (strlen($nouveau) < 8)       repondre(false, 'Minimum 8 caractères', 400);

$stmt = $dbh->prepare('SELECT password FROM user WHERE id = ?');
$stmt->execute([$id]);
$hash = $stmt->fetchColumn();

if (!$hash || !password_verify($ancien, $hash)) repondre(false, 'Ancien mot de passe incorrect', 403);

$stmt = $dbh->prepare('UPDATE user SET password = ? WHERE id = ?');
$stmt->execute([password_hash($nouveau, PASSWORD_DEFAULT), $id]);

repondre(true, 'Mot de passe modifié avec succès');