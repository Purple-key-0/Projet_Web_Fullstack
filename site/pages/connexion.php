<?php
session_start();

?>
<?php require __DIR__ . '/entete.php'; ?>

<body>
    <section class="container">
        <?php if (isset($_GET['erreur'])): ?>
            <p class="font-container" style="color: crimson;">E-mail ou mot de passe incorrect.</p>
        <?php endif; ?>

        <form action="../php/traitement_connexion.php" method="post">
            <div>
                <label class="font-container" for="email">Votre e-mail</label>
                <input class="form-group" type="email" name="email" id="email" required>
            </div>
            <div>
                <label class="font-container" for="password">Votre mot de passe</label>
                <input class="form-group" type="password" name="password" id="password" required>
            </div>
            <input class="submit" type="submit" value="Se connecter">
            <div class="register-container">
            <p class="font-container">Pas encore de compte ?</p>
            <a class="inscription" href="inscription.php">S'inscrire</a>
            </div>
        </form>
    </section>
</body>
<?php if (isset($_GET['erreur']) && $_GET['erreur'] == 1): ?>

<script>
    alert("Identifiant ou mot de passe incorrect !");
</script>

<?php endif; ?>
</html>