<?php
session_start();
require_once __DIR__ . '/../config/config.php';

$host = "sql7.freesqldatabase.com";
$dbName = "sql7839244";
$user = "sql7839244";
$pass = "k4ySRIVWAK";

// Connexion à la base de données MySQL
$link1 = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);

?>

<?php require __DIR__ . '/entete.php'; ?>
    
    <header class="page_paiement">
        <h1>Créer un événement</h1>
        <p>Renseignez les informations de l'événement</p>
    </header>

    
    <main class="infos_event_boite">
    <form id="event-form" class="page-paiement-contenu" action="../php/traitement_creation_event.php" method="post" enctype="multipart/form-data">

        
        <section class="container_paiement">

            <h2>Informations de l'événement</h2>

            
            <div class="champ">
                <label for="categorie">Type d'événement</label>
                <div class="form-group">
                    <select id="categorie" name="categorie" required>
                        <option value="">Choisir une catégorie</option>
                        <option value="concert">Concert</option>
                        <option value="spectacle">Spectacle</option>
                    </select>
                </div>
            </div>

            
            <div class="champ">
                <label for="artiste">Artiste de l'événement</label>
                <div class="form-group">
                    <input type="text" id="artiste" name="artiste" placeholder="Artiste de l'événement" required >
                </div>
            </div>

            
            <div class="champ">
                <label for="event">Nom de l'événement</label>
                <div class="form-group">
                    <input type="text" id="event" name="event" placeholder="Nom de l'événement" required>
                </div>
            </div>

            
            <div class="form-row">
                <div class="champ">
                    <label for="date">Date de l'événement</label>
                    <div class="form-group">
                        <input type="date" id="date" name="date" required >
                    </div>
                </div>

                <div class="champ">
                    <label for="time">Heure de l'événement</label>
                    <div class="form-group">
                        <input type="time" id="time" name="time" required >
                    </div>
                </div>
            </div>

           
            <div class="champ">
                <label for="lieu">Lieu de l'événement</label>
                <div class="form-group">
                    <input type="text" id="lieu" name="lieu" placeholder="Lieu de l'événement" required>
                </div>
            </div>

            
            <div class="champ">
                <label for="description">Description de l'événement</label>
                <div class="form-group">
                    <textarea id="description" name="description" placeholder="Description de l'événement" rows="5" required></textarea>
                </div>
            </div>

           
            <div class="champ">
                <label for="image">Image de l'événement</label>
                <div class="form-group_image">
                    <input type="file" id="image_event" name="image_event" required>
                </div>
            </div>

            
            <button type="submit" class="submit" name="submit">
                Créer l'événement →
            </button>

        </section>

        
        <aside class="recapitulatif">

            <h2>Prix des places</h2>

            <div class="champ">
                    <label for="carre-or">Carré OR</label>
                    <div class="form-group">
                        <label> Nombre de places: <input type="number" id="nbr_carre_or" name="nbr_carre_or" min="0" placeholder="0"></label>
                        <label> Prix unitaire: <input type="number" id="prix_carre_or" name="prix_carre_or" min="0" step="0.01" placeholder="0.00"></label>
                    </div>
                </div>

            <div class="champ">
                    <label for="fosse">Fosse</label>
                    <div class="form-group">
                        <label> Nombre de places: <input type="number" id="nbr_fosse" name="nbr_fosse" min="0" placeholder="0"></label>
                        <label> Prix unitaire: <input type="number" id="prix_fosse" name="prix_fosse" min="0" step="0.01" placeholder="0.00"></label>
                    </div>
                </div>

            <div class="champ">
                    <label for="gradins">Gradins</label>
                    <div class="form-group">
                        <label> Nombre de places: <input type="number" id="nbr_gradins" name="nbr_gradins" min="0" placeholder="0"></label>
                        <label> Prix unitaire: <input type="number" id="prix_gradins" name="prix_gradins" min="0" step="0.01" placeholder="0.00"></label>
                    </div>
                </div>

        </aside>

    </form>
    </main>

</body>
</html>