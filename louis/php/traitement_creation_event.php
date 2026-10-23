
<?php


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Le formulaire n'a pas été envoyé.");
}


$artiste = $_POST['artiste'] ?? '';
$nom_event = $_POST['event'] ?? '';
$lieu_event = $_POST['lieu'] ?? '';
$description_event = $_POST['description'] ?? '';
$type_event = $_POST['categorie'] ?? '';
$date_event = $_POST['date'] ?? '';
$heure_event = $_POST['time'] ?? '';



if (trim($artiste) === '') {
    exit("Le nom de l'artiste est obligatoire.");
}

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

    $insertQuery = "INSERT INTO events (artiste_event, nom_event, lieu_event, description_event, type_event, date_event, heure_event)
                    VALUES (:artiste, :nom_event, :lieu_event, :description_event, :type_event, :date_event, :heure_event)";

    $stmt = $dbh->prepare($insertQuery);


    $stmt->execute([
        'artiste' => $artiste,
        'nom_event' => $nom_event,
        'lieu_event' => $lieu_event,
        'description_event' => $description_event,
        'type_event' => $type_event,
        'date_event' => $date_event,
        'heure_event' => $heure_event
    ]);

    echo "L'événement a bien été ajouté à la base de données.";

} catch (PDOException $e) {
    error_log($e->getMessage());
    exit("Une erreur est survenue lors de l'enregistrement.");
}

