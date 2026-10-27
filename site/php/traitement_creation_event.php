
<?php

// Vérifier que le formulaire a bien été envoyé
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Le formulaire n'a pas été envoyé.");
}

// Récupérer les données du formulaire
$artiste_event = $_POST['artiste'] ?? '';
$nom_event = $_POST['event'] ?? '';
$lieu_event = $_POST['lieu'] ?? '';
$description_event = $_POST['description'] ?? '';
$type_event = $_POST['categorie'] ?? '';
$date_event = $_POST['date'] ?? '';
$heure_event = $_POST['time'] ?? '';
$prix_or = $_POST['prix_carre_or'] ?? '';
$place_or = $_POST['nbr_carre_or'] ?? '';
$prix_gradin = $_POST['prix_gradins'] ?? '';
$place_gradin = $_POST['nbr_gradins'] ?? '';
$prix_fosse = $_POST['prix_fosse'] ?? '';
$place_fosse = $_POST['nbr_fosse'] ?? '';
$image_event = $_POST['url_image'] ?? '';


// Connexion à la base de données
$host = "sql7.freesqldatabase.com";
$dbName = "sql7839244";
$user = "sql7839244";
$pass = "k4ySRIVWAK";

try {
    $link1 = new PDO(
        "mysql:host=$host;dbname=$dbName;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // Préparer la requête SQL
    $insertQuery = "INSERT INTO events (artiste_event, nom_event, lieu_event, description_event, type_event, date_event, heure_event, tarif_or, place_or, tarif_gradin, place_gradin, tarif_fosse, place_fosse, image)
                    VALUES (:artiste, :nom_event, :lieu_event, :description_event, :type_event, :date_event, :heure_event, :prix_or, :place_or, :prix_gradin, :place_gradin, :prix_fosse, :place_fosse, :image_event)";

    $stmt1 = $link1->prepare($insertQuery);

    // Exécuter la requête avec la valeur du formulaire
    $stmt1->execute([
        'artiste' => $artiste_event,
        'nom_event' => $nom_event,
        'lieu_event' => $lieu_event,
        'description_event' => $description_event,
        'type_event' => $type_event,
        'date_event' => $date_event,
        'heure_event' => $heure_event,
        'prix_or' => $prix_or,
        'place_or' => $place_or,
        'prix_gradin' => $prix_gradin,
        'place_gradin' => $place_gradin,
        'prix_fosse' => $prix_fosse,
        'place_fosse' => $place_fosse,
        'image_event' => $image_event,
    ]);

    echo "Les données de l'événement ont bien été ajoutées à la base de données.";

} catch (PDOException $e) {
    error_log($e->getMessage());
    exit("Une erreur est survenue lors de l'enregistrement.");
}
