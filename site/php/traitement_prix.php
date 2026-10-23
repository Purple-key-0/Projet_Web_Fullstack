
<?php


if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Le formulaire n'a pas été envoyé.");
}


$prix_carre_or = $_POST['prix_carre_or'] ?? '';
$prix_gradins = $_POST['prix_gradins'] ?? '';
$prix_fosse = $_POST['prix_fosse'] ?? '';


if (trim($artiste) === '') {
    exit("Le nom de l'artiste est obligatoire.");
}

require __DIR__ . '/../config/config.php';

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

