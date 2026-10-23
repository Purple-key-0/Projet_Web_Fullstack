<?php

$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$firstName = $_POST['first_name'];
$lastName = $_POST['last_name'];
$birthDate = $_POST['birth_date'];

require __DIR__ . '/../config/config.php';

// Connexion à la base de données MySQL
$dbh = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);

$insertQuery = "INSERT INTO user VALUES (NULL, '$email', '$password', '[\"ROLE_USER\"]', '$lastName', '$firstName', '$birthDate', NOW(), NOW())";
$stt = $dbh->query($insertQuery);

if ($stt === false) {
    echo "Une erreur est survenue";
} else {
    echo "Utilisateur enregistré";
}