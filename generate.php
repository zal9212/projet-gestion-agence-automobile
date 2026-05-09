<?php
$files = [
    'db.php' => <<<'PHP'
<?php
$host = '127.0.0.1';
$db = 'car_rental_db';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
PHP,

    'register.php' => <<<'PHP'
<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head><title>Inscription</title></head>
<body>
    <h1>Inscription</h1>
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color:red;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <form action="actions/register.php" method="POST">
        <label>Nom: <input type="text" name="nom" required></label><br><br>
        <label>Prénom: <input type="text" name="prenom" required></label><br><br>
        <label>Email: <input type="email" name="email" required></label><br><br>
        <label>Téléphone: <input type="text" name="telephone"></label><br><br>
        <label>Mot de passe: <input type="password" name="password" required></label><br><br>
        <label>Adresse: <textarea name="adresse"></textarea></label><br><br>
        <button type="submit">S'inscrire</button>
    </form>
    <a href="login.php">Déjà un compte ? Connectez-vous</a>
</body>
</html>
PHP,

    'actions/register.php' => <<<'PHP'
<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $password = $_POST['password'];
    $adresse = trim($_POST['adresse']);

    if (empty($nom) || empty($prenom) || empty($email) || empty($password)) {
        $_SESSION['error'] = "Veuillez remplir les champs obligatoires.";
        header('Location: ../register.php');
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    try {
        $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, telephone, password, adresse) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $email, $telephone, $hash, $adresse]);
        header('Location: ../login.php');
        exit;
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            $_SESSION['error'] = "Cet email est déjà utilisé.";
        } else {
            $_SESSION['error'] = "Erreur d'inscription.";
        }
        header('Location: ../register.php');
        exit;
    }
}
PHP,

    'login.php' => <<<'PHP'
<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head><title>Connexion</title></head>
<body>
    <h1>Connexion</h1>
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color:red;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <form action="actions/login.php" method="POST">
        <label>Email: <input type="email" name="email" required></label><br><br>
        <label>Mot de passe: <input type="password" name="password" required></label><br><br>
        <button type="submit">Se connecter</button>
    </form>
    <a href="register.php">Pas encore de compte ? S'inscrire</a>
</body>
</html>
PHP,

    'actions/login.php' => <<<'PHP'
<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT id, nom, prenom, password, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_nom'] = $user['nom'];
        $_SESSION['user_prenom'] = $user['prenom'];
        $_SESSION['user_role'] = $user['role'];

        if ($user['role'] === 'admin') {
            header('Location: ../admin_cars.php');
        } else {
            header('Location: ../index.php');
        }
        exit;
    } else {
        $_SESSION['error'] = "Identifiants invalides.";
        header('Location: ../login.php');
        exit;
    }
}
PHP,

    'actions/logout.php' => <<<'PHP'
<?php
session_start();
session_destroy();
header('Location: ../login.php');
exit;
PHP,

    'index.php' => <<<'PHP'
<?php
session_start();
require_once 'db.php';

$stmt = $pdo->query("SELECT * FROM cars WHERE status = 'disponible'");
$cars = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Accueil - Location de Voitures</title></head>
<body>
    <h1>Voitures Disponibles</h1>
    
    <?php if (isset($_SESSION['user_id'])): ?>
        <p>Bonjour <?= htmlspecialchars($_SESSION['user_prenom']); ?>! 
        <a href="history.php">Mon Historique</a> | 
        <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <a href="admin_cars.php">Espace Admin</a> | 
        <?php endif; ?>
        <a href="actions/logout.php">Déconnexion</a></p>
    <?php else: ?>
        <p><a href="login.php">Se connecter</a> | <a href="register.php">S'inscrire</a></p>
    <?php endif; ?>

    <hr>
    
    <?php if (count($cars) > 0): ?>
        <ul>
        <?php foreach ($cars as $car): ?>
            <li>
                <strong><?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></strong>
                (<?= htmlspecialchars($car['annee']) ?>) - <?= htmlspecialchars($car['prix_journalier']) ?> €/jour
                <br>
                <em>Immatriculation: <?= htmlspecialchars($car['immatriculation']) ?></em>
                <?php if (isset($_SESSION['user_id'])): ?>
                    <br><a href="reserve.php?car_id=<?= $car['id'] ?>">Réserver</a>
                <?php endif; ?>
                <hr>
            </li>
        <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Aucune voiture disponible actuellement.</p>
    <?php endif; ?>
</body>
</html>
PHP,

    'reserve.php' => <<<'PHP'
<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

if (!isset($_GET['car_id'])) {
    die("Voiture non spécifiée.");
}

$car_id = (int)$_GET['car_id'];
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$car_id]);
$car = $stmt->fetch();

if (!$car) {
    die("Voiture introuvable.");
}
?>
<!DOCTYPE html>
<html>
<head><title>Réserver - <?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></title></head>
<body>
    <h1>Réserver <?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></h1>
    <p>Prix par jour: <?= htmlspecialchars($car['prix_journalier']) ?> €</p>
    
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color:red;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <?php if (isset($_SESSION['success'])): ?>
        <p style="color:green;"><?= $_SESSION['success']; unset($_SESSION['success']); ?></p>
    <?php endif; ?>

    <form action="actions/reserve.php" method="POST">
        <input type="hidden" name="car_id" value="<?= $car_id ?>">
        <label>Date de début: <input type="date" name="date_debut" required></label><br><br>
        <label>Date de fin: <input type="date" name="date_fin" required></label><br><br>
        <button type="submit">Confirmer la réservation</button>
    </form>
    <a href="index.php">Retour</a>
</body>
</html>
PHP,

    'actions/reserve.php' => <<<'PHP'
<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id'])) {
        die("Non autorisé.");
    }

    $user_id = $_SESSION['user_id'];
    $car_id = (int)$_POST['car_id'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];

    if ($date_debut > $date_fin) {
        $_SESSION['error'] = "La date de fin doit être postérieure à la date de début.";
        header("Location: ../reserve.php?car_id=$car_id");
        exit;
    }

    $stmt = $pdo->prepare("
        SELECT id FROM reservations 
        WHERE car_id = ? 
        AND status_reservation NOT IN ('annulee', 'terminee')
        AND date_debut <= ? 
        AND date_fin >= ?
    ");
    $stmt->execute([$car_id, $date_fin, $date_debut]);
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['error'] = "Cette voiture est déjà réservée sur ce créneau.";
        header("Location: ../reserve.php?car_id=$car_id");
        exit;
    }

    $car_stmt = $pdo->prepare("SELECT prix_journalier FROM cars WHERE id = ?");
    $car_stmt->execute([$car_id]);
    $car = $car_stmt->fetch();
    
    $datetime1 = new DateTime($date_debut);
    $datetime2 = new DateTime($date_fin);
    $interval = $datetime1->diff($datetime2);
    $days = $interval->days + 1; 
    $prix_total = $days * $car['prix_journalier'];

    $insert_stmt = $pdo->prepare("
        INSERT INTO reservations (user_id, car_id, date_debut, date_fin, prix_total) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $insert_stmt->execute([$user_id, $car_id, $date_debut, $date_fin, $prix_total]);

    $_SESSION['success'] = "Réservation confirmée avec succès. Montant total : $prix_total €.";
    header("Location: ../reserve.php?car_id=$car_id");
    exit;
}
PHP,

    'history.php' => <<<'PHP'
<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare("
    SELECT r.*, c.marque, c.modele 
    FROM reservations r 
    JOIN cars c ON r.car_id = c.id 
    WHERE r.user_id = ? 
    ORDER BY r.date_creation DESC
");
$stmt->execute([$_SESSION['user_id']]);
$reservations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Mon Historique</title></head>
<body>
    <h1>Mon Historique de Réservations</h1>
    <a href="index.php">Retour à l'accueil</a>
    <hr>
    <?php if (count($reservations) > 0): ?>
        <table border="1" cellpadding="5">
            <thead>
                <tr>
                    <th>Voiture</th>
                    <th>Du</th>
                    <th>Au</th>
                    <th>Prix Total</th>
                    <th>Statut</th>
                    <th>Date de Réservation</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $res): ?>
                <tr>
                    <td><?= htmlspecialchars($res['marque'] . ' ' . $res['modele']) ?></td>
                    <td><?= htmlspecialchars($res['date_debut']) ?></td>
                    <td><?= htmlspecialchars($res['date_fin']) ?></td>
                    <td><?= htmlspecialchars($res['prix_total']) ?> €</td>
                    <td><?= htmlspecialchars($res['status_reservation']) ?></td>
                    <td><?= htmlspecialchars($res['date_creation']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Vous n'avez effectué aucune réservation.</p>
    <?php endif; ?>
</body>
</html>
PHP,

    'admin_cars.php' => <<<'PHP'
<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die("Accès refusé.");
}

$stmt = $pdo->query("SELECT * FROM cars ORDER BY id DESC");
$cars = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Admin - Gestion des Voitures</title></head>
<body>
    <h1>Gestion des Voitures</h1>
    <a href="index.php">Retour à l'accueil</a> | <a href="admin_car_add.php">Ajouter une voiture</a>
    <hr>
    <?php if (isset($_SESSION['message'])): ?>
        <p style="color:green;"><?= $_SESSION['message']; unset($_SESSION['message']); ?></p>
    <?php endif; ?>
    
    <table border="1" cellpadding="5">
        <thead>
            <tr>
                <th>ID</th>
                <th>Immatriculation</th>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Prix/J</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cars as $car): ?>
            <tr>
                <td><?= htmlspecialchars($car['id']) ?></td>
                <td><?= htmlspecialchars($car['immatriculation']) ?></td>
                <td><?= htmlspecialchars($car['marque']) ?></td>
                <td><?= htmlspecialchars($car['modele']) ?></td>
                <td><?= htmlspecialchars($car['prix_journalier']) ?> €</td>
                <td><?= htmlspecialchars($car['status']) ?></td>
                <td>
                    <a href="admin_car_edit.php?id=<?= $car['id'] ?>">Modifier</a> |
                    <a href="actions/admin_car_delete.php?id=<?= $car['id'] ?>" onclick="return confirm('Vraiment supprimer ?');">Supprimer</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
PHP,

    'admin_car_add.php' => <<<'PHP'
<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die("Accès refusé.");
}

$cat_stmt = $pdo->query("SELECT * FROM categories");
$categories = $cat_stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Admin - Ajouter Voiture</title></head>
<body>
    <h1>Ajouter une Voiture</h1>
    <form action="actions/admin_car_add.php" method="POST">
        <label>Catégorie: 
            <select name="category_id">
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars($cat['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </label><br><br>
        <label>Immatriculation: <input type="text" name="immatriculation" required></label><br><br>
        <label>Marque: <input type="text" name="marque" required></label><br><br>
        <label>Modèle: <input type="text" name="modele" required></label><br><br>
        <label>Année: <input type="number" name="annee"></label><br><br>
        <label>Type Carburant: 
            <select name="type_carburant">
                <option value="Essence">Essence</option>
                <option value="Diesel">Diesel</option>
                <option value="Electrique">Electrique</option>
                <option value="Hybride">Hybride</option>
            </select>
        </label><br><br>
        <label>Boîte de vitesse: 
            <select name="boite_vitesse">
                <option value="Manuelle">Manuelle</option>
                <option value="Automatique">Automatique</option>
            </select>
        </label><br><br>
        <label>Places: <input type="number" name="nb_places" value="5"></label><br><br>
        <label>Prix Journalier: <input type="number" step="0.01" name="prix_journalier" required></label><br><br>
        <label>Statut: 
            <select name="status">
                <option value="disponible">Disponible</option>
                <option value="maintenance">Maintenance</option>
                <option value="louée">Louée</option>
            </select>
        </label><br><br>
        <label>Climatisé: 
            <input type="radio" name="climatisé" value="1" checked> Oui
            <input type="radio" name="climatisé" value="0"> Non
        </label><br><br>
        <button type="submit">Ajouter</button>
    </form>
    <a href="admin_cars.php">Annuler</a>
</body>
</html>
PHP,

    'actions/admin_car_add.php' => <<<'PHP'
<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        die("Accès refusé.");
    }

    $category_id = $_POST['category_id'] ?: null;
    $immatriculation = trim($_POST['immatriculation']);
    $marque = trim($_POST['marque']);
    $modele = trim($_POST['modele']);
    $annee = $_POST['annee'] ?: null;
    $type_carburant = $_POST['type_carburant'];
    $boite_vitesse = $_POST['boite_vitesse'];
    $nb_places = (int)$_POST['nb_places'];
    $prix_journalier = (float)$_POST['prix_journalier'];
    $status = $_POST['status'];
    $climatise = $_POST['climatisé'];

    $stmt = $pdo->prepare("
        INSERT INTO cars (category_id, immatriculation, marque, modele, annee, type_carburant, boite_vitesse, nb_places, prix_journalier, status, climatisé)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([$category_id, $immatriculation, $marque, $modele, $annee, $type_carburant, $boite_vitesse, $nb_places, $prix_journalier, $status, $climatise]);

    $_SESSION['message'] = "Voiture ajoutée avec succès.";
    header('Location: ../admin_cars.php');
    exit;
}
PHP,

    'admin_car_edit.php' => <<<'PHP'
<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die("Accès refusé.");
}

if (!isset($_GET['id'])) {
    die("ID non fourni.");
}

$id = (int)$_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
$stmt->execute([$id]);
$car = $stmt->fetch();

if (!$car) {
    die("Voiture introuvable.");
}

$cat_stmt = $pdo->query("SELECT * FROM categories");
$categories = $cat_stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<head><title>Admin - Modifier Voiture</title></head>
<body>
    <h1>Modifier une Voiture</h1>
    <form action="actions/admin_car_edit.php" method="POST">
        <input type="hidden" name="id" value="<?= $car['id'] ?>">
        
        <label>Catégorie: 
            <select name="category_id">
                <?php foreach($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $car['category_id'] == $cat['id'] ? 'selected' : '' ?>><?= htmlspecialchars($cat['nom']) ?></option>
                <?php endforeach; ?>
            </select>
        </label><br><br>
        <label>Immatriculation: <input type="text" name="immatriculation" value="<?= htmlspecialchars($car['immatriculation']) ?>" required></label><br><br>
        <label>Marque: <input type="text" name="marque" value="<?= htmlspecialchars($car['marque']) ?>" required></label><br><br>
        <label>Modèle: <input type="text" name="modele" value="<?= htmlspecialchars($car['modele']) ?>" required></label><br><br>
        <label>Année: <input type="number" name="annee" value="<?= htmlspecialchars($car['annee']) ?>"></label><br><br>
        <label>Type Carburant: 
            <select name="type_carburant">
                <option value="Essence" <?= $car['type_carburant'] == 'Essence' ? 'selected' : '' ?>>Essence</option>
                <option value="Diesel" <?= $car['type_carburant'] == 'Diesel' ? 'selected' : '' ?>>Diesel</option>
                <option value="Electrique" <?= $car['type_carburant'] == 'Electrique' ? 'selected' : '' ?>>Electrique</option>
                <option value="Hybride" <?= $car['type_carburant'] == 'Hybride' ? 'selected' : '' ?>>Hybride</option>
            </select>
        </label><br><br>
        <label>Boîte de vitesse: 
            <select name="boite_vitesse">
                <option value="Manuelle" <?= $car['boite_vitesse'] == 'Manuelle' ? 'selected' : '' ?>>Manuelle</option>
                <option value="Automatique" <?= $car['boite_vitesse'] == 'Automatique' ? 'selected' : '' ?>>Automatique</option>
            </select>
        </label><br><br>
        <label>Places: <input type="number" name="nb_places" value="<?= htmlspecialchars($car['nb_places']) ?>"></label><br><br>
        <label>Prix Journalier: <input type="number" step="0.01" name="prix_journalier" value="<?= htmlspecialchars($car['prix_journalier']) ?>" required></label><br><br>
        <label>Statut: 
            <select name="status">
                <option value="disponible" <?= $car['status'] == 'disponible' ? 'selected' : '' ?>>Disponible</option>
                <option value="maintenance" <?= $car['status'] == 'maintenance' ? 'selected' : '' ?>>Maintenance</option>
                <option value="louée" <?= $car['status'] == 'louée' ? 'selected' : '' ?>>Louée</option>
            </select>
        </label><br><br>
        <label>Climatisé: 
            <input type="radio" name="climatisé" value="1" <?= $car['climatisé'] == 1 ? 'checked' : '' ?>> Oui
            <input type="radio" name="climatisé" value="0" <?= $car['climatisé'] == 0 ? 'checked' : '' ?>> Non
        </label><br><br>
        <button type="submit">Modifier</button>
    </form>
    <a href="admin_cars.php">Annuler</a>
</body>
</html>
PHP,

    'actions/admin_car_edit.php' => <<<'PHP'
<?php
session_start();
require_once '../db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
        die("Accès refusé.");
    }

    $id = (int)$_POST['id'];
    $category_id = $_POST['category_id'] ?: null;
    $immatriculation = trim($_POST['immatriculation']);
    $marque = trim($_POST['marque']);
    $modele = trim($_POST['modele']);
    $annee = $_POST['annee'] ?: null;
    $type_carburant = $_POST['type_carburant'];
    $boite_vitesse = $_POST['boite_vitesse'];
    $nb_places = (int)$_POST['nb_places'];
    $prix_journalier = (float)$_POST['prix_journalier'];
    $status = $_POST['status'];
    $climatise = $_POST['climatisé'];

    $stmt = $pdo->prepare("
        UPDATE cars SET 
            category_id = ?, immatriculation = ?, marque = ?, modele = ?, 
            annee = ?, type_carburant = ?, boite_vitesse = ?, nb_places = ?, 
            prix_journalier = ?, status = ?, climatisé = ?
        WHERE id = ?
    ");
    $stmt->execute([$category_id, $immatriculation, $marque, $modele, $annee, $type_carburant, $boite_vitesse, $nb_places, $prix_journalier, $status, $climatise, $id]);

    $_SESSION['message'] = "Voiture modifiée avec succès.";
    header('Location: ../admin_cars.php');
    exit;
}
PHP,

    'actions/admin_car_delete.php' => <<<'PHP'
<?php
session_start();
require_once '../db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    die("Accès refusé.");
}

if (isset($_GET['id'])) {
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
    $stmt->execute([$id]);
    $_SESSION['message'] = "Voiture supprimée avec succès.";
}

header('Location: ../admin_cars.php');
exit;
PHP
];

if (!is_dir('actions')) {
    mkdir('actions', 0777, true);
}

foreach ($files as $filepath => $content) {
    file_put_contents($filepath, $content);
}
echo "Fichiers générés avec succès.";
?>
