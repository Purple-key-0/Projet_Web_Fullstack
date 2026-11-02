<?php
require __DIR__ . '/../config/config.php';


$email     = trim($_POST['email'] ?? '');
$password  = $_POST['password'] ?? '';
$firstName = trim($_POST['first_name'] ?? '');
$lastName  = trim($_POST['last_name'] ?? '');
$birthDate = trim($_POST['birth_date'] ?? '');


$date = DateTime::createFromFormat('Y-m-d', $birthDate);

if (
    !filter_var($email, FILTER_VALIDATE_EMAIL)
    || strlen($password) < 8 || strlen($password) > 72 // bcrypt ignore au-delà de 72 octets
    || $firstName === '' || $lastName === ''
    || !$date || $date->format('Y-m-d') !== $birthDate
) {
    http_response_code(400);
    exit('Données invalides');
}

try {
    $dbh = new PDO(
        "mysql:host=$host;dbname=$dbName;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    // L'e-mail est-il déjà utilisé ?
    $check = $dbh->prepare('SELECT COUNT(*) FROM user WHERE email = ?');
    $check->execute([$email]);
    if ($check->fetchColumn() > 0) {
        http_response_code(409);
        exit('Cette adresse est déjà utilisée');
    }

   
    $stt = $dbh->prepare(
        'INSERT INTO user (email, password, role, last_name, first_name, birthDate)
         VALUES (?, ?, ?, ?, ?, ?)'
    );
    $stt->execute([
        $email,
        password_hash($password, PASSWORD_DEFAULT), // jamais le mot de passe en clair
        '["ROLE_USER"]',
        $lastName,
        $firstName,
        $birthDate,
    ]);

 
    header('Location: ../pages/index.php');
    exit;
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    echo 'Une erreur est survenue';
}