<?php
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Whitelist - admin_view</title>
    <link rel="stylesheet" href="../css/styles.css">
    <script src="../js/menu.js"></script>
</head>
<body>
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
    <div><h4 id="connection">
        <?php if (!isset($_SESSION['user'])):?>
            <a href="connexion.php">Se connecter</a>
        <?php else: ?>
            <?php session_destroy(); ?>
            <a href="index.php">Se déconnecter</a>
        <?php endif; ?>
    </h4></div>
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

    
    <header class="page_paiement">
        <h1>Créer un événement</h1>
        <p>Renseignez les informations de l'événement</p>
    </header>

    
    <main class="infos_event_boite">
    <form id="event-form" class="page-paiement-contenu" action="ajouter_event.php" method="post">

        
        <section class="container_paiement">

            <h2>Informations de l'événement</h2>

            
            <div class="champ">
                <label for="categorie">Type d'événement</label>
                <div class="form-group">
                    <select id="categorie" name="categorie" required>
                        <option value="">Choisir une catégorie</option>
                        <option value="concert">Concert</option>
                        <option value="spectacle">Spectacle</option>
                    </select>
                </div>
            </div>

            
            <div class="champ">
                <label for="artiste">Artiste de l'événement</label>
                <div class="form-group">
                    <input type="text" id="artiste" name="artiste" placeholder="Artiste de l'événement" required>
                </div>
            </div>

            
            <div class="champ">
                <label for="event">Nom de l'événement</label>
                <div class="form-group">
                    <input type="text" id="event" name="event" placeholder="Nom de l'événement" required>
                </div>
            </div>

            
            <div class="form-row">
                <div class="champ">
                    <label for="date">Date de l'événement</label>
                    <div class="form-group">
                        <input type="date" id="date" name="date" required>
                    </div>
                </div>

                <div class="champ">
                    <label for="time">Heure de l'événement</label>
                    <div class="form-group">
                        <input type="time" id="time" name="time" required>
                    </div>
                </div>
            </div>

           
            <div class="champ">
                <label for="lieu">Lieu de l'événement</label>
                <div class="form-group">
                    <input type="text" id="lieu" name="lieu" placeholder="Lieu de l'événement" required>
                </div>
            </div>

            
            <div class="champ">
                <label for="description">Description de l'événement</label>
                <div class="form-group">
                    <textarea id="description" name="description" placeholder="Description de l'événement" rows="5"></textarea>
                </div>
            </div>

           
            <div class="champ">
                <label for="image">Image de l'événement</label>
                <div class="form-group">
                    <input type="file" id="image" name="image" accept="image/*">
                </div>
            </div>

            
            <button type="submit" id="submit">
                Créer l'événement →
            </button>

        </section>

        
        <aside class="recapitulatif">

            <h2>Prix des places</h2>

            <div class="champ">
                <label for="carre-or">Carré OR</label>
                <div class="form-group">
                    <input type="number" id="carre-or" name="prix_carre_or" min="0" step="0.1" placeholder="0.00">
                    <span class="devise">€</span>
                </div>
            </div>

            <div class="champ">
                <label for="gradins">Gradins</label>
                <div class="form-group">
                    <input type="number" id="gradins" name="prix_gradins" min="0" step="0.1" placeholder="0.00">
                    <span class="devise">€</span>
                </div>
            </div>

            <div class="champ">
                <label for="fosse">Fosse</label>
                <div class="form-group">
                    <input type="number" id="fosse" name="prix_fosse" min="0" step="0.1" placeholder="0.00">
                    <span class="devise">€</span>
                </div>
            </div>

        </aside>

    </form>
    </main>

</body>
</html>