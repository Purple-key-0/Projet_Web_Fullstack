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
    <title>Whitelist - admin_view</title>
    <link rel="stylesheet" href="../css/styles.css">
</head>
<body>
    <header id="en_tete">
        <img src="../assets/img/logo_menu.svg" id="logo_menu">
        <div><h4 id="connection"><a href="connexion.php">Se connecter</a></h4></div>
    </header>
    <header id="titre_site"><h1>Whitelist</h1></header>
    <header id="bandeau_onglets">
        <h5 id="h5_1"><a href="index.php">Accueil</a></h5>
        <h5><a href="evenements.php">Événements</a></h5>
        <h5><a href="reservations.php">Mes Réservations</a></h5>
        <h5><a href="admin_view.php">Gestion d'Événements</a></h5>
    </header>

    <main class="container">
        <h1>Créer un événement</h1>

        <form id="event-form">
            <label for="artiste">Type d'événement</label>
            <div class="form-group">
                <select id="categorie" name="categorie" required>
                    <option value="">Choisir une catégorie</option>
                    <option value="concert">Concert</option>
                    <option value="spectacle">Spectacle</option>
                </select>
            </div>

            <label for="artiste">Artiste de l'événement</label>
            <div class="form-group">
                
                <input type="text" id="artiste" name="artiste" placeholder="Artiste de l'événement" required>
            </div>

            <label for="event">Nom de l'événement</label>
            <div class="form-group">
                
                <input type="text" id="event" name="event" placeholder="Nom de l'événement" required>
            </div>

            <div class="form-row">
                <label for="date">Date de l'événement</label>
                <div class="form-group">
                    
                    <input type="date" id="date" name="date" required>
                </div>

                <label for="time">Heure de l'événement</label>
                <div class="form-group">
                    
                    <input type="time" id="time" name="time" required>
                </div>
            </div>

            <label for="lieu">Lieu de l'événement</label>
            <div class="form-group">
                
                <input type="text" id="lieu" name="lieu" placeholder="Lieu de l'événement" required>
            </div>

            <label for="description">Description de l'événement</label>
            <div class="form-group">
                
                <textarea id="description" name="description" placeholder="Description de l'événement" rows="5"></textarea>
            </div>

            <fieldset class="prices">
                <legend>Prix des places (€)</legend>

                <label for="carre-or">Carré OR</label>
                <div class="form-group">
                    
                    <input type="number" id="carre-or" name="prix_carre_or" min="0" step="0.1">
                </div>

                <label for="gradins">Gradins</label>
                <div class="form-group">
                    
                    <input type="number" id="gradins" name="prix_gradins" min="0" step="0.1">
                </div>

                <label for="fosse">Fosse</label>
                <div class="form-group">
                    
                    <input type="number" id="fosse" name="prix_fosse" min="0" step="0.1">
                </div>
            </fieldset>

            <label for="image">Image de l'événement</label>
            <div class="form-group">
                
                <input type="file" id="image" name="image" accept="image/*">
            </div>

            <button type="submit" id="submit">
                Créer l'événement
            </button>

        </form>
    </main>

</body>
</html>