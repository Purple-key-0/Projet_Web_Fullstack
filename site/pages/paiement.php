<?php

require __DIR__ . '/../config/config.php';

// Connexion à la base de données MySQL
$dbh = new PDO("mysql:host=$host;dbname=$dbName", $user, $pass);

// Définition de la requête MySQL
$query  = "SELECT last_name, first_name FROM user";

// Envoi de la requête à la base de données et récupération des résultats de la requête
$stt = $dbh->query($query);
$result = $stt->fetchAll();

$htmlTable = "";
foreach ($result as $value) {
    $htmlTable .= "<tr>";
    $htmlTable .= "<td>{$value['last_name']}</td>";
    $htmlTable .= "<td>{$value['first_name']}</td>";
    $htmlTable .= "</tr>";
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

            <form action="" method="post">

                <!-- Numéro de carte -->
                <div class="champ">
                    <label for="carte">Numéro de carte</label>

                    <div class="form-group">
                        <input
                            type="text"
                            id="carte"
                            name="carte"
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
                                name="expiration"
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
                                name="cryptogramme"
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
                            name="nom_carte"
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

                <!-- Bouton de paiement -->
                <button type="submit" id="submit">
                    Payer maintenant →
                </button>

            </form>

        </section>

        <!-- Récapitulatif de la commande -->
        <aside class="recapitulatif">

            <h2>Récapitulatif de la commande</h2>

            <div class="recap">

                <h3>Concert de Rock</h3>

                <p>
                    <strong>Date :</strong>
                    15/07/2026
                </p>

                <p>
                    <strong>Lieu :</strong>
                    Stade de France
                </p>

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
