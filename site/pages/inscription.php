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
    <section class="container">
        <form action="../php/traitement_inscription.php" method="post">
            <div>
                <label class="font-container" for="email">Votre e-mail</label>
                <input class="form-group"type="email" name="email" id="email" required>
            </div>
            <div>
                <label class="font-container" for="password">Votre mot de passe</label>
                <input class="form-group" type="password" name="password" id="password" required>
            </div>
            <div>
                <label class="font-container" for="last-name">Votre nom de famille</label>
                <input class="form-group" type="text" name="last_name" id="last-name" required>
            </div>
            <div>
                <label class="font-container" for="first-name">Votre prénom</label>
                <input class="form-group" type="text" name="first_name" id="first-name" required>
            </div>
            <div>
                <label class="font-container" for="birth-date">Votre date de naissance</label>
                <input class="form-group" type="date" name="birth_date" id="birth-date" required>
            </div>
            <input class="submit" type="submit" value="S'inscrire">
        </form>
    </section>
</body>

</html>