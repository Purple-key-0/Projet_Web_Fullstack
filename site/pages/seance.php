<?php
session_start();

require __DIR__ . '/../config/config.php';

function h($valeur): string {
    return htmlspecialchars((string) $valeur, ENT_QUOTES, 'UTF-8');
}


function imageEvenement(int $id): ?string {
    $dossier  = __DIR__ . '/../assets/img/image_event_apres/';
    $fichiers = glob($dossier . $id . '.*');
    return $fichiers ? basename($fichiers[0]) : null;
}

// Jeton CSRF pour le bouton "Annuler ma réservation" (vérifié par desinscription_event.php).
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$id_user = $_SESSION['user']['id'] ?? null;

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

    if (!$evenement) {
        http_response_code(404);
        exit('Événement introuvable.');
    }

    
    $dejaInscrit = false;
    if ($id_user) {
        $check = $pdo->prepare(
            'SELECT 1 FROM user_event WHERE user_id = ? AND event_id = ?'
        );
        $check->execute([$id_user, $evenement['id_event']]);
        $dejaInscrit = (bool) $check->fetchColumn();
    }
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit('Erreur lors de la récupération de l’événement.');
}

$image = imageEvenement((int) $evenement['id_event']);

?>



<?php require __DIR__ . '/entete.php'; ?>

<body>
    <section class="container container-evenement">
        <h2 class="font-container"><?= h($evenement['nom_event']) ?></h2>
                <?php if ($image): ?>
                    <div class="image_evenement_">
                        <img src="../assets/img/image_event_apres/<?= h($image) ?>" alt="Image de l'événement">
                    </div>
                <?php endif; ?>
                <p class="p_event">
                    
                    <?= h($evenement['date_event']) ?>
                    
                    à <?= h($evenement['heure_event']) ?>  

                    Lieu : <?= h($evenement['lieu_event']) ?>
                <br>
                    Artiste : <?= h($evenement['artiste_event']) ?>
                <br>
                    <?= h($evenement['description_event']) ?>               
                <br>
                   Total des places : <?= h($evenement['total_places']) ?>
                </p>
                
                <?php if (!$dejaInscrit): ?>
                    <a href="paiement.php?id_event=<?= (int) $evenement['id_event'] ?>" class="submit" id="reserver-button">
                        Réserver ma place !
                    </a>
                <?php else: ?>
                    <form action="../php/desinscription_event.php" method="post">
                        <input type="hidden" name="id_event" value="<?= h($evenement['id_event']) ?>">
                        <input type="hidden" name="csrf" value="<?= h($_SESSION['csrf']) ?>">
                        <button type="submit" class="submit" name="submit">
                            Annuler ma réservation
                        </button>
                    </form>
                <?php endif; ?>

    </section>
</body>
</html>