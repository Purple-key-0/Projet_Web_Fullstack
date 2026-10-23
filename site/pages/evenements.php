<?php

require __DIR__ . '/../config/config.php';

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
        <a href="profil.php">Mon Compte</a>
        <a href="evenements.php">Événements</a>
        <a href="reservations.php">Mes Réservations</a>
        <?php if (isset($_SESSION['user']['roles']) && $_SESSION['user']['roles'] === '["ROLE_ADMIN"]' ):?>
            <a href="admin_view.php">Gestion d'Événements</a>
        <?php endif; ?>
    </div>
    </div>
    <div><h4 id="connection">
        <?php if (!isset($_SESSION['user'])):?>
            <a href="connexion.php">Se connecter</a>
        <?php else: ?>
            <?php session_destroy(); ?>
            <a href="index.php">Se déconnecter</a>
        <?php endif; ?>
    </h4></div>
</header>
<header id="titre_site"><h1>Événements</h1></header>
<header id="bandeau_onglets">
    <h5 id="h5_1"><a href="index.php">Accueil</a></h5>
    <h5><a href="evenements.php">Événements</a></h5>
    <h5><a href="reservations.php">Mes Réservations</a></h5>
    <h5>
        <?php if (isset($_SESSION['user']['roles']) && $_SESSION['user']['roles'] === '["ROLE_ADMIN"]' ):?>
            <a href="admin_view.php">Gestion d'Événements</a>
        <?php endif; ?>
    </h5>
</header>
<body>
    
    <div class="rechercher">
    <input
        type="search"
        id="barre-de-recherche"
        placeholder="Rechercher un événement"
        autocomplete="off"
    >

    <ul id="liste-evenements">
        <li>
            <a href="seance.html" data-mots-cles="Musique Concert Rap français Hip-hop Rap Musique urbaine Événement musical Live / spectacle musical Culture Sortie / événement">
                Décennie - Gradur
            </a>
        </li>

        <li>
            <a href="festival.html" data-mots-cles="festival musique été">
                Festival de musique
            </a>
        </li>
    </ul>
</div>
    <header class="nouveaute">
        <h2>Nouveauté !</h2>

        <div class="nouveaute-infos">
            <p><i>Vendredi 26 Novembre 2026  :</i></p>
            <h3> <a href="seance.html" class="nouveaute-lien">Concert Décennie - Gradur : Bordeaux</a> </h3>
        </div>
        <div class="gradur">
            <img src="../assets/img/Decennie.jpg" alt="Gradur" class="nouveaute-image">
        </div>
    </header>
    <header class="delete-edit">
        <h3>
            <button type="button"> Modifier l'événement</button>
            <button type="button"> Supprimer l'événement</button>
        </h3>
    </header>
    <script src="../js/evenements.js"></script>

</body>
</html>