
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


// Connexion à la base de données
$host = "sql7.freesqldatabase.com";
$dbName = "sql7839244";
$user = "sql7839244";
$pass = "k4ySRIVWAK";

try {
    $dbh = new PDO(
        "mysql:host=$host;dbname=$dbName;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    // Préparer la requête SQL
    $insertQuery = "INSERT INTO events (artiste_event, nom_event, lieu_event, description_event, type_event, date_event, heure_event)
                    VALUES (:artiste, :nom_event, :lieu_event, :description_event, :type_event, :date_event, :heure_event)";

    $stmt = $dbh->prepare($insertQuery);

    // Exécuter la requête avec la valeur du formulaire
    $stmt->execute([
        'artiste' => $artiste_event,
        'nom_event' => $nom_event,
        'lieu_event' => $lieu_event,
        'description_event' => $description_event,
        'type_event' => $type_event,
        'date_event' => $date_event,
        'heure_event' => $heure_event
    ]);

    echo "Les données de l'événement ont bien été ajoutées à la base de données.";

} catch (PDOException $e) {
    error_log($e->getMessage());
    exit("Une erreur est survenue lors de l'enregistrement.");
}
