<?php

// Vérifier que le formulaire a bien été envoyé
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Le formulaire n'a pas été envoyé.");
}

// Récupérer les données du formulaire
$carréor = $_POST['prix_carre_or'] ?? '';
$gradin = $_POST['nbr_carre_or'] ?? '';
$fosse = $_POST['prix_gradins'] ?? '';
$description_event = $_POST['nbr_gradins'] ?? '';
$type_event = $_POST['prix_fosse'] ?? '';
$date_event = $_POST['nbr_fosse'] ?? '';



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
    $insertQuery = "INSERT INTO events (categorie,prix)
                    VALUES (:artiste, :nom_event, :lieu_event, :description_event, :type_event, :date_event, :heure_event)";

    $stmt = $dbh->prepare($insertQuery);

    // Exécuter la requête avec la valeur du formulaire
    $stmt->execute([
        'artiste' => $artiste_event,
        
    ]);

    echo "Les données de l'événement ont bien été ajoutées à la base de données.";

} catch (PDOException $e) {
    error_log($e->getMessage());
    exit("Une erreur est survenue lors de l'enregistrement.");
}
