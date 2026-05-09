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