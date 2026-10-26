<?php
session_start();
require __DIR__ . '/../config/config.php';

// Connexion à la base de données MySQL
$dbh = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);

// Définition de la requête MySQL
$query  = "SELECT artiste_event, nom_event, lieu_event, description_event, type_event, date_event, heure_event FROM events";

$stt = $dbh->query($query);
$result = $stt->fetchAll();

$htmlTable = "";
foreach ($result as $value) {
    $htmlTable .= "<tr>";
    $htmlTable .= "<td>{$value['artiste_event']}</td>";
    $htmlTable .= "<td>{$value['nom_event']}</td>";
    $htmlTable .= "<td>{$value['lieu_event']}</td>";
    $htmlTable .= "<td>{$value['description_event']}</td>";
    $htmlTable .= "<td>{$value['type_event']}</td>";
    $htmlTable .= "<td>{$value['date_event']}</td>";
    $htmlTable .= "<td>{$value['heure_event']}</td>";
}

?>
<?php require __DIR__ . '/entete.php'; ?>

    <main class="infos_event_boite">
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