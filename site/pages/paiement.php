<?php
session_start();
require __DIR__ . '/../config/config.php';

// ---------------------------------------------------------------
// Il faut être connecté pour accéder au paiement.
// Avant, $_SESSION['user']['id'] était lu sans vérification :
// un visiteur non connecté provoquait une erreur.
// ---------------------------------------------------------------
if (!isset($_SESSION['user']['id'])) {
    header('Location: connexion.php');
    exit;
}
$id_user = (int) $_SESSION['user']['id'];

// ---------------------------------------------------------------
// Jeton CSRF : un texte aléatoire, créé une fois par session.
// Il est placé dans le formulaire (champ caché plus bas) et
// vérifié par inscription_event.php.
// random_bytes() génère des octets aléatoires sûrs pour la sécurité,
// bin2hex() les transforme en texte lisible.
// ---------------------------------------------------------------
if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

// Petite fonction pour écrire moins de code : elle "échappe" un texte
// avant de l'afficher (< devient &lt; etc.), ce qui empêche le XSS.
function h($valeur): string {
    return htmlspecialchars((string) $valeur, ENT_QUOTES, 'UTF-8');
}

// L'identifiant de l'événement vient de l'URL (?id_event=3).
// FILTER_VALIDATE_INT renvoie false si ce n'est pas un entier.
$id = filter_input(INPUT_GET, 'id_event', FILTER_VALIDATE_INT);

if (!$id) {
    http_response_code(400);
    exit('Identifiant invalide.');
}

try {
    // Une seule connexion (avant, il y en avait deux, dont une inutilisée
    // et sans gestion des erreurs).
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

    // L'utilisateur est-il déjà inscrit ? Cette requête est maintenant
    // dans le try : une erreur SQL est attrapée au lieu de planter la page.
    $check = $pdo->prepare(
        'SELECT 1 FROM user_event WHERE user_id = ? AND event_id = ?'
    );
    $check->execute([$id_user, $evenement['id_event']]);
    $dejaInscrit = (bool) $check->fetchColumn();
} catch (PDOException $e) {
    error_log($e->getMessage());
    http_response_code(500);
    exit('Erreur lors de la récupération de l’événement.');
}
?>
<?php require __DIR__ . '/entete.php'; ?>
<body>
    <!-- Titre de la page -->
    <header class="page_paiement">
        <h1>Paiement</h1>
        <p>Finalisez votre commande</p>
    </header>

    <!-- Conteneur des deux boîtes -->
    <main class="page-paiement-contenu">

        <!-- Informations de paiement -->
        <section class="container_paiement">

            <h2>Informations de paiement</h2>

            <?php if ($dejaInscrit): ?>
                <p>Vous êtes déjà inscrit à cet événement.</p>
            <?php endif; ?>

            <div class="cartes-bancaires">
                <img
                    src="../assets/img/icone_visa.webp"
                    alt="Visa"
                >
                <img
                    src="../assets/img/icone_mastercard.webp"
                    alt="Mastercard"
                >
            </div>

            <form action="../php/inscription_event.php" method="post">

                <?php /*
                    Les champs de carte bancaire n'ont plus d'attribut "name".
                    Un champ sans "name" n'est pas envoyé au serveur : les
                    données de carte ne quittent jamais le navigateur.
                    Le "required" et les "id" fonctionnent toujours.
                    (Le paiement est une simulation dans ce projet.)
                */ ?>

                <!-- Numéro de carte -->
                <div class="champ">
                    <label for="carte">Numéro de carte</label>

                    <div class="form-group">
                        <input
                            type="text"
                            id="carte"
                            placeholder="1234 5678 9012 3456"
                            autocomplete="cc-number"
                            inputmode="numeric"
                            required
                        >

                        <img
                            src="../assets/img/icone_cartebancaire.webp"
                            alt=""
                            class="icone-champ"
                        >
                    </div>
                </div>

                <!-- Expiration et cryptogramme -->
                <div class="form-row">

                    <div class="champ">
                        <label for="expiration">
                            Date d'expiration
                        </label>

                        <div class="form-group">
                            <input
                                type="text"
                                id="expiration"
                                placeholder="MM/AA"
                                autocomplete="cc-exp"
                                required
                            >

                            <img
                                src="../assets/img/icone_calendrier.webp"
                                alt=""
                                class="icone-champ"
                            >
                        </div>
                    </div>

                    <div class="champ">
                        <label for="cryptogramme">
                            Cryptogramme (CVV)
                        </label>

                        <div class="form-group">
                            <input
                                type="password"
                                id="cryptogramme"
                                placeholder="123"
                                autocomplete="cc-csc"
                                inputmode="numeric"
                                required
                            >

                        </div>
                    </div>

                </div>

                <!-- Nom sur la carte -->
                <div class="champ">
                    <label for="nom_carte">Nom sur la carte</label>

                    <div class="form-group">
                        <input
                            type="text"
                            id="nom_carte"
                            placeholder="Jean Dupont"
                            autocomplete="cc-name"
                            required
                        >
                    </div>
                </div>

                <!-- Conditions générales -->
                <div class="confirmation">
                    <input
                        type="checkbox"
                        id="conditions"
                        name="conditions"
                        required
                    >

                    <label for="conditions">
                        J'accepte les conditions générales de vente.
                    </label>
                </div>

                    <input type="hidden" name="id_event" value="<?= h($evenement['id_event']) ?>">
                    <!-- Jeton CSRF vérifié par inscription_event.php -->
                    <input type="hidden" name="csrf" value="<?= h($_SESSION['csrf']) ?>">
                    <button type="submit" class="submit" name="submit">
                        Passer au paiement
                    </button>

            </form>

        </section>

        <!-- Récapitulatif de la commande -->
        <aside class="recapitulatif">

            <h2>Récapitulatif de la commande</h2>

            <div class="recap">

                <!-- Nom, date et lieu viennent maintenant de la base de données
                     (avant : texte écrit à la main dans la page) -->
                <h3><?= h($evenement['nom_event']) ?></h3>

                <p>
                    <strong>Date :</strong>
                    <?= h($evenement['date_event']) ?>
                </p>

                <p>
                    <strong>Lieu :</strong>
                    <?= h($evenement['lieu_event']) ?>
                </p>

                <!-- À FAIRE : nombre de places et prix encore fixes.
                     Pour les calculer, il faut savoir quelle catégorie
                     (carré or, gradins, fosse) et combien de places l'utilisateur
                     a choisi, puis lire les tarifs dans la table events. -->
                <p>
                    <strong>Nombre de places :</strong>
                    2
                </p>

                <hr>

                <div class="ligne-prix">
                    <span>Sous-total</span>
                    <span>150,00 €</span>
                </div>

                <div class="ligne-prix">
                    <span>Frais de service</span>
                    <span>5,00 €</span>
                </div>

                <div class="prix-total">
                    <strong>Montant total</strong>
                    <strong>155,00 €</strong>
                </div>

            </div>

            <div class="paiement-securise">
                <p>🔒 Paiement sécurisé</p>

                <small>
                    Vos informations doivent être traitées
                    de manière sécurisée.
                </small>
            </div>

        </aside>

    </main>

</body>
</html>