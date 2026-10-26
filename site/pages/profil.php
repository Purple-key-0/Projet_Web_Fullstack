<?php
session_start();
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

require __DIR__ . '/../config/auth.php';
require __DIR__ . '/../config/db.php';
exigerConnexion();

$stmt = $dbh->prepare('SELECT first_name, last_name, email, birth_date FROM user WHERE id = ?');
$stmt->execute([$_SESSION['user']['id']]);
$u = $stmt->fetch();

$titrePage = 'Mon profil';

?>
<?php require __DIR__ . '/entete.php'; ?>
<body>

<main>
    <h2>Bonjour <?= htmlspecialchars($u['first_name'] . ' ' . $u['last_name']) ?></h2>

    <h3>Mes informations</h3>

    <div class="ligne-info">
        <p><strong>Adresse e-mail :</strong> <span id="email-actuel"><?= htmlspecialchars($u['email']) ?></span></p>
        <button type="button" onclick="afficherFormulaire('form-email')">Modifier l'e-mail</button>
    </div>
    <form id="form-email" class="form-profil form-cache">
        <input type="email" name="email" placeholder="Nouvelle adresse e-mail" required>
        <input type="password" name="mdp" placeholder="Mot de passe actuel (confirmation)" required>
        <button type="submit">Valider</button>
        <div class="message"></div>
    </form>

    <div class="ligne-info">
        <p><strong>Mot de passe :</strong> ••••••••</p>
        <button type="button" onclick="afficherFormulaire('form-mdp')">Modifier le mot de passe</button>
    </div>
    <form id="form-mdp" class="form-profil form-cache">
        <input type="password" name="ancien" placeholder="Ancien mot de passe" required>
        <input type="password" name="nouveau" placeholder="Nouveau mot de passe (8 caractères min.)" minlength="8" required>
        <input type="password" name="confirmation" placeholder="Confirmer le nouveau mot de passe" required>
        <button type="submit">Valider</button>
        <div class="message"></div>
    </form>
</main>

<script>
    function afficherFormulaire(id) {
    document.getElementById(id).classList.toggle('form-cache');
}
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