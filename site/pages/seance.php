<?php
session_start();

require __DIR__ . '/../config/config.php';

// Connexion à la base de données MySQL
$dbh = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);
$id_user = $_SESSION['user']['id'];

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

$check = $pdo->prepare(
    'SELECT 1 FROM user_event WHERE user_id = ? AND event_id = ?'
);
$check->execute([$id_user, $evenement['id_event']]);

$dejaInscrit = (bool) $check->fetchColumn();

?>



<?php require __DIR__ . '/entete.php'; ?>

<body>
    <section class="container">
        <h2 class="font-container"><?= htmlspecialchars($evenement['nom_event'], ENT_QUOTES, 'UTF-8') ?></h2>

                <p class="font-container">
                    <?= htmlspecialchars($evenement['date_event'], ENT_QUOTES, 'UTF-8') ?>
                    à <?= htmlspecialchars($evenement['heure_event'], ENT_QUOTES, 'UTF-8') ?>
                </p>

                <p class="font-container">
                    Lieu : <?= htmlspecialchars($evenement['lieu_event'], ENT_QUOTES, 'UTF-8') ?>
                </p>

                <p class="font-container">
                    Artiste : <?= htmlspecialchars($evenement['artiste_event'], ENT_QUOTES, 'UTF-8') ?>
                </p>

                <p class="font-container">
                    <?= htmlspecialchars($evenement['description_event'], ENT_QUOTES, 'UTF-8') ?>
                </p>
                <div class="image_evenement_">
                    <img src="../image_event/<?= htmlspecialchars($evenement['id_event'], ENT_QUOTES, 'UTF-8') ?>.jpg" alt="Image de l'événement">
                </div>
                <?php if (!$dejaInscrit): ?>
                    <a href="paiement.php?id_event=<?= (int) $evenement['id_event'] ?>" class="submit" id="reserver-button">
                        Réserver ma place !
                    </a>
                <?php else: ?>
                    <form action="../php/desinscription_event.php" method="post">
                        <input type="hidden" name="id_event" value="<?= htmlspecialchars($evenement['id_event'], ENT_QUOTES, 'UTF-8') ?>">
                        <button type="submit" class="submit" name="submit">
                            Annuler ma réservation
                        </button>
                    </form>
                <?php endif; ?>

    </section>
</body>
</html>