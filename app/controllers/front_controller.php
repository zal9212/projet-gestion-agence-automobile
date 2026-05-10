<?php
/**
 * CONTROLEUR FRONT (PROCEDURAL) - Version Corrigée
 */

function front_home() {
    $pdo = get_pdo();
    $user_id = $_SESSION['user_id'] ?? 0;
    
    // On récupère toutes les voitures
    $stmt = $pdo->prepare("SELECT c.*, cat.nom as categorie_nom, 
                          (SELECT COUNT(*) FROM favorites f WHERE f.car_id = c.id AND f.user_id = ?) as is_fav
                          FROM cars c 
                          LEFT JOIN categories cat ON c.category_id = cat.id 
                          LIMIT 6");
    $stmt->execute([$user_id]);
    $cars = $stmt->fetchAll();

    // Récupérer les marques uniques
    $brands = $pdo->query("SELECT DISTINCT marque FROM cars ORDER BY marque ASC")->fetchAll(PDO::FETCH_COLUMN);

    render_view('front/home', ['cars' => $cars, 'brands' => $brands]);
}

function front_search() {
    $pdo = get_pdo();
    $user_id = $_SESSION['user_id'] ?? 0;
    
    $category_ids = $_GET['categories'] ?? [];
    $transmission = $_GET['transmission'] ?? '';
    $query = $_GET['q'] ?? '';

    $sql = "SELECT c.*, cat.nom as categorie_nom,
                   (SELECT COUNT(*) FROM favorites f WHERE f.car_id = c.id AND f.user_id = ?) as is_fav
            FROM cars c 
            LEFT JOIN categories cat ON c.category_id = cat.id
            WHERE 1=1";
    
    $params = [$user_id];

    if (!empty($category_ids)) {
        $placeholders = implode(',', array_fill(0, count($category_ids), '?'));
        $sql .= " AND c.category_id IN ($placeholders)";
        foreach($category_ids as $id) $params[] = $id;
    }

    if ($transmission) {
        $sql .= " AND c.boite_vitesse = ?";
        $params[] = $transmission;
    }

    if ($query) {
        $sql .= " AND (c.marque LIKE ? OR c.modele LIKE ?)";
        $params[] = "%$query%";
        $params[] = "%$query%";
    }

    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $cars = $stmt->fetchAll();

    $categories = $pdo->query("SELECT * FROM categories")->fetchAll();
    
    render_view('front/search_results', ['cars' => $cars, 'categories' => $categories]);
}

function front_reserve() {
    if (!isset($_SESSION['user_id'])) redirect('index.php?action=login');
    $pdo = get_pdo();
    $car_id = (int)$_GET['id'];
    
    $stmt = $pdo->prepare("SELECT c.*, cat.nom as categorie_nom FROM cars c LEFT JOIN categories cat ON c.category_id = cat.id WHERE c.id = ?");
    $stmt->execute([$car_id]);
    $car = $stmt->fetch();
    
    if (!$car) redirect('index.php');
    
    // Récupérer les réservations existantes pour afficher les indisponibilités
    $stmt_res = $pdo->prepare("SELECT date_debut, date_fin FROM reservations WHERE car_id = ? AND status_reservation NOT IN ('annulee', 'terminee') AND date_fin >= CURRENT_DATE()");
    $stmt_res->execute([$car_id]);
    $bookings = $stmt_res->fetchAll();
    
    $locations = $pdo->query("SELECT * FROM locations ORDER BY id ASC")->fetchAll();
    render_view('front/reserve', ['car' => $car, 'locations' => $locations, 'bookings' => $bookings]);
}

function front_confirm_reserve() {
    if (!isset($_SESSION['user_id'])) die("Accès refusé");
    $pdo = get_pdo();
    
    $user_id = $_SESSION['user_id'];
    $car_id = (int)$_POST['car_id'];
    $date_debut = $_POST['date_debut'];
    $date_fin = $_POST['date_fin'];
    
    // --- COHERENCE 0 : VERIFIER LES DATES ---
    if (strtotime($date_fin) < strtotime($date_debut)) {
        $_SESSION['error'] = "Erreur : La date de retour ne peut pas être antérieure à la date de départ.";
        redirect("index.php?action=reserve&id=$car_id");
    }

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

function front_verify_contract() {
    $id = (int)($_GET['id'] ?? 0);
    $pdo = get_pdo();
    
    $stmt = $pdo->prepare("SELECT r.*, u.nom, u.prenom, c.marque, c.modele, c.immatriculation, agent.nom as agent_nom, agent.prenom as agent_prenom
                          FROM reservations r 
                          JOIN users u ON r.user_id = u.id 
                          JOIN cars c ON r.car_id = c.id 
                          LEFT JOIN users agent ON r.validated_by = agent.id
                          WHERE r.id = ?");
    $stmt->execute([$id]);
    $reservation = $stmt->fetch();
    
    if (!$reservation) die("Contrat invalide ou inexistant.");
    
    render_view('front/verify_contract', ['res' => $reservation]);
}

function front_toggle_favorite() {
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['status' => 'error', 'message' => 'Login required']);
        exit;
    }
    $pdo = get_pdo();
    $user_id = $_SESSION['user_id'];
    $car_id = (int)$_GET['id'];
    
    $check = $pdo->prepare("SELECT id FROM favorites WHERE user_id = ? AND car_id = ?");
    $check->execute([$user_id, $car_id]);
    $fav = $check->fetch();
    
    if ($fav) {
        $pdo->prepare("DELETE FROM favorites WHERE id = ?")->execute([$fav['id']]);
        echo json_encode(['status' => 'removed']);
    } else {
        $pdo->prepare("INSERT INTO favorites (user_id, car_id) VALUES (?, ?)")->execute([$user_id, $car_id]);
        echo json_encode(['status' => 'added']);
    }
    exit;
}

function front_favorites() {
    if (!isset($_SESSION['user_id'])) redirect('index.php?action=login');
    $pdo = get_pdo();
    $stmt = $pdo->prepare("SELECT c.*, cat.nom as categorie_nom FROM favorites f JOIN cars c ON f.car_id = c.id LEFT JOIN categories cat ON c.category_id = cat.id WHERE f.user_id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $cars = $stmt->fetchAll();
    render_view('front/favorites', ['cars' => $cars]);
}
