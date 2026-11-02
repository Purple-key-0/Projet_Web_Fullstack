<?php
session_start();

require_once __DIR__ . '/../config/config.php';
require __DIR__ . '/../config/auth.php';
exigerAdmin();


if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$modif = false;
$e = [];
if (isset($_GET['id_event'])) {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $st = $pdo->prepare('SELECT * FROM events WHERE id_event = ?');
    $st->execute([(int) $_GET['id_event']]);
    $e = $st->fetch(PDO::FETCH_ASSOC);
    if (!$e) { header('Location: evenements.php'); exit; }
    $modif = true;
}
function v(string $k): string {
    global $e;
    return htmlspecialchars((string) ($e[$k] ?? ''), ENT_QUOTES, 'UTF-8');
}
?>

<?php require __DIR__ . '/entete.php'; ?>
<body>
    
    <header class="page_paiement">
        <h1><?= $modif ? "Modifier l'événement" : "Créer un événement" ?></h1>
        <p>Renseignez les informations de l'événement</p>
    </header>

    
    <main class="infos_event_boite">
    <form id="event-form" class="page-paiement-contenu" 
        action="../php/<?= $modif ? 'traitement_modification_event.php' : 'traitement_creation_event.php' ?>" 
        method="post" enctype="multipart/form-data">

        <input type="hidden" name="csrf" value="<?= htmlspecialchars($_SESSION['csrf'], ENT_QUOTES, 'UTF-8') ?>">
        <?php if ($modif): ?>
            <input type="hidden" name="id_event" value="<?= (int) $e['id_event'] ?>">
        <?php endif; ?>

        <section class="container_paiement">

            <h2>Informations de l'événement</h2>

            
            <div class="champ">
                <label for="categorie">Type d'événement</label>
                <div class="form-group">
                    <select id="categorie" name="categorie" required>
                        <option value="">Choisir une catégorie</option>
                        <option value="concert" <?= v('type_event') === 'concert' ? 'selected' : '' ?>>Concert</option>
                        <option value="spectacle" <?= v('type_event') === 'spectacle' ? 'selected' : '' ?>>Spectacle</option>
                    </select>
                </div>
            </div>

            
            <div class="champ">
                <label for="artiste">Artiste de l'événement</label>
                <div class="form-group">
                    <input type="text" id="artiste" name="artiste" value="<?= v('artiste_event') ?>" required>
                </div>
            </div>

            
            <div class="champ">
                <label for="event">Nom de l'événement</label>
                <div class="form-group">
                    <input type="text" id="event" name="event" value="<?= v('nom_event') ?>" required>
                </div>
            </div>

            
            <div class="form-row">
                <div class="champ">
                    <label for="date">Date de l'événement</label>
                    <div class="form-group">
                        <input type="date" id="date" name="date" value="<?= v('date_event') ?>" required>
                    </div>
                </div>

                <div class="champ">
                    <label for="time">Heure de l'événement</label>
                    <div class="form-group">
                        <input type="time" id="time" name="time" value="<?= htmlspecialchars(substr((string)($e['heure_event'] ?? ''), 0, 5)) ?>" required>
                    </div>
                </div>
            </div>

           
            <div class="champ">
                <label for="lieu">Lieu de l'événement</label>
                <div class="form-group">
                    <input type="text" id="lieu" name="lieu" value="<?= v('lieu_event') ?>" required>
                </div>
            </div>

            
            <div class="champ">
                <label for="description">Description de l'événement</label>
                <div class="form-group">
                    <textarea id="description" name="description" rows="5" required><?= v('description_event') ?></textarea>
                </div>
            </div>

           
            <div class="champ">
                <label for="image">Image de l'événement</label>
                <div class="form-group_image">
                    <input type="file" id="image_event" name="image_event" accept="image/jpeg,image/png,image/gif,image/webp" <?= $modif ? '' : 'required' ?>>
                </div>
            </div>

            
            <button type="submit" class="submit" name="submit">
                <?= $modif ? 'Enregistrer les modifications →' : "Créer l'événement →" ?>
            </button>

        </section>

        
        <aside class="recapitulatif">

            <h2>Prix des places</h2>

            <div class="champ">
                    <label for="carre-or">Carré OR</label>
                    <div class="form-group">
                        <label> Nombre de places: <input type="number" id="nbr_carre_or" name="nbr_carre_or" min="0" placeholder="0"></label>
                        <label> Prix unitaire: <input type="number" id="prix_carre_or" name="prix_carre_or" min="0" step="0.01" placeholder="0.00"></label>
                    </div>
                </div>

            <div class="champ">
                    <label for="fosse">Fosse</label>
                    <div class="form-group">
                        <label> Nombre de places: <input type="number" id="nbr_fosse" name="nbr_fosse" min="0" placeholder="0"></label>
                        <label> Prix unitaire: <input type="number" id="prix_fosse" name="prix_fosse" min="0" step="0.01" placeholder="0.00"></label>
                    </div>
                </div>

            <div class="champ">
                    <label for="gradins">Gradins</label>
                    <div class="form-group">
                        <label> Nombre de places: <input type="number" id="nbr_gradins" name="nbr_gradins" min="0" placeholder="0"></label>
                        <label> Prix unitaire: <input type="number" id="prix_gradins" name="prix_gradins" min="0" step="0.01" placeholder="0.00"></label>
                    </div>
                </div>

        </aside>

    </form>
    </main>

</body>
</html>