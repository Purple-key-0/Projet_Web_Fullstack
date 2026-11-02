<?php
session_start();
require_once __DIR__ . '/../config/config.php';
require __DIR__ . '/../config/auth.php';
exigerAdmin();

if (empty($_SESSION['csrf'])) {
    $_SESSION['csrf'] = bin2hex(random_bytes(32));
}

$message = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbName;charset=utf8mb4", $user, $pass,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
    $utilisateurs = $pdo
        ->query('SELECT id_user, first_name, last_name, email, role, derniere_connexion FROM user ORDER BY last_name, first_name')
        ->fetchAll();
} catch (PDOException $e) {
    error_log($e->getMessage());
    die('Impossible de récupérer les utilisateurs.');
}

function h($s): string { return htmlspecialchars((string) $s, ENT_QUOTES, 'UTF-8'); }

$moi = (int) ($_SESSION['user_id'] ?? 0);
?>
<?php require __DIR__ . '/entete.php'; ?>
<body>
    <header class="titre-event">
        <h1 class="font-container">Gestion des Utilisateurs</h1>
    </header>

    <main class="gestion-roles">
        <?php if ($message): ?>
            <p class="message-flash"><?= h($message) ?></p>
        <?php endif; ?>

        <input type="search" class="barre-de-recherche" placeholder="Rechercher un utilisateur (nom, prénom, email)" autocomplete="off">
        <section class="evenement" id="liste-utilisateurs">
        <table class="table-roles">
            <thead>
                <tr>
                    <th>Utilisateur</th>
                    <th>Email</th>
                    <th>Dernière connexion</th>
                    <th>Rôle</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($utilisateurs as $u):
                $roles   = json_decode((string) $u['role'], true) ?: [];
                $estAdmin = in_array('ROLE_ADMIN', $roles, true);
                $recherche = mb_strtolower($u['last_name'] . ' ' . $u['first_name'] . ' ' . $u['email']);
            ?>
                <tr data-recherche="<?= h($recherche) ?>">
                    <td><?= h($u['first_name'] . ' ' . $u['last_name']) ?></td>
                    <td><?= h($u['email']) ?></td>
                    <td>
                        <?= $u['derniere_connexion']
                            ? h(date('d/m/Y H:i', strtotime($u['derniere_connexion'])))
                            : 'Jamais' ?>
                    </td>
                    <td>
                        <?php if ((int) $u['id_user'] === $moi): ?>
                            <?= $estAdmin ? 'Admin' : 'Utilisateur' ?> (vous)
                        <?php else: ?>
                            <form action="../php/traitement_role.php" method="post" style="display:inline">
                                <input type="hidden" name="csrf" value="<?= h($_SESSION['csrf']) ?>">
                                <input type="hidden" name="id_user" value="<?= (int) $u['id_user'] ?>">
                                <select name="role" id="select-role">
                                    <option value="ROLE_USER" <?= !$estAdmin ? 'selected' : '' ?>>Utilisateur</option>
                                    <option value="ROLE_ADMIN" <?= $estAdmin ? 'selected' : '' ?>>Admin</option>
                                </select>
                                <button type="submit">Enregistrer</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <p id="aucun-resultat" hidden>Aucun utilisateur trouvé.</p>
        </section>
    </main>

    <script>
        const champ = document.getElementById('recherche-user');
        const lignes = document.querySelectorAll('.table-roles tbody tr');
        const vide = document.getElementById('aucun-resultat');
        champ.addEventListener('input', () => {
            const terme = champ.value.trim().toLowerCase();
            let visibles = 0;
            lignes.forEach(l => {
                const ok = l.dataset.recherche.includes(terme);
                l.hidden = !ok;
                if (ok) visibles++;
            });
            vide.hidden = visibles > 0;
        });
    </script>
</body>
</html>