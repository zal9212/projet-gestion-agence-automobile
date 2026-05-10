<?php
/**
 * CONTROLEUR ADMIN (PROCEDURAL) - Version Corrigée
 */

function check_staff() {
    if (!isset($_SESSION['user_role']) || !in_array($_SESSION['user_role'], ['admin', 'employee'])) {
        redirect('index.php?action=login');
    }
}

// Pour compatibilité avec le code existant mais permet maintenant les employés
function check_admin() {
    check_staff();
}

function admin_dashboard() {
    check_admin();
    $pdo = get_pdo();
    
    // --- COHERENCE : CA MENSUEL FILTRÉ PAR MOIS ET ANNÉE ---
    $ca_mensuel = $pdo->query("SELECT SUM(prix_total) FROM reservations 
                               WHERE status_reservation IN ('validee', 'terminee', 'en_cours') 
                               AND MONTH(date_creation) = MONTH(CURRENT_DATE()) 
                               AND YEAR(date_creation) = YEAR(CURRENT_DATE())")->fetchColumn() ?: 0;

    $stats = [
        'total_cars' => $pdo->query("SELECT COUNT(*) FROM cars")->fetchColumn(),
        'total_reservations' => $pdo->query("SELECT COUNT(*) FROM reservations")->fetchColumn(),
        'ca_mensuel' => $ca_mensuel,
        'attente' => $pdo->query("SELECT COUNT(*) FROM reservations WHERE status_reservation = 'en_attente'")->fetchColumn(),
    ];
    
    $recent_reservations = $pdo->query("SELECT r.*, u.nom, u.prenom, c.marque, c.modele FROM reservations r JOIN users u ON r.user_id = u.id JOIN cars c ON r.car_id = c.id ORDER BY r.id DESC LIMIT 5")->fetchAll();

    // --- ALERTES MAINTENANCE : Assurances expirant dans moins de 30 jours ---
    $alerts = $pdo->query("SELECT id, immatriculation, marque, modele, date_assurance 
                           FROM cars 
                           WHERE date_assurance IS NOT NULL 
                           AND date_assurance <= DATE_ADD(CURRENT_DATE(), INTERVAL 30 DAY) 
                           ORDER BY date_assurance ASC")->fetchAll();

    render_view('admin/dashboard', [
        'stats' => $stats, 
        'recent_reservations' => $recent_reservations,
        'alerts' => $alerts,
        'is_admin' => ($_SESSION['user_role'] === 'admin')
    ]);
}

function admin_cars() {
    check_admin();
    $pdo = get_pdo();
    $cars = $pdo->query("SELECT c.*, cat.nom as cat_nom FROM cars c LEFT JOIN categories cat ON c.category_id = cat.id ORDER BY c.id DESC")->fetchAll();
    render_view('admin/cars', ['cars' => $cars]);
}

function admin_car_form() {
    check_admin();
    $pdo = get_pdo();
    $id = $_GET['id'] ?? null;
    $car = null;
    $gallery = [];
    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
        $stmt->execute([$id]);
        $car = $stmt->fetch();
        
        $gallery = $pdo->prepare("SELECT * FROM car_images WHERE car_id = ?");
        $gallery->execute([$id]);
        $gallery = $gallery->fetchAll();
    }
    $categories = $pdo->query("SELECT * FROM categories")->fetchAll();
    
    // On récupère les logos disponibles localement pour le sélecteur
    $brand_logos = [];
    $logo_dir = 'assets/brand_logos/';
    if (is_dir($logo_dir)) {
        $files = scandir($logo_dir);
        foreach ($files as $file) {
            if ($file !== '.' && $file !== '..') {
                $brand_logos[] = [
                    'name' => ucfirst(str_replace('.png', '', $file)),
                    'path' => $logo_dir . $file
                ];
            }
        }
    }

    render_view('admin/car_form', [
        'car' => $car, 
        'categories' => $categories, 
        'gallery' => $gallery,
        'available_logos' => $brand_logos
    ]);
}

function admin_car_save() {
    check_admin();
    $pdo = get_pdo();
    $id = $_POST['id'] ?? null;
    
    $image_principale = $_POST['image_principale'] ?? '';
    $brand_logo = $_POST['brand_logo_path'] ?? '';

    $uploadDir = 'uploads/cars/';
    if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);

    // Image Principale
    if (isset($_FILES['image_file']) && $_FILES['image_file']['error'] == UPLOAD_ERR_OK) {
        $extension = pathinfo($_FILES['image_file']['name'], PATHINFO_EXTENSION);
        $fileName = 'car_' . time() . '_' . uniqid() . '.' . $extension;
        if (move_uploaded_file($_FILES['image_file']['tmp_name'], $uploadDir . $fileName)) {
            $image_principale = $uploadDir . $fileName;
        }
    }

    // Logo Marque
    if (isset($_FILES['brand_logo_file']) && $_FILES['brand_logo_file']['error'] == UPLOAD_ERR_OK) {
        $extension = pathinfo($_FILES['brand_logo_file']['name'], PATHINFO_EXTENSION);
        $fileName = 'logo_' . time() . '_' . uniqid() . '.' . $extension;
        if (move_uploaded_file($_FILES['brand_logo_file']['tmp_name'], $uploadDir . $fileName)) {
            $brand_logo = $uploadDir . $fileName;
        }
    }

    // Nettoyage des données numériques et dates
    $category_id = !empty($_POST['category_id']) ? $_POST['category_id'] : null;
    $prix_journalier = !empty($_POST['prix_journalier']) ? $_POST['prix_journalier'] : 0;
    $caution = !empty($_POST['caution']) ? $_POST['caution'] : 0;
    $nb_places = !empty($_POST['nb_places']) ? $_POST['nb_places'] : 5;
    $date_assurance = !empty($_POST['date_assurance']) ? $_POST['date_assurance'] : null;

    $data = [
        $category_id, $_POST['immatriculation'], $_POST['marque'], $_POST['modele'],
        $_POST['type_carburant'], $_POST['boite_vitesse'], $nb_places,
        $prix_journalier, $caution, $image_principale, $brand_logo, $date_assurance, $_POST['status']
    ];

    try {
        if ($id && !empty($id)) {
            $stmt = $pdo->prepare("UPDATE cars SET category_id=?, immatriculation=?, marque=?, modele=?, type_carburant=?, boite_vitesse=?, nb_places=?, prix_journalier=?, caution=?, image_principale=?, brand_logo=?, date_assurance=?, status=? WHERE id=?");
            $data[] = $id;
            $stmt->execute($data);
            $car_id = $id;
            $_SESSION['success'] = "Véhicule mis à jour.";
        } else {
            // --- CHECK SI IMMATRICULATION EXISTE DEJA ---
            $immat = $_POST['immatriculation'];
            $check = $pdo->prepare("SELECT COUNT(*) FROM cars WHERE immatriculation = ?");
            $check->execute([$immat]);
            if ($check->fetchColumn() > 0) {
                $_SESSION['error'] = "Erreur : L'immatriculation ($immat) existe déjà dans la flotte.";
                redirect('index.php?action=admin_car_form');
                return;
            }

            $stmt = $pdo->prepare("INSERT INTO cars (category_id, immatriculation, marque, modele, type_carburant, boite_vitesse, nb_places, prix_journalier, caution, image_principale, brand_logo, date_assurance, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute($data);
            $car_id = $pdo->lastInsertId();
            $_SESSION['success'] = "Véhicule ajouté.";
        }
    } catch (PDOException $e) {
        // Log de l'erreur pour le développeur
        file_put_contents('debug_log.txt', date('[Y-m-d H:i:s] ') . "Erreur Ajout Voiture: " . $e->getMessage() . " | Data: " . json_encode($_POST) . PHP_EOL, FILE_APPEND);
        
        $_SESSION['error'] = "Erreur lors de l'enregistrement : " . $e->getMessage();
        redirect('index.php?action=admin_car_form' . ($id ? '&id='.$id : ''));
        return;
    }

    // --- GESTION DE LA GALERIE D'IMAGES ---
    if (isset($_FILES['gallery_files']) && !empty($_FILES['gallery_files']['name'][0])) {
        $uploadDir = 'uploads/cars/gallery/';
        if (!is_dir($uploadDir)) mkdir($uploadDir, 0777, true);
        
        foreach ($_FILES['gallery_files']['name'] as $key => $name) {
            if ($_FILES['gallery_files']['error'][$key] == UPLOAD_ERR_OK) {
                $extension = pathinfo($name, PATHINFO_EXTENSION);
                $fileName = time() . '_' . uniqid() . '.' . $extension;
                if (move_uploaded_file($_FILES['gallery_files']['tmp_name'][$key], $uploadDir . $fileName)) {
                    $pdo->prepare("INSERT INTO car_images (car_id, image_path) VALUES (?, ?)")->execute([$car_id, $uploadDir . $fileName]);
                }
            }
        }
    }

    redirect('index.php?action=admin_cars');
}

function admin_car_delete() {
    check_admin();
    $pdo = get_pdo();
    // On vérifie s'il y a des réservations avant de supprimer
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM reservations WHERE car_id = ?");
    $stmt->execute([$_GET['id']]);
    if ($stmt->fetchColumn() > 0) {
        $_SESSION['error'] = "Impossible de supprimer : ce véhicule possède des réservations.";
    } else {
        $stmt = $pdo->prepare("DELETE FROM cars WHERE id = ?");
        $stmt->execute([$_GET['id']]);
        $_SESSION['success'] = "Véhicule supprimé.";
    }
    redirect('index.php?action=admin_cars');
}

function admin_reservations() {
    check_admin();
    $pdo = get_pdo();
    
    $status = $_GET['status'] ?? null;
    $sql = "SELECT r.*, u.nom, u.prenom, u.telephone, c.marque, c.modele, c.immatriculation 
            FROM reservations r 
            JOIN users u ON r.user_id = u.id 
            JOIN cars c ON r.car_id = c.id";
    
    $params = [];
    if ($status) {
        $sql .= " WHERE r.status_reservation = ?";
        $params[] = $status;
    }
    
    $sql .= " ORDER BY r.id DESC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $reservations = $stmt->fetchAll();
    
    render_view('admin/reservations', [
        'reservations' => $reservations, 
        'current_status' => $status,
        'is_admin' => ($_SESSION['user_role'] === 'admin')
    ]);
}

function admin_reservations_export() {
    if ($_SESSION['user_role'] !== 'admin') die("Exportation réservée aux administrateurs");
    $pdo = get_pdo();
    
    $sql = "SELECT r.id, u.nom, u.prenom, c.marque, c.modele, r.date_debut, r.date_fin, r.prix_total, r.status_reservation 
            FROM reservations r 
            JOIN users u ON r.user_id = u.id 
            JOIN cars c ON r.car_id = c.id 
            ORDER BY r.id DESC";
    
    $stmt = $pdo->query($sql);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=reservations_autorent_' . date('Y-m-d') . '.csv');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'Nom', 'Prenom', 'Marque', 'Modele', 'Date Debut', 'Date Fin', 'Total (FCFA)', 'Statut']);
    
    foreach ($rows as $row) {
        fputcsv($output, $row);
    }
    fclose($output);
    exit();
}

function admin_res_update() {
    check_admin();
    $pdo = get_pdo();
    $stmt = $pdo->prepare("UPDATE reservations SET status_reservation = ?, validated_by = ? WHERE id = ?");
    $stmt->execute([$_POST['status'], $_SESSION['user_id'], $_POST['id']]);
    
    // NOTIFICATION POUR LE CLIENT
    $res_stmt = $pdo->prepare("SELECT user_id, status_reservation FROM reservations WHERE id = ?");
    $res_stmt->execute([$_POST['id']]);
    $res_data = $res_stmt->fetch();
    if($res_data) {
        $msg = "Votre réservation est désormais : " . ucfirst($res_data['status_reservation']);
        send_notification($res_data['user_id'], $msg, "info");
    }

    $_SESSION['success'] = "Statut mis à jour.";
    redirect('index.php?action=admin_reservations');
}

function admin_crm() {
    check_admin();
    $pdo = get_pdo();
    $users = $pdo->query("SELECT * FROM users WHERE role = 'client' ORDER BY id DESC")->fetchAll();
    render_view('admin/crm', ['users' => $users]);
}

function admin_crm_blacklist() {
    check_admin();
    $pdo = get_pdo();
    $pdo->prepare("UPDATE users SET is_blacklisted = NOT is_blacklisted WHERE id = ?")->execute([$_GET['id']]);
    redirect('index.php?action=admin_crm');
}

function admin_crm_view() {
    check_admin();
    $pdo = get_pdo();
    $id = (int)$_GET['id'];
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    $client = $stmt->fetch();
    $reservations = $pdo->prepare("SELECT r.*, c.marque, c.modele, c.immatriculation FROM reservations r JOIN cars c ON r.car_id = c.id WHERE r.user_id = ? ORDER BY r.id DESC");
    $reservations->execute([$id]);
    render_view('admin/crm_view', ['client' => $client, 'reservations' => $reservations->fetchAll()]);
}

function admin_maintenance() {
    check_admin();
    $pdo = get_pdo();
    $cars = $pdo->query("SELECT * FROM cars ORDER BY id DESC")->fetchAll();
    render_view('admin/maintenance', ['cars' => $cars]);
}

function admin_checkin() {
    check_admin();
    $pdo = get_pdo();
    // On affiche les réservations validées (prêtes pour départ) ou en cours (prêtes pour retour)
    $reservations = $pdo->query("SELECT r.*, u.nom, u.prenom, c.immatriculation FROM reservations r JOIN users u ON r.user_id = u.id JOIN cars c ON r.car_id = c.id WHERE r.status_reservation IN ('validee', 'en_cours') ORDER BY r.date_debut ASC")->fetchAll();
    render_view('admin/checkin', ['reservations' => $reservations]);
}

function admin_checkout_process() {
    check_admin();
    $pdo = get_pdo();
    $stmt = $pdo->prepare("SELECT r.*, u.nom, u.prenom, c.marque, c.modele, c.immatriculation FROM reservations r JOIN users u ON r.user_id = u.id JOIN cars c ON r.car_id = c.id WHERE r.id = ?");
    $stmt->execute([$_GET['id']]);
    $reservation = $stmt->fetch();
    render_view('admin/checkout_process', ['reservation' => $reservation]);
}

function admin_save_checkout() {
    check_admin();
    $pdo = get_pdo();
    // Mise à jour de la réservation
    $stmt = $pdo->prepare("UPDATE reservations SET kilometrage_depart = ?, niveau_carburant_depart = ?, signature_base64 = ?, status_reservation = 'en_cours', validated_by = ? WHERE id = ?");
    $stmt->execute([$_POST['kilometrage_depart'], $_POST['niveau_carburant_depart'], $_POST['signature_base64'], $_SESSION['user_id'], $_POST['id']]);
    
    // Mise à jour du statut voiture -> LOUÉE
    $res = $pdo->prepare("SELECT car_id FROM reservations WHERE id = ?");
    $res->execute([$_POST['id']]);
    $car_id = $res->fetchColumn();
    $pdo->prepare("UPDATE cars SET status = 'louée' WHERE id = ?")->execute([$car_id]);

    $_SESSION['success'] = "Check-out terminé. Voiture en location.";
    redirect("index.php?action=admin_print_contract&id=".$_POST['id']);
}

function admin_print_contract() {
    check_admin();
    $pdo = get_pdo();
    
    // Récupération de la réservation avec infos client et véhicule de base + info agent (validated_by)
    $stmt = $pdo->prepare("SELECT 
        reservations.*, 
        u.nom, u.prenom, u.email, u.telephone, 
        c.marque, c.modele, c.immatriculation, c.id as car_id,
        agent.nom as agent_nom, agent.prenom as agent_prenom
    FROM reservations
    INNER JOIN users AS u ON reservations.user_id = u.id 
    INNER JOIN cars AS c ON reservations.car_id = c.id 
    LEFT JOIN users AS agent ON reservations.validated_by = agent.id
    WHERE reservations.id = ?");
    $stmt->execute([$_GET['id']]);
    $reservation = $stmt->fetch();

    // Récupération des détails complets du véhicule (pour la caution, etc.)
    $car_stmt = $pdo->prepare("SELECT * FROM cars WHERE id = ?");
    $car_stmt->execute([$reservation['car_id']]);
    $car = $car_stmt->fetch();

    render_view('admin/contract_print', ['reservation' => $reservation, 'car' => $car]);
}

function admin_gantt() {
    check_admin();
    $pdo = get_pdo();
    $cars = $pdo->query("SELECT id, immatriculation, marque, modele FROM cars ORDER BY marque ASC")->fetchAll();
    $month = date('m'); $year = date('Y');
    $reservations = $pdo->prepare("SELECT r.*, u.nom, u.prenom FROM reservations r JOIN users u ON r.user_id = u.id WHERE (MONTH(r.date_debut) = ? OR MONTH(r.date_fin) = ?) AND YEAR(r.date_debut) = ?");
    $reservations->execute([$month, $month, $year]);
    render_view('admin/gantt', ['cars' => $cars, 'reservations' => $reservations->fetchAll()]);
}

function admin_profile() {
    check_admin();
    $pdo = get_pdo();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();
    render_view('admin/profile', ['user' => $user]);
}

// --- GESTION DU PERSONNEL (STAFF) ---

function admin_staff() {
    if ($_SESSION['user_role'] !== 'admin') die("Accès réservé aux administrateurs");
    $pdo = get_pdo();
    $staff = $pdo->query("SELECT * FROM users WHERE role IN ('admin', 'employee') ORDER BY role ASC")->fetchAll();
    render_view('admin/staff', ['staff' => $staff]);
}

function admin_staff_form() {
    check_admin();
    $pdo = get_pdo();
    $id = $_GET['id'] ?? null;
    $member = null;
    if ($id) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $member = $stmt->fetch();
    }
    render_view('admin/staff_form', ['member' => $member]);
}

function admin_staff_save() {
    if ($_SESSION['user_role'] !== 'admin') die("Action non autorisée");
    $pdo = get_pdo();
    $id = $_POST['id'] ?? null;
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $telephone = $_POST['telephone'];

    if ($id) {
        if (!empty($_POST['password'])) {
            $pass = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET nom=?, prenom=?, email=?, role=?, telephone=?, password=? WHERE id=?");
            $stmt->execute([$nom, $prenom, $email, $role, $telephone, $pass, $id]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET nom=?, prenom=?, email=?, role=?, telephone=? WHERE id=?");
            $stmt->execute([$nom, $prenom, $email, $role, $telephone, $id]);
        }
        $_SESSION['success'] = "Membre du personnel mis à jour.";
    } else {
        // --- CHECK SI EMAIL EXISTE DEJA ---
        $check = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $check->execute([$email]);
        if ($check->fetchColumn() > 0) {
            $_SESSION['error'] = "Erreur : Cet email ($email) est déjà utilisé par un autre membre.";
            redirect('index.php?action=admin_staff_form');
            return;
        }

        $pass = password_hash($_POST['password'] ?: '123456', PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, role, telephone, password) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->execute([$nom, $prenom, $email, $role, $telephone, $pass]);
        $_SESSION['success'] = "Nouveau membre ajouté.";
    }
    redirect('index.php?action=admin_staff');
}

function admin_staff_delete() {
    check_admin();
    $pdo = get_pdo();
    if ($_GET['id'] == $_SESSION['user_id']) {
        $_SESSION['error'] = "Vous ne pouvez pas vous supprimer vous-même.";
    } else {
        $pdo->prepare("DELETE FROM users WHERE id = ?")->execute([$_GET['id']]);
        $_SESSION['success'] = "Membre supprimé.";
    }
    redirect('index.php?action=admin_staff');
}
