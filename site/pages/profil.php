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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whitelist :)</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/index.js"></script>
</head>
<header id="en_tete">
    <div class="menu-container">
    <img src="../assets/img/logo_menu.svg"
         id="logo_menu"
         alt="Ouvrir le menu"
         onclick="toggleMenu()">

    <div id="menu_deroulant" class="menu-cache">
        <a href="index.php">Accueil</a>
        <a href="profil.php">Mon Compte</a>
        <a href="evenements.php">Événements</a>
        <a href="reservations.php">Mes Réservations</a>
        <?php if (isset($_SESSION['user']['roles']) && $_SESSION['user']['roles'] === '["ROLE_ADMIN"]' ):?>
            <a href="admin_view.php">Gestion d'Événements</a>
        <?php endif; ?>
    </div>
</div>
    <div><h4 id="connection"><a href="connexion.php">Se connecter</a></h4></div>
</header>
<header id="titre_site"><h1>Whitelist</h1></header>
<header id="bandeau_onglets">
    <h5 id="h5_1"><a href="index.php">Accueil</a></h5>
    <h5><a href="evenements.php">Événements</a></h5>
    <h5><a href="reservations.php">Mes Réservations</a></h5>
    <h5>
        <?php if (isset($_SESSION['user']['roles']) && $_SESSION['user']['roles'] === '["ROLE_ADMIN"]' ):?>
            <a href="admin_view.php">Gestion d'Événements</a>
        <?php endif; ?>
    </h5>
</header>
<body>
<?php require __DIR__ . '/menu.php'; ?>

<main>
    <h2>Bonjour <?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></h2>

    <section>
        <h3>Mes informations</h3>
        <p><strong>Adresse e-mail :</strong> <span id="email-actuel"><?= htmlspecialchars($u['email']) ?></span></p>
        <p><strong>Mot de passe :</strong> ••••••••</p>
    </section>

    <section>
        <h3>Modifier mon adresse e-mail</h3>
        <form id="form-email" class="form-profil">
            <input type="email" name="email" placeholder="Nouvelle adresse e-mail" required>
            <input type="password" name="mdp" placeholder="Mot de passe actuel (confirmation)" required>
            <button type="submit">Modifier l'e-mail</button>
            <div class="message"></div>
        </form>
    </section>

    <section>
        <h3>Modifier mon mot de passe</h3>
        <form id="form-mdp" class="form-profil">
            <input type="password" name="ancien" placeholder="Ancien mot de passe" required>
            <input type="password" name="nouveau" placeholder="Nouveau mot de passe (8 caractères min.)" minlength="8" required>
            <input type="password" name="confirmation" placeholder="Confirmer le nouveau mot de passe" required>
            <button type="submit">Modifier le mot de passe</button>
            <div class="message"></div>
        </form>
    </section>
</main>

<script>
function brancherFormulaire(idForm, urlPhp, apresSucces) {
    const form = document.getElementById(idForm);
    const message = form.querySelector('.message');

    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        const data = new FormData(form);

        if (data.has('confirmation') && data.get('nouveau') !== data.get('confirmation')) {
            message.textContent = 'Les mots de passe ne correspondent pas.';
            message.style.color = 'crimson';
            return;
        }
        try {
            const r = await fetch(urlPhp, { method: 'POST', body: data });
            const res = await r.json();
            message.textContent = res.message;
            message.style.color = res.succes ? 'green' : 'crimson';
            if (res.succes) { if (apresSucces) apresSucces(data); form.reset(); }
        } catch (err) {
            message.textContent = 'Erreur de connexion au serveur.';
            message.style.color = 'crimson';
        }
    });
}

brancherFormulaire('form-mdp', 'changer_mdp.php');
brancherFormulaire('form-email', 'changer_email.php', (d) => {
    document.getElementById('email-actuel').textContent = d.get('email');
});
</script>
</body>
</html>