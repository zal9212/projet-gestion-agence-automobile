<?php
// Script de génération des contrôleurs
$files = [
    'index.php' => <<<'PHP'
<?php
session_start();
require_once 'app/config.php';
require_once 'app/controllers/FrontController.php';
require_once 'app/controllers/AuthController.php';
require_once 'app/controllers/AdminController.php';

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'home': FrontController::home(); break;
    case 'search': FrontController::search(); break;
    case 'reserve': FrontController::reserve(); break;
    case 'confirm_reserve': FrontController::confirmReserve(); break;
    case 'history': FrontController::history(); break;
    
    case 'login': AuthController::login(); break;
    case 'do_login': AuthController::doLogin(); break;
    case 'register': AuthController::register(); break;
    case 'do_register': AuthController::doRegister(); break;
    case 'logout': AuthController::logout(); break;
    
    case 'admin_dashboard': AdminController::dashboard(); break;
    case 'admin_cars': AdminController::cars(); break;
    case 'admin_reservations': AdminController::reservations(); break;
    case 'admin_res_update': AdminController::updateReservationStatus(); break;
    
    default: echo "404 Not Found"; break;
}
PHP,

    'app/controllers/FrontController.php' => <<<'PHP'
<?php
class FrontController {
    public static function home() {
        $pdo = getPDO();
        $stmt = $pdo->query("SELECT c.*, cat.nom as categorie_nom FROM cars c LEFT JOIN categories cat ON c.category_id = cat.id WHERE c.status = 'disponible' LIMIT 6");
        $cars = $stmt->fetchAll();
        require 'app/views/front/home.php';
    }

    public static function search() {
        $pdo = getPDO();
        $date_debut = $_GET['date_debut'] ?? '';
        $date_fin = $_GET['date_fin'] ?? '';
        
        $sql = "SELECT c.*, cat.nom as categorie_nom FROM cars c LEFT JOIN categories cat ON c.category_id = cat.id WHERE c.status = 'disponible'";
        if ($date_debut && $date_fin) {
            $sql .= " AND c.id NOT IN (
                SELECT car_id FROM reservations 
                WHERE status_reservation NOT IN ('annulee', 'terminee')
                AND date_debut <= :date_fin AND date_fin >= :date_debut
            )";
        }
        
        $stmt = $pdo->prepare($sql);
        if ($date_debut && $date_fin) {
            $stmt->execute(['date_fin' => $date_fin, 'date_debut' => $date_debut]);
        } else {
            $stmt->execute();
        }
        
        $cars = $stmt->fetchAll();
        require 'app/views/front/search_results.php';
    }

    public static function reserve() {
        if (!isset($_SESSION['user_id'])) { header('Location: index.php?action=login'); exit; }
        $pdo = getPDO();
        $car_id = (int)$_GET['id'];
        $stmt = $pdo->prepare("SELECT c.*, cat.nom as categorie_nom FROM cars c LEFT JOIN categories cat ON c.category_id = cat.id WHERE c.id = ?");
        $stmt->execute([$car_id]);
        $car = $stmt->fetch();
        $locations = $pdo->query("SELECT * FROM locations")->fetchAll();
        require 'app/views/front/reserve.php';
    }

    public static function confirmReserve() {
        if (!isset($_SESSION['user_id'])) die("Non autorisé");
        $pdo = getPDO();
        
        $user_id = $_SESSION['user_id'];
        $car_id = (int)$_POST['car_id'];
        $date_debut = $_POST['date_debut'];
        $date_fin = $_POST['date_fin'];
        $lieu_prise_id = $_POST['lieu_prise_id'] ?: null;
        $lieu_retour_id = $_POST['lieu_retour_id'] ?: null;
        $avec_chauffeur = isset($_POST['avec_chauffeur']) ? 1 : 0;
        
        $check = $pdo->prepare("SELECT id FROM reservations WHERE car_id = ? AND status_reservation NOT IN ('annulee', 'terminee') AND date_debut <= ? AND date_fin >= ?");
        $check->execute([$car_id, $date_fin, $date_debut]);
        if ($check->rowCount() > 0) {
            $_SESSION['error'] = "Voiture déjà réservée.";
            header("Location: index.php?action=reserve&id=$car_id");
            exit;
        }

        $car = $pdo->prepare("SELECT prix_journalier, caution FROM cars WHERE id = ?");
        $car->execute([$car_id]);
        $carData = $car->fetch();
        
        $days = (new DateTime($date_debut))->diff(new DateTime($date_fin))->days + 1;
        $prix_total = $days * $carData['prix_journalier'];
        
        if ($avec_chauffeur) $prix_total += (15000 * $days); // Ex: 15000 FCFA/jour pour le chauffeur
        
        if($lieu_prise_id){
            $loc = $pdo->prepare("SELECT frais_supplementaire FROM locations WHERE id = ?");
            $loc->execute([$lieu_prise_id]); $prix_total += $loc->fetchColumn();
        }
        if($lieu_retour_id){
            $loc = $pdo->prepare("SELECT frais_supplementaire FROM locations WHERE id = ?");
            $loc->execute([$lieu_retour_id]); $prix_total += $loc->fetchColumn();
        }

        $stmt = $pdo->prepare("INSERT INTO reservations (user_id, car_id, date_debut, date_fin, lieu_prise_id, lieu_retour_id, avec_chauffeur, prix_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([$user_id, $car_id, $date_debut, $date_fin, $lieu_prise_id, $lieu_retour_id, $avec_chauffeur, $prix_total]);
        
        $_SESSION['success'] = "Réservation enregistrée ! Notre agence va vous contacter.";
        header("Location: index.php?action=history");
        exit;
    }

    public static function history() {
        if (!isset($_SESSION['user_id'])) { header('Location: index.php?action=login'); exit; }
        $pdo = getPDO();
        $stmt = $pdo->prepare("SELECT r.*, c.marque, c.modele, c.image_principale FROM reservations r JOIN cars c ON r.car_id = c.id WHERE r.user_id = ? ORDER BY r.date_creation DESC");
        $stmt->execute([$_SESSION['user_id']]);
        $reservations = $stmt->fetchAll();
        require 'app/views/front/history.php';
    }
}
PHP,

    'app/controllers/AuthController.php' => <<<'PHP'
<?php
class AuthController {
    public static function login() {
        require 'app/views/front/login.php';
    }

    public static function doLogin() {
        $pdo = getPDO();
        $email = trim($_POST['email']);
        $password = $_POST['password'];

        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_nom'] = $user['nom'];
            $_SESSION['user_prenom'] = $user['prenom'];
            $_SESSION['user_role'] = $user['role'];

            if ($user['role'] === 'admin') header('Location: index.php?action=admin_dashboard');
            else header('Location: index.php');
        } else {
            $_SESSION['error'] = "Identifiants invalides.";
            header('Location: index.php?action=login');
        }
    }

    public static function register() {
        require 'app/views/front/register.php';
    }

    public static function doRegister() {
        $pdo = getPDO();
        $nom = trim($_POST['nom']);
        $prenom = trim($_POST['prenom']);
        $email = trim($_POST['email']);
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

        try {
            $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, password) VALUES (?, ?, ?, ?)");
            $stmt->execute([$nom, $prenom, $email, $password]);
            $_SESSION['success'] = "Inscription réussie.";
            header('Location: index.php?action=login');
        } catch(Exception $e) {
            $_SESSION['error'] = "Cet email est déjà utilisé.";
            header('Location: index.php?action=register');
        }
    }

    public static function logout() {
        session_destroy();
        header('Location: index.php');
    }
}
PHP,

    'app/controllers/AdminController.php' => <<<'PHP'
<?php
class AdminController {
    private static function checkAdmin() {
        if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') die("Accès refusé");
    }

    public static function dashboard() {
        self::checkAdmin();
        $pdo = getPDO();
        $stats = [
            'total_cars' => $pdo->query("SELECT COUNT(*) FROM cars")->fetchColumn(),
            'total_reservations' => $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn(),
            'ca_mensuel' => $pdo->query("SELECT SUM(prix_total) FROM reservations WHERE status_reservation IN ('validee', 'terminee') AND MONTH(date_creation) = MONTH(CURRENT_DATE())")->fetchColumn() ?: 0,
            'attente' => $pdo->query("SELECT COUNT(*) FROM reservations WHERE status_reservation = 'en_attente'")->fetchColumn()
        ];
        
        $recent_reservations = $pdo->query("SELECT r.*, u.nom, u.prenom, c.marque, c.modele FROM reservations r JOIN users u ON r.user_id = u.id JOIN cars c ON r.car_id = c.id ORDER BY r.id DESC LIMIT 5")->fetchAll();
        
        require 'app/views/admin/dashboard.php';
    }

    public static function cars() {
        self::checkAdmin();
        $pdo = getPDO();
        $cars = $pdo->query("SELECT c.*, cat.nom as cat_nom FROM cars c LEFT JOIN categories cat ON c.category_id = cat.id")->fetchAll();
        require 'app/views/admin/cars.php';
    }

    public static function reservations() {
        self::checkAdmin();
        $pdo = getPDO();
        $reservations = $pdo->query("SELECT r.*, u.nom, u.prenom, u.telephone, c.marque, c.immatriculation FROM reservations r JOIN users u ON r.user_id = u.id JOIN cars c ON r.car_id = c.id ORDER BY r.id DESC")->fetchAll();
        require 'app/views/admin/reservations.php';
    }
    
    public static function updateReservationStatus() {
        self::checkAdmin();
        $pdo = getPDO();
        $id = (int)$_POST['id'];
        $status = $_POST['status'];
        $stmt = $pdo->prepare("UPDATE reservations SET status_reservation = ? WHERE id = ?");
        $stmt->execute([$status, $id]);
        $_SESSION['success'] = "Statut mis à jour.";
        header('Location: index.php?action=admin_reservations');
    }
}
PHP
];

foreach ($files as $filepath => $content) {
    file_put_contents($filepath, $content);
}
echo "Controllers générés.\n";
?>
