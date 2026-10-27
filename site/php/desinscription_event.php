<?php
session_start();
require __DIR__ . '/../config/config.php';

$dbh = new PDO(
    "mysql:host=$host;dbname=$dbName;charset=utf8mb4",
    $user,
    $pass
);
$id_user = $_SESSION['user']['id'];
$id_event = $_POST['id_event'];

$stmt = $dbh->prepare(
    'DELETE FROM user_event WHERE user_id = ? AND event_id = ?'
);
$stmt->execute([$id_user, $id_event]);

header('Location: ../pages/index.php');
exit;