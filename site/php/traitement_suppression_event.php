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

$id_event = filter_var($_POST['id_event'] ?? '', FILTER_VALIDATE_INT);
if (!$id_event) {
    http_response_code(400);
    exit('Données invalides');
}

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbName;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
    $stmt = $pdo->prepare('DELETE FROM events WHERE id_event = ?');
    $stmt->execute([$id_event]);
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit("Impossible de supprimer l'événement (il est peut-être lié à des réservations).");
}

// Supprime les images associées
foreach (['image_event_avant', 'image_event_apres'] as $dossier) {
    foreach (glob(__DIR__ . '/../assets/img/' . $dossier . '/' . $id_event . '.*') ?: [] as $fichier) {
        @unlink($fichier);
    }
}

header('Location: ../pages/evenements.php');
exit;