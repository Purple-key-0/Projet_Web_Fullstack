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
    <form action="../php/traitement_inscription.php" method="post">
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