<?php
session_start();
require_once __DIR__ . '/../config/config.php';

function imageEvenement(int $id): ?string {
    $dossier  = __DIR__ . '/../assets/img/image_event_apres/';
    $fichiers = glob($dossier . $id . '.*');
    return $fichiers ? basename($fichiers[0]) : null;
}

try {
    $pdo = new PDO(
        "mysql:host=$host;dbname=$dbName;charset=utf8mb4",
        $user,
        $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );

    $evenements = $pdo
        ->query('SELECT * FROM events ORDER BY date_event')
        ->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    error_log($e->getMessage());
    die('Impossible de récupérer les événements.');
}

require __DIR__ . '/entete.php';


?>

<body>
    <header class="titre-event">
        <h1 class="font-container">Nos Événements</h1>
    </header>
    <div class="rechercher">
        <input
            type="search"
            id="barre-de-recherche"
            placeholder="Rechercher un événement"
            autocomplete="off"
        >

        <ul id="liste-evenements">
            <?php foreach ($evenements as $evenement): ?>
                <li>
                    <a
                        href="seance.php?id_event=<?= (int) $evenement['id_event'] ?>"
                        data-mots-cles="<?= htmlspecialchars(
                            ($evenement['artiste_event'] ?? '') . ' ' .
                            ($evenement['type_event'] ?? ''),
                            ENT_QUOTES,
                            'UTF-8'
                        ) ?>"
                    >
                        <?= htmlspecialchars($evenement['nom_event'], ENT_QUOTES, 'UTF-8') ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <?php foreach ($evenements as $evenement): ?>
        <section class="evenement" >
            <a href="seance.php?id_event=<?= (int) $evenement['id_event'] ?>" class="evenement-lien">
                <?php $image = imageEvenement((int) $evenement['id_event']); ?>
                <?php if ($image): ?>
                    <div class="image_evenement_">
                        <img src="../assets/img/image_event_apres/<?= htmlspecialchars($image, ENT_QUOTES, 'UTF-8') ?>" alt="Image de l'événement">
                    </div>
                <?php endif; ?>
                <h2 class="evenement-titre"><?= htmlspecialchars($evenement['nom_event'], ENT_QUOTES, 'UTF-8') ?></h2>

                <p class="p_event">
                    Artiste : <?= htmlspecialchars($evenement['artiste_event'], ENT_QUOTES, 'UTF-8') ?>

                <br>
                
                    <?= htmlspecialchars($evenement['date_event'], ENT_QUOTES, 'UTF-8') ?>
                    à <?= htmlspecialchars($evenement['heure_event'], ENT_QUOTES, 'UTF-8') ?>
                
                </p>
                
            </a>
        </section>
    <?php endforeach; ?>
    <script src="../js/evenements.js"></script>
</body>
</html>