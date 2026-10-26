<?php
require_once __DIR__ . '/../config/config.php';

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
    die('Impossible de récupérer les événements.');
}

require __DIR__ . '/entete.php';
?>

<body>
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
                        href="seance.php?nom=<?= urlencode($evenement['nom_event']) ?>"
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
            <a href="seance.php?id=<?= (int) $evenement['id_event'] ?>" class="evenement-lien">
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
            </a>
        </section>
    <?php endforeach; ?>

    <script src="../js/evenements.js"></script>
</body>
</html>