<?php
require __DIR__ . '/config.php';

$dbh = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $user, $pass, [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);