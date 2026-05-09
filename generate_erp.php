<?php
require_once 'app/config.php';
$pdo = getPDO();

// 1. MISE À JOUR DE LA BASE DE DONNÉES (Ignorer les erreurs si les colonnes existent déjà)
$queries = [
    "ALTER TABLE cars ADD COLUMN vin VARCHAR(50) NULL;",
    "ALTER TABLE cars ADD COLUMN kilometrage INT DEFAULT 0;",
    "ALTER TABLE cars ADD COLUMN date_assurance DATE NULL;",
    "ALTER TABLE cars ADD COLUMN date_visite_technique DATE NULL;",
    "ALTER TABLE users ADD COLUMN is_blacklisted BOOLEAN DEFAULT FALSE;",
    "ALTER TABLE users ADD COLUMN permis_url VARCHAR(255) NULL;",
    "ALTER TABLE users ADD COLUMN piece_identite_url VARCHAR(255) NULL;",
    "ALTER TABLE reservations ADD COLUMN kilometrage_depart INT NULL;",
    "ALTER TABLE reservations ADD COLUMN kilometrage_retour INT NULL;",
    "ALTER TABLE reservations ADD COLUMN caution_status VARCHAR(20) DEFAULT 'en_attente';",
    "CREATE TABLE IF NOT EXISTS maintenance (
        id INT AUTO_INCREMENT PRIMARY KEY,
        car_id INT,
        type_intervention VARCHAR(100),
        cout DECIMAL(10,2),
        date_intervention DATE,
        FOREIGN KEY (car_id) REFERENCES cars(id)
    );",
    "CREATE TABLE IF NOT EXISTS factures (
        id INT AUTO_INCREMENT PRIMARY KEY,
        reservation_id INT,
        numero_facture VARCHAR(50),
        montant DECIMAL(10,2),
        status VARCHAR(20) DEFAULT 'non_payee',
        date_creation DATETIME DEFAULT CURRENT_TIMESTAMP,
        FOREIGN KEY (reservation_id) REFERENCES reservations(id)
    );"
];

foreach ($queries as $sql) {
    try {
        $pdo->exec($sql);
    } catch(PDOException $e) {
        // Continue silently if column already exists
    }
}

// 2. MISE À JOUR DU MENU ADMIN (layouts/admin.php)
$admin_layout = <<<'PHP'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoRent ERP Workspace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f4f7f6; color: #1a1a1a; }
        .sidebar { min-height: 100vh; background: #ffffff; border-right: 1px solid #eee; padding-top: 30px; position: sticky; top: 0; overflow-y: auto;}
        .sidebar a { color: #666; text-decoration: none; padding: 12px 30px; display: flex; align-items: center; font-weight: 500; transition: 0.2s; border-right: 4px solid transparent; font-size: 0.95rem; }
        .sidebar a:hover, .sidebar a.active { color: #1a1a1a; background: #fbfbfb; border-right: 4px solid #f4c053; font-weight: 600; }
        .sidebar i { width: 25px; font-size: 1.1rem; }
        .card { border: none; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar" style="width: 280px; flex-shrink: 0;">
            <div class="text-center mb-4 px-4">
                <h3 class="fw-bold text-dark"><i class="fa-solid fa-car-side" style="color: #f4c053;"></i> AutoRent</h3>
                <span class="badge bg-dark text-warning rounded-pill mt-1">ERP Pro Edition</span>
            </div>
            
            <p class="text-muted small fw-bold px-4 mb-2 ms-2 mt-4" style="letter-spacing: 1px;">CORE BUSINESS</p>
            <a href="index.php?action=admin_dashboard" class="<?= (isset($_GET['action']) && $_GET['action']=='admin_dashboard')?'active':'' ?>"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            <a href="index.php?action=admin_reservations" class="<?= (isset($_GET['action']) && $_GET['action']=='admin_reservations')?'active':'' ?>"><i class="fa-solid fa-calendar-check"></i> Bookings</a>
            <a href="index.php?action=admin_cars" class="<?= (isset($_GET['action']) && strpos($_GET['action'], 'admin_car')!==false)?'active':'' ?>"><i class="fa-solid fa-car"></i> Fleet & Vehicles</a>
            
            <p class="text-muted small fw-bold px-4 mb-2 ms-2 mt-4" style="letter-spacing: 1px;">OPERATIONS</p>
            <a href="index.php?action=admin_checkin" class="<?= (isset($_GET['action']) && $_GET['action']=='admin_checkin')?'active':'' ?>"><i class="fa-solid fa-clipboard-check"></i> Check-in / Out</a>
            <a href="index.php?action=admin_maintenance" class="<?= (isset($_GET['action']) && $_GET['action']=='admin_maintenance')?'active':'' ?>"><i class="fa-solid fa-wrench"></i> Maintenance</a>
            
            <p class="text-muted small fw-bold px-4 mb-2 ms-2 mt-4" style="letter-spacing: 1px;">FINANCE & CLIENTS</p>
            <a href="index.php?action=admin_crm" class="<?= (isset($_GET['action']) && $_GET['action']=='admin_crm')?'active':'' ?>"><i class="fa-solid fa-users"></i> CRM & Customers</a>
            <a href="#"><i class="fa-solid fa-file-invoice-dollar"></i> Invoices</a>
            
            <p class="text-muted small fw-bold px-4 mt-5 mb-2 ms-2">OTHER</p>
            <a href="index.php" class="text-primary"><i class="fa-solid fa-globe"></i> View Website</a>
            <a href="index.php?action=logout" class="text-danger mt-2"><i class="fa-solid fa-power-off"></i> Logout</a>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-5 overflow-auto" style="height: 100vh;">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div class="search-bar w-50 bg-white rounded-pill px-4 py-2 d-flex align-items-center shadow-sm border">
                    <i class="fa-solid fa-magnifying-glass text-muted me-3"></i>
                    <input type="text" class="border-0 bg-transparent w-100" placeholder="Search VIN, customers, bookings..." style="outline:none;">
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm border" style="width: 45px; height: 45px;"><i class="fa-regular fa-bell"></i></div>
                    <img src="https://ui-avatars.com/api/?name=Admin&background=1a1a1a&color=fff" class="rounded-circle shadow-sm" style="width: 45px;">
                </div>
            </div>
            <?= $content ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
PHP;
file_put_contents('app/views/layouts/admin.php', $admin_layout);

// 3. NOUVELLES VUES (CRM, Maintenance, Check-in)
$crm_view = <<<'PHP'
<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-5">
    <div><h2 class="fw-bold mb-1">CRM & Customers</h2><p class="text-muted">Manage your client base, KYC documents and blacklists.</p></div>
</div>
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light text-muted small text-uppercase">
            <tr><th class="ps-4 py-3">Client</th><th class="py-3">Contact</th><th class="py-3">KYC Docs</th><th class="py-3">Status</th><th class="pe-4 py-3 text-end">Action</th></tr>
        </thead>
        <tbody class="border-top-0">
            <?php foreach($users as $u): ?>
            <tr>
                <td class="ps-4 fw-bold text-dark"><?= htmlspecialchars($u['nom'].' '.$u['prenom']) ?></td>
                <td class="text-muted small"><i class="fa-solid fa-envelope me-1"></i> <?= htmlspecialchars($u['email']) ?><br><i class="fa-solid fa-phone me-1"></i> <?= htmlspecialchars($u['telephone'] ?? 'N/A') ?></td>
                <td><span class="badge bg-warning text-dark"><i class="fa-solid fa-clock me-1"></i> Pending Review</span></td>
                <td>
                    <?php if($u['is_blacklisted']): ?>
                        <span class="badge bg-danger">Blacklisted</span>
                    <?php else: ?>
                        <span class="badge bg-success bg-opacity-10 text-success">Active</span>
                    <?php endif; ?>
                </td>
                <td class="pe-4 text-end">
                    <button class="btn btn-light btn-sm rounded-circle"><i class="fa-solid fa-ban text-danger"></i></button>
                    <button class="btn btn-light btn-sm rounded-circle"><i class="fa-solid fa-eye text-primary"></i></button>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>
PHP;
file_put_contents('app/views/admin/crm.php', $crm_view);

$maintenance_view = <<<'PHP'
<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-5">
    <div><h2 class="fw-bold mb-1">Fleet Maintenance</h2><p class="text-muted">Track oil changes, technical visits and insurances.</p></div>
    <button class="btn btn-dark rounded-pill px-4"><i class="fa-solid fa-plus me-2"></i> Log Maintenance</button>
</div>
<div class="row g-4">
    <?php foreach($cars as $car): ?>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0"><?= htmlspecialchars($car['immatriculation']) ?></h5>
                <span class="badge bg-light text-dark border"><?= htmlspecialchars($car['marque'].' '.$car['modele']) ?></span>
            </div>
            <p class="text-muted small mb-4"><i class="fa-solid fa-gauge-high me-2"></i> <?= number_format($car['kilometrage'], 0, ',', ' ') ?> km</p>
            
            <div class="d-flex justify-content-between text-muted small mb-2 border-bottom pb-2">
                <span>Insurance Exp.</span>
                <strong class="<?= (!$car['date_assurance'] || strtotime($car['date_assurance']) < time()) ? 'text-danger' : 'text-success' ?>">
                    <?= $car['date_assurance'] ? date('M d, Y', strtotime($car['date_assurance'])) : 'Missing' ?>
                </strong>
            </div>
            <div class="d-flex justify-content-between text-muted small border-bottom pb-2">
                <span>Next Tech Visit</span>
                <strong class="text-warning">Need update</strong>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>
PHP;
file_put_contents('app/views/admin/maintenance.php', $maintenance_view);

$checkin_view = <<<'PHP'
<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-5">
    <div><h2 class="fw-bold mb-1">Check-in / Check-out Desk</h2><p class="text-muted">Process vehicle handovers and returns in real-time.</p></div>
</div>
<div class="alert alert-warning border-0 rounded-4 shadow-sm p-4 d-flex align-items-center">
    <i class="fa-solid fa-tablet-screen-button fa-2x me-3"></i>
    <div>
        <h6 class="fw-bold mb-1">Tablet Mode Activated</h6>
        <p class="mb-0 small text-dark">Use this interface at the agency counter to verify mileage, fuel, and capture customer signatures.</p>
    </div>
</div>
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light text-muted small text-uppercase">
            <tr><th class="ps-4 py-3">Reservation</th><th class="py-3">Vehicle</th><th class="py-3">Client</th><th class="py-3">Action Required</th><th class="pe-4 py-3 text-end">Action</th></tr>
        </thead>
        <tbody class="border-top-0">
            <?php foreach($reservations as $r): ?>
            <tr>
                <td class="ps-4 fw-bold">#<?= $r['id'] ?> <br><small class="text-muted fw-normal"><?= date('d/m', strtotime($r['date_debut'])) ?> &rarr; <?= date('d/m', strtotime($r['date_fin'])) ?></small></td>
                <td><span class="badge bg-dark text-white rounded-pill px-3"><?= htmlspecialchars($r['immatriculation']) ?></span></td>
                <td><?= htmlspecialchars($r['nom'].' '.$r['prenom']) ?></td>
                <td>
                    <?php if($r['status_reservation'] == 'validee'): ?>
                        <span class="badge bg-primary rounded-pill"><i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Ready for Check-out</span>
                    <?php elseif($r['status_reservation'] == 'en_cours'): ?>
                        <span class="badge bg-success rounded-pill"><i class="fa-solid fa-arrow-right-to-bracket me-1"></i> Awaiting Check-in</span>
                    <?php else: ?>
                        <span class="text-muted small">No action</span>
                    <?php endif; ?>
                </td>
                <td class="pe-4 text-end">
                    <?php if($r['status_reservation'] == 'validee' || $r['status_reservation'] == 'en_cours'): ?>
                        <button class="btn btn-warning btn-sm rounded-pill px-3 fw-bold">Process</button>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>
PHP;
file_put_contents('app/views/admin/checkin.php', $checkin_view);

// 4. MISE À JOUR DE AdminController.php
$controller_additions = <<<'PHP'
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
}
PHP;

$admin_controller = file_get_contents('app/controllers/AdminController.php');
// Remove the last closing brace } and append new methods
$admin_controller = substr(trim($admin_controller), 0, -1) . "\n" . $controller_additions;
file_put_contents('app/controllers/AdminController.php', $admin_controller);

// 5. MISE À JOUR DE index.php pour inclure les nouvelles routes
$index_php = file_get_contents('index.php');
$routes = "    case 'admin_crm': AdminController::crm(); break;\n    case 'admin_maintenance': AdminController::maintenance(); break;\n    case 'admin_checkin': AdminController::checkin(); break;\n    default:";
$index_php = str_replace('    default:', $routes, $index_php);
file_put_contents('index.php', $index_php);

echo "Base de données mise à jour et modules ERP générés avec succès.";
?>
