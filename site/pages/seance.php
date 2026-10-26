<?php
session_start();

require __DIR__ . '/../config/config.php';

// Connexion à la base de données MySQL
$dbh = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);


$id = filter_input(INPUT_GET, 'id_event', FILTER_VALIDATE_INT);

if (!$id) {
    http_response_code(400);
    exit('Identifiant invalide.');
}

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbName;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $requete = $pdo->prepare('SELECT * FROM events WHERE id_event = :id');
    $requete->execute(['id' => $id]);
    $evenement = $requete->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    http_response_code(500);
    exit('Erreur lors de la récupération de l’événement.');
}

if (!$evenement) {
    http_response_code(404);
    exit('Événement introuvable.');
}
?>

<?php require __DIR__ . '/entete.php'; ?>

<body>
    <section>
        <h2><?= htmlspecialchars($evenement['nom_event'], ENT_QUOTES, 'UTF-8') ?></h2>

                <p>
                    <?= htmlspecialchars($evenement['date_event'], ENT_QUOTES, 'UTF-8') ?>
                    à <?= htmlspecialchars($evenement['heure_event'], ENT_QUOTES, 'UTF-8') ?>
                </p>

                <p>
                    Lieu : <?= htmlspecialchars($evenement['lieu_event'], ENT_QUOTES, 'UTF-8') ?>
                </p>

                <p>
                    Artiste : <?= htmlspecialchars($evenement['artiste_event'], ENT_QUOTES, 'UTF-8') ?>
                </p>

                <p>
                    <?= htmlspecialchars($evenement['description_event'], ENT_QUOTES, 'UTF-8') ?>
                </p>
    </section>
</body>
</html>