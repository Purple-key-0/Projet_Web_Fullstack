<?php
session_start();
require __DIR__ . '/../config/config.php';


if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_SESSION['user']['id'])) {
    header('Location: ../pages/connexion.php');
    exit;
}


$jeton_session    = $_SESSION['csrf'] ?? '';
$jeton_formulaire = $_POST['csrf'] ?? '';

if (
    $jeton_session === ''
    || !is_string($jeton_formulaire)
    || !hash_equals($jeton_session, $jeton_formulaire)
) {
    http_response_code(403);
    exit('Requête refusée');
}


$id_user  = (int) $_SESSION['user']['id'];
$id_event = filter_input(INPUT_POST, 'id_event', FILTER_VALIDATE_INT);

if (!$id_event) {
    http_response_code(400);
    exit('Événement invalide');
}

try {
    $dbh = new PDO(
        "mysql:host=$host;dbname=$dbName;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );


    $stmt = $dbh->prepare(
        'DELETE FROM user_event WHERE user_id = ? AND event_id = ?'
    );
    $stmt->execute([$id_user, $id_event]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit('Une erreur est survenue');
}

header('Location: ../pages/index.php');
exit;