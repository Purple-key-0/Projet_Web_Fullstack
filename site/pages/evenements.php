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
<?php require __DIR__ . '/entete.php'; ?>
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