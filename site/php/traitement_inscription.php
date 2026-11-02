<?php

$email = $_POST['email'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);
$firstName = $_POST['first_name'];
$lastName = $_POST['last_name'];
$birthDate = $_POST['birth_date'];

require __DIR__ . '/../config/config.php';

// Connexion à la base de données MySQL
$dbh = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);

$insertQuery = "INSERT INTO user VALUES (NULL, '$firstName', '$lastName', '$email', '$birthDate', '[\"ROLE_USER\"]', '$password')";
$stt = $dbh->query($insertQuery);

if ($stt === false) {
    echo "Une erreur est survenue";
    sleep(2);
    header('Location: ../pages/connexion.php');
} else {
    echo "Utilisateur enregistré";
    sleep(2);
    header('Location: ../pages/index.php');
}