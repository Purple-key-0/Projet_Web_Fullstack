<?php

$email = $_GET['email'];
$password = $_GET['password'];

$host = "sql7.freesqldatabase.com";
$dbName = "sql7839244";
$user = "sql7839244";
$pass = "k4ySRIVWAK";

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