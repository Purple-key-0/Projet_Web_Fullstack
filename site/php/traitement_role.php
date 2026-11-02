<?php
session_start();
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../config/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Le formulaire n'a pas été envoyé.");
}
if (!estConnecte() || !estAdmin()) {
    http_response_code(403);
    exit('Accès refusé');
}

$jeton_session    = $_SESSION['csrf'] ?? '';
$jeton_formulaire = $_POST['csrf'] ?? '';
if ($jeton_session === '' || !is_string($jeton_formulaire) || !hash_equals($jeton_session, $jeton_formulaire)) {
    http_response_code(403);
    exit('Requête refusée');
}

$id_user = filter_var($_POST['id_user'] ?? '', FILTER_VALIDATE_INT);
$role    = $_POST['role'] ?? '';

if (!$id_user || !in_array($role, ['ROLE_USER', 'ROLE_ADMIN'], true)) {
    http_response_code(400);
    exit('Données invalides');
}
if ($id_user === (int) ($_SESSION['user_id'] ?? 0)) {
    http_response_code(400);
    exit('Vous ne pouvez pas modifier votre propre rôle.');
}

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

    $stmt = $pdo->prepare('UPDATE user SET role = ? WHERE id_user = ?');
    $stmt->execute([json_encode([$role]), $id_user]);   // donne ["ROLE_ADMIN"] ou ["ROLE_USER"]

    $_SESSION['flash'] = 'Rôle mis à jour.';
} catch (PDOException $e) {
    error_log($e->getMessage());
    $_SESSION['flash'] = 'Erreur lors de la mise à jour du rôle.';
}

header('Location: ../pages/gestion_users.php');
exit;