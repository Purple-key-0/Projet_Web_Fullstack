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
    <title>Événements</title>
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
<header id="titre_site"><h1>Événements</h1></header>
<header id="bandeau_onglets">
    <h5 id="h5_1"><a href="index.php">Accueil</a></h5>
    <h5><a href="evenements.php">Événements</a></h5>
    <h5><a href="reservations.php">Mes Réservations</a></h5>
    <h5><a href="admin_view.php">Gestion d'Événements</a></h5>
</header>
<body>
    <header class="eve">
        <h5> <a href="filtrer.php">Filtrer</a> </h5>
        <h5> <a href="connexion.php">Se connecter</a> </h5>
    </header>
    <header class="Creer">
        <h3>
            <button type="button"> Créer un événement</button>
        </h3>
    </header>
    <div class="rechercher">
        <input type="search" id ="barre-de-recherche" placeholder="Rechercher un événement">

        <ul id="liste-evenements">
            <li><a href="seance.php" data-mots-cles="gradur décennie rap concert">Décennie - Gradur</a></li>
        </ul>
    </div>
    <header class="nouveaute">
        <h2>Nouveauté !</h2>
        <p>Vendredi 26 Novembre 2026</p>
        <h3> <a href="seance.php">Concert Décennie - Gradur : Bordeaux</a> </h3>
    </header>
    <script src="evenements.js"></script>

</body>
</html>