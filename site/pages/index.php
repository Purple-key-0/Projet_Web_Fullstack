<?php
session_start();

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

<body id ="index-body">
    <div id="bienvenue" class="container">
        <h2>Bienvenue sur Whitelist!</h2>
        <p>Vivez chaque moment en grand : Concerts, spectacles, festivals, théâtre, sport, humour… Retrouvez tous les événements qui font vibrer votre ville et au-delà, en quelques clics seulement.
<br><br>
Notre plateforme vous offre un accès simple et rapide à des milliers d'événements. Choisissez votre place, payez en toute sécurité et recevez votre billet instantanément sur votre téléphone. Plus de files d'attente, plus de stress : l'essentiel, c'est le spectacle.
<br><br>
<span class="pourquoi">Pourquoi nous choisir ?</span>
<br><br>

<span class="reservation">Réservation en 3 clics :</span><br><br> - Une interface fluide, pensée pour aller droit au but.<br>
- Paiement 100 % sécurisé : vos données sont protégées à chaque étape. <br>
- Billets immédiats : e-billet reçu par e-mail et disponible dans votre espace personnel. <br>
- Choix de la place : visualisez la salle et sélectionnez le meilleur emplacement. <br>
- Alertes et nouveautés : soyez le premier informé des ouvertures de billetterie. <br><br>

Que vous soyez fan de musique, passionné de théâtre ou en quête d'une sortie entre amis, trouvez votre prochaine émotion et réservez dès maintenant.
</p>
    </div>
</body>
</html>