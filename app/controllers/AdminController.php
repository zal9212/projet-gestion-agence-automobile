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
        $cars = $pdo->query("SELECT c.*, cat.nom as cat_nom FROM cars c LEFT JOIN categories cat ON c.category_id = cat.id ORDER BY c.id DESC")->fetchAll();
        require 'app/views/admin/cars.php';
    }

    public static function carForm() {
        self::checkAdmin();
        $pdo = getPDO();
        $categories = $pdo->query("SELECT * FROM categories")->fetchAll();
        
        $car = null;
        if(isset($_GET['id'])) {
            $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $car = $stmt->fetch();
        }
        require 'app/views/admin/car_form.php';
    }

    public static function carSave() {
        self::checkAdmin();
        $pdo = getPDO();
        
        $id = $_POST['id'] ?? null;
        $category_id = $_POST['category_id'];
        $immatriculation = $_POST['immatriculation'];
        $marque = $_POST['marque'];
        $modele = $_POST['modele'];
        $type_carburant = $_POST['type_carburant'];
        $boite_vitesse = $_POST['boite_vitesse'];
        $nb_places = $_POST['nb_places'];
        $prix_journalier = $_POST['prix_journalier'];
        $caution = $_POST['caution'];
        $image_principale = $_POST['image_principale'];
        $status = $_POST['status'];
        
        if($id) {
            $stmt = $pdo->prepare("UPDATE cars SET category_id=?, immatriculation=?, marque=?, modele=?, type_carburant=?, boite_vitesse=?, nb_places=?, prix_journalier=?, caution=?, image_principale=?, status=? WHERE id=?");
            $stmt->execute([$category_id, $immatriculation, $marque, $modele, $type_carburant, $boite_vitesse, $nb_places, $prix_journalier, $caution, $image_principale, $status, $id]);
            $_SESSION['success'] = "Vehicle updated successfully.";
        } else {
            $stmt = $pdo->prepare("INSERT INTO cars (category_id, immatriculation, marque, modele, type_carburant, boite_vitesse, nb_places, prix_journalier, caution, image_principale, status) VALUES (?,?,?,?,?,?,?,?,?,?,?)");
            $stmt->execute([$category_id, $immatriculation, $marque, $modele, $type_carburant, $boite_vitesse, $nb_places, $prix_journalier, $caution, $image_principale, $status]);
            $_SESSION['success'] = "Vehicle added successfully.";
        }
        header('Location: index.php?action=admin_cars');
    }

    public static function carDelete() {
        self::checkAdmin();
        $pdo = getPDO();
        if(isset($_GET['id'])) {
            $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
            $stmt->execute([$_GET['id']]);
            $_SESSION['success'] = "Vehicle deleted successfully.";
        }
        header('Location: index.php?action=admin_cars');
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
        $_SESSION['success'] = "Reservation status updated.";
        header('Location: index.php?action=admin_reservations');
    }

    public static function crm() {
        self::checkAdmin();
        $pdo = getPDO();
        $users = $pdo->query("SELECT * FROM users WHERE role = 'client' ORDER BY id DESC")->fetchAll();
        require 'app/views/admin/crm.php';
    }

    public static function maintenance() {
        self::checkAdmin();
        $pdo = getPDO();
        $cars = $pdo->query("SELECT * FROM cars ORDER BY id DESC")->fetchAll();
        require 'app/views/admin/maintenance.php';
    }

    public static function checkin() {
        self::checkAdmin();
        $pdo = getPDO();
        $reservations = $pdo->query("SELECT r.*, u.nom, u.prenom, c.immatriculation FROM reservations r JOIN users u ON r.user_id = u.id JOIN cars c ON r.car_id = c.id WHERE r.status_reservation IN ('validee', 'en_cours') ORDER BY r.date_debut ASC")->fetchAll();
        require 'app/views/admin/checkin.php';
    }

    public static function checkoutProcess() {
        self::checkAdmin();
        $pdo = getPDO();
        $id = (int)$_GET['id'];
        $stmt = $pdo->prepare("SELECT r.*, u.nom, u.prenom, u.email, u.telephone, c.marque, c.modele, c.immatriculation FROM reservations r JOIN users u ON r.user_id = u.id JOIN cars c ON r.car_id = c.id WHERE r.id = ?");
        $stmt->execute([$id]);
        $reservation = $stmt->fetch();
        
        $stmt2 = $pdo->prepare("SELECT kilometrage FROM cars WHERE id = ?");
        $stmt2->execute([$reservation['car_id']]);
        $car = $stmt2->fetch();
        
        require 'app/views/admin/checkout_process.php';
    }

    public static function saveCheckout() {
        self::checkAdmin();
        $pdo = getPDO();
        $id = (int)$_POST['id'];
        $km = (int)$_POST['kilometrage_depart'];
        $carburant = $_POST['niveau_carburant_depart'];
        $signature = $_POST['signature_base64'];

        $stmt = $pdo->prepare("UPDATE reservations SET kilometrage_depart = ?, niveau_carburant_depart = ?, signature_base64 = ?, status_reservation = 'en_cours' WHERE id = ?");
        $stmt->execute([$km, $carburant, $signature, $id]);
        
        // Mettre à jour le statut de la voiture
        $stmt2 = $pdo->prepare("UPDATE cars SET status = 'louée', kilometrage = ? WHERE id = (SELECT car_id FROM reservations WHERE id = ?)");
        $stmt2->execute([$km, $id]);

        header("Location: index.php?action=admin_print_contract&id=".$id);
    }

    public static function printContract() {
        self::checkAdmin();
        $pdo = getPDO();
        $id = (int)$_GET['id'];
        $stmt = $pdo->prepare("SELECT r.*, u.nom, u.prenom, u.email, u.telephone, c.marque, c.modele, c.immatriculation FROM reservations r JOIN users u ON r.user_id = u.id JOIN cars c ON r.car_id = c.id WHERE r.id = ?");
        $stmt->execute([$id]);
        $reservation = $stmt->fetch();
        
        $stmt2 = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
        $stmt2->execute([$reservation['car_id']]);
        $car = $stmt2->fetch();
        
        require 'app/views/admin/contract_print.php';
    }

    public static function gantt() {
        self::checkAdmin();
        $pdo = getPDO();
        $cars = $pdo->query("SELECT id, immatriculation, marque, modele FROM cars ORDER BY marque ASC")->fetchAll();
        // On récupère toutes les réservations du mois courant
        $month = date('m');
        $year = date('Y');
        $stmt = $pdo->prepare("SELECT r.*, u.nom, u.prenom FROM reservations r JOIN users u ON r.user_id = u.id WHERE MONTH(r.date_debut) = ? OR MONTH(r.date_fin) = ? AND YEAR(r.date_debut) = ?");
        $stmt->execute([$month, $month, $year]);
        $reservations = $stmt->fetchAll();
        require 'app/views/admin/gantt.php';
    }
}