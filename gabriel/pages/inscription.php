<?php

$host = "sql7.freesqldatabase.com";
$dbName = "sql7839244";
$user = "sql7839244";
$pass = "k4ySRIVWAK";

// Connexion à la base de données MySQL
$dbh = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);

// Définition de la requête MySQL
$query  = "SELECT last_name, first_name FROM user";

// Envoi de la requête à la base de données et récupération des résultats de la requête
$stt = $dbh->query($query);
$result = $stt->fetchAll();

$htmlTable = "";
foreach ($result as $value) {
    $htmlTable .= "<tr>";
    $htmlTable .= "<td>{$value['last_name']}</td>";
    $htmlTable .= "<td>{$value['first_name']}</td>";
    $htmlTable .= "</tr>";
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/index.js"></script>
</head>
<header id="en_tete">
    <div class="menu-container">
    <img src="../assets/img/logo_menu.svg"
         id="logo_menu"
         alt="Ouvrir le menu"
         onclick="toggleMenu()">

    <div id="menu_deroulant" class="menu-cache">
        <a href="index.php">Accueil</a>
        <a href="evenements.php">Événements</a>
        <a href="reservations.php">Mes Réservations</a>
        <a href="admin_view.php">Gestion d'Événements</a>
    </div>
</div>
    <div><h4 id="connection"><a href="connexion.php">Se connecter</a></h4></div>
</header>
<header id="titre_site"><h1>Whitelist</h1></header>
<header id="bandeau_onglets">
    <h5 id="h5_1"><a href="index.php">Accueil</a></h5>
    <h5><a href="evenements.php">Événements</a></h5>
    <h5><a href="reservations.php">Mes Réservations</a></h5>
    <h5><a href="admin_view.php">Gestion d'Événements</a></h5>
</header>

<body>
    <form action="../php/traitement_inscription.php">
        <div>
            <label for="email">Votre e-mail</label>
            <input type="email" name="email" id="email">
        </div>
        <div>
            <label for="password">Votre mot de passe</label>
            <input type="password" name="password" id="password">
        </div>
        <div>
            <label for="last-name">Votre nom de famille</label>
            <input type="text" name="last_name" id="last-name">
        </div>
        <div>
            <label for="first-name">Votre prénom</label>
            <input type="text" name="first_name" id="first-name">
        </div>
        <div>
            <label for="birth-date">Votre date de naissance</label>
            <input type="date" name="birth_date" id="birth-date">
        </div>
        <input type="submit" value="S'inscrire">
    </form>
</body>

</html>