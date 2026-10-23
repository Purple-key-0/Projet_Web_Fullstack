<?php

session_start();
require __DIR__ . '/../config/config.php';

$email = $_POST['email'] ?? '';
$password = $_POST['password'] ?? '';

$dbh = new PDO(
    "mysql:host=$host;dbname=$dbName;charset=utf8mb4",
    $user,
    $pass
);
$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$query = 'SELECT id, first_name, last_name, password, roles
          FROM user
          WHERE email = :email';

$stt = $dbh->prepare($query);
$stt->execute(['email' => $email]);
$result = $stt->fetch(PDO::FETCH_ASSOC);

if ($result && password_verify($password, $result['password'])) {
    session_regenerate_id(true);

    $_SESSION['user'] = [
        'id' => $result['id'],
        'first_name' => $result['first_name'],
        'last_name' => $result['last_name'],
        'roles' => $result['roles'],
    ];

    header('Location: ../pages/index.php');
    exit;
}

header('Location: ../pages/connexion.php?erreur=1');
exit;

$email = $_POST['email'];
$password = $_POST['password'];

require __DIR__ . '/../config/config.php';

// Connexion à la base de données MySQL
$dbh = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);

$query = "SELECT first_name, last_name, password FROM user WHERE email = :email ";
$stt = $dbh->prepare($query);
$params = [
    ':email' => $email,
];
$stt->execute($params);
$result = $stt->fetch();

$hashedPassword = $result['password'];

if (password_verify($password, $hashedPassword)) {
    echo "Bienvenue " . $result['first_name'] . ' ' . $result['last_name'];
} else {
    echo "Erreur : identifiant ou mot de passe incorrect";
}