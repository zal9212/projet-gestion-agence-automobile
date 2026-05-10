<?php
/**
 * CONTROLEUR FRONT (PROCEDURAL) - Version Corrigée
 */

function front_home() {
    $pdo = get_pdo();
    $stmt = $pdo->query("SELECT c.*, cat.nom as categorie_nom FROM cars c LEFT JOIN categories cat ON c.category_id = cat.id WHERE c.status = 'disponible' LIMIT 6");
    $cars = $stmt->fetchAll();
    render_view('front/home', ['cars' => $cars]);
}

function front_search() {
    $pdo = get_pdo();
    $date_debut = $_GET['date_debut'] ?? '';
    $date_fin = $_GET['date_fin'] ?? '';
    
    $sql = "SELECT c.*, cat.nom as categorie_nom FROM cars c LEFT JOIN categories cat ON c.category_id = cat.id WHERE c.status = 'disponible'";
    if ($date_debut && $date_fin) {
        $sql .= " AND c.id NOT IN (SELECT car_id FROM reservations WHERE status_reservation NOT IN ('annulee', 'terminee') AND date_debut <= :date_fin AND date_fin >= :date_debut)";
    }
    
    $stmt = $pdo->prepare($sql);
    if ($date_debut && $date_fin) {
        $stmt->execute(['date_fin' => $date_fin, 'date_debut' => $date_debut]);
    } else {
        $stmt->execute();
    }
    
    $cars = $stmt->fetchAll();
    render_view('front/search_results', ['cars' => $cars]);
}

function front_reserve() {
    if (!isset($_SESSION['user_id'])) redirect('index.php?action=login');
    $pdo = get_pdo();
    $car_id = (int)$_GET['id'];
    
    $stmt = $pdo->prepare("SELECT c.*, cat.nom as categorie_nom FROM cars c LEFT JOIN categories cat ON c.category_id = cat.id WHERE c.id = ?");
    $stmt->execute([$car_id]);
    $car = $stmt->fetch();
    
    if (!$car) redirect('index.php');
    
    $locations = $pdo->query("SELECT * FROM locations ORDER BY id ASC")->fetchAll();
    render_view('front/reserve', ['car' => $car, 'locations' => $locations]);
}

function front_confirm_reserve() {
    if (!isset($_SESSION['user_id'])) die("Accès refusé");
    $pdo = get_pdo();
    
    $user_id = $_SESSION['user_id'];
    $car_id = (int)$_POST['car_id'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    $lieu_prise_id = $_POST['lieu_prise_id'] ?: null;
    $lieu_retour_id = $_POST['lieu_retour_id'] ?: null;
    $avec_chauffeur = isset($_POST['avec_chauffeur']) ? 1 : 0;

    // --- COHERENCE 1 : VERIFIER DISPONIBILITE REELLE ---
    $check = $pdo->prepare("SELECT id FROM reservations WHERE car_id = ? AND status_reservation NOT IN ('annulee', 'terminee') AND date_debut <= ? AND date_fin >= ?");
    $check->execute([$car_id, $date_fin, $date_debut]);
    if ($check->fetch()) {
        $_SESSION['error'] = "Désolé, ce véhicule vient d'être réservé par quelqu'un d'autre sur ces dates.";
        redirect("index.php?action=reserve&id=$car_id");
    }

    // Calcul prix
    $car_stmt = $pdo->prepare("SELECT prix_journalier FROM cars WHERE id = ?");
    $car_stmt->execute([$car_id]);
    $prix_journalier = $car_stmt->fetchColumn();
    
    $days = (new DateTime($date_debut))->diff(new DateTime($date_fin))->days + 1;
    $prix_total = $days * $prix_journalier;
    
    if ($avec_chauffeur) $prix_total += (15000 * $days);
    
    if ($lieu_prise_id) {
        $loc = $pdo->prepare("SELECT frais_supplementaire FROM locations WHERE id = ?");
        $loc->execute([$lieu_prise_id]);
        $prix_total += $loc->fetchColumn();
    }
    if ($lieu_retour_id) {
        $loc = $pdo->prepare("SELECT frais_supplementaire FROM locations WHERE id = ?");
        $loc->execute([$lieu_retour_id]);
        $prix_total += $loc->fetchColumn();
    }

    $stmt = $pdo->prepare("INSERT INTO reservations (user_id, car_id, date_debut, date_fin, lieu_prise_id, lieu_retour_id, avec_chauffeur, prix_total) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$user_id, $car_id, $date_debut, $date_fin, $lieu_prise_id, $lieu_retour_id, $avec_chauffeur, $prix_total]);
    
    // NOTIFICATION POUR L'ADMIN
    $admin_stmt = $pdo->query("SELECT id FROM users WHERE role = 'admin' LIMIT 1");
    $admin_id = $admin_stmt->fetchColumn();
    if($admin_id) {
        send_notification($admin_id, "Nouvelle réservation reçue de " . $_SESSION['user_prenom'], "success");
    }

    $_SESSION['success'] = "Réservation enregistrée avec succès ! Notre équipe vous contactera.";
    redirect('index.php?action=history');
}

function front_history() {
    if (!isset($_SESSION['user_id'])) redirect('index.php?action=login');
    $pdo = get_pdo();
    $stmt = $pdo->prepare("SELECT r.*, c.marque, c.modele, c.image_principale, c.immatriculation, c.type_carburant FROM reservations r JOIN cars c ON r.car_id = c.id WHERE r.user_id = ? ORDER BY r.date_creation DESC");
    $stmt->execute([$_SESSION['user_id']]);
    $reservations = $stmt->fetchAll();
    render_view('front/history', ['reservations' => $reservations]);
}

function front_cancel_reservation() {
    if (!isset($_SESSION['user_id'])) redirect('index.php?action=login');
    $pdo = get_pdo();
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("UPDATE reservations SET status_reservation = 'annulee' WHERE id = ? AND user_id = ? AND status_reservation = 'en_attente'");
    $stmt->execute([$id, $_SESSION['user_id']]);
    $_SESSION['success'] = "Réservation annulée.";
    redirect('index.php?action=history');
}

function front_profile() {
    if (!isset($_SESSION['user_id'])) redirect('index.php?action=login');
    $pdo = get_pdo();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    render_view('front/profile', ['user' => $user]);
}
