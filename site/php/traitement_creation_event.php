<?php
session_start();
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../config/auth.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit("Le formulaire n'a pas été envoyé.");
}


if (!estConnecte() || !estAdmin()) {
    http_response_code(403);
    exit('Accès refusé');
}


$jeton_session    = $_SESSION['csrf'] ?? '';
$jeton_formulaire = $_POST['csrf'] ?? '';
if (
    $jeton_session === ''
    || !is_string($jeton_formulaire)
    || !hash_equals($jeton_session, $jeton_formulaire)
) {
    http_response_code(403);
    exit('Requête refusée');
}


$artiste_event     = trim($_POST['artiste'] ?? '');
$nom_event         = trim($_POST['event'] ?? '');
$lieu_event        = trim($_POST['lieu'] ?? '');
$description_event = trim($_POST['description'] ?? '');
$type_event        = trim($_POST['categorie'] ?? '');
$date_event        = trim($_POST['date'] ?? '');
$heure_event       = trim($_POST['time'] ?? '');


function nombre_ou_zero(string $nom): string {
    $valeur = $_POST[$nom] ?? '';
    if (!is_string($valeur)) {
        return 'invalide';
    }
    $valeur = trim($valeur);
    return $valeur === '' ? '0' : $valeur;
}

$positif = ['options' => ['min_range' => 0]];
$prix_or      = filter_var(nombre_ou_zero('prix_carre_or'), FILTER_VALIDATE_FLOAT, $positif);
$place_or     = filter_var(nombre_ou_zero('nbr_carre_or'),  FILTER_VALIDATE_INT,   $positif);
$prix_gradin  = filter_var(nombre_ou_zero('prix_gradins'),  FILTER_VALIDATE_FLOAT, $positif);
$place_gradin = filter_var(nombre_ou_zero('nbr_gradins'),   FILTER_VALIDATE_INT,   $positif);
$prix_fosse   = filter_var(nombre_ou_zero('prix_fosse'),    FILTER_VALIDATE_FLOAT, $positif);
$place_fosse  = filter_var(nombre_ou_zero('nbr_fosse'),     FILTER_VALIDATE_INT,   $positif);

// Date au format AAAA-MM-JJ et heure au format HH:MM, réellement valides.
$d = DateTime::createFromFormat('Y-m-d', $date_event);
$h = DateTime::createFromFormat('H:i', $heure_event);

if (
    $artiste_event === '' || $nom_event === '' || $lieu_event === '' || $type_event === ''
    || !$d || $d->format('Y-m-d') !== $date_event
    || !$h || $h->format('H:i') !== $heure_event
    || in_array(false, [$prix_or, $place_or, $prix_gradin, $place_gradin, $prix_fosse, $place_fosse], true)
) {
    http_response_code(400);
    exit('Données invalides');
}


$image = null;
if (isset($_FILES['image_event']) && $_FILES['image_event']['error'] === UPLOAD_ERR_OK) {
    if ($_FILES['image_event']['size'] > 2 * 1024 * 1024) {
        exit('Image trop lourde (2 Mo maximum)');
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($_FILES['image_event']['tmp_name']);
    $types = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/gif'  => 'gif',
        'image/webp' => 'webp',
    ];
    if (!isset($types[$mime])) {
        exit('Type de fichier non supporté');
    }
    $image = ['tmp' => $_FILES['image_event']['tmp_name'], 'ext' => $types[$mime]];
}


try {
    $link1 = new PDO(
        "mysql:host=$host;dbname=$dbName;charset=utf8mb4",
        $user,
        $pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    );

    $stmt1 = $link1->prepare(
        'INSERT INTO events (artiste_event, nom_event, lieu_event, description_event, type_event, date_event, heure_event, tarif_or, place_or, tarif_gradin, place_gradin, tarif_fosse, place_fosse, total_places)
         VALUES (:artiste, :nom_event, :lieu_event, :description_event, :type_event, :date_event, :heure_event, :prix_or, :place_or, :prix_gradin, :place_gradin, :prix_fosse, :place_fosse, :total)'
    );

    $stmt1->execute([
        'artiste'           => $artiste_event,
        'nom_event'         => $nom_event,
        'lieu_event'        => $lieu_event,
        'description_event' => $description_event,
        'type_event'        => $type_event,
        'date_event'        => $date_event,
        'heure_event'       => $heure_event,
        'prix_or'           => $prix_or,
        'place_or'          => $place_or,
        'prix_gradin'       => $prix_gradin,
        'place_gradin'      => $place_gradin,
        'prix_fosse'        => $prix_fosse,
        'place_fosse'       => $place_fosse,
        'total'             => $place_or + $place_gradin + $place_fosse,
    ]);

    $id_event = $link1->lastInsertId();
    echo "Les données de l'événement ont bien été ajoutées à la base de données.";
} catch (PDOException $e) {
    error_log($e->getMessage());
    exit("Une erreur est survenue lors de l'enregistrement.");
}


if ($image === null) {
    exit("Aucune image envoyée ou une erreur est survenue");
}

$dossier_avant = __DIR__ . '/../assets/img/image_event_avant/';
$dossier_apres = __DIR__ . '/../assets/img/image_event_apres/';
$nouveau_nom   = $id_event . '.' . $image['ext'];

if (move_uploaded_file($image['tmp'], $dossier_avant . $nouveau_nom)) {
    if (copy($dossier_avant . $nouveau_nom, $dossier_apres . $nouveau_nom)) {
        echo "Événement et images enregistrés avec succès.";
    } else {
        echo "L'image originale est enregistrée, mais la copie a échoué.";
    }
} 
else {
    echo "Erreur lors de l'enregistrement de l'image originale";
}
sleep(2);
header('Location: ../pages/evenements.php');