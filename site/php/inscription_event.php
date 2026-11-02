<?php
// session_start() : permet de retrouver l'utilisateur connecté ($_SESSION)
session_start();

// Charge $host, $dbName, $user, $pass (identifiants de la base de données)
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

 
    $stmt = $dbh->prepare('SELECT COUNT(*) FROM events WHERE id_event = ?');
    $stmt->execute([$id_event]);
    if ($stmt->fetchColumn() == 0) {
        http_response_code(404);
        exit('Événement introuvable');
    }

    // L'utilisateur est-il déjà inscrit ? (évite les doublons)
    $stmt = $dbh->prepare(
        'SELECT COUNT(*) FROM user_event WHERE user_id = ? AND event_id = ?'
    );
    $stmt->execute([$id_user, $id_event]);

    if ($stmt->fetchColumn() == 0) {
        $stmt = $dbh->prepare(
            'INSERT INTO user_event (user_id, event_id) VALUES (?, ?)'
        );
        $stmt->execute([$id_user, $id_event]);
    }
} catch (PDOException $e) {
    
    error_log($e->getMessage());
    http_response_code(500);
    exit('Une erreur est survenue');
}

header('Location: ../pages/reservations.php');
exit;