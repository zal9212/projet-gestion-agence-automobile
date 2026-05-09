<?php
$files = [
    'app/views/admin/reservations.php' => <<<'PHP'
<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h2 class="fw-bold mb-1">Bookings Management</h2>
        <p class="text-muted">Manage all customer reservations from here.</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-dark rounded-pill px-4"><i class="fa-solid fa-filter me-2"></i> Filter</button>
        <button class="btn btn-dark rounded-pill px-4"><i class="fa-solid fa-download me-2"></i> Export</button>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase" style="letter-spacing: 1px;">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Customer</th>
                        <th class="py-3">Vehicle</th>
                        <th class="py-3">Dates</th>
                        <th class="py-3">Amount</th>
                        <th class="py-3">Driver</th>
                        <th class="py-3">Status</th>
                        <th class="pe-4 py-3 text-end">Action</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php foreach($reservations as $r): ?>
                    <tr>
                        <td class="ps-4 fw-bold text-muted">#<?= $r['id'] ?></td>
                        <td>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($r['nom'].' '.$r['prenom']) ?></div>
                            <div class="text-muted small"><i class="fa-solid fa-phone me-1"></i> <?= htmlspecialchars($r['telephone']) ?></div>
                        </td>
                        <td>
                            <div class="fw-medium text-dark"><?= htmlspecialchars($r['marque']) ?></div>
                            <div class="text-muted small"><?= htmlspecialchars($r['immatriculation']) ?></div>
                        </td>
                        <td>
                            <div class="small fw-medium text-dark"><?= date('M d, Y', strtotime($r['date_debut'])) ?></div>
                            <div class="text-muted small">&rarr; <?= date('M d, Y', strtotime($r['date_fin'])) ?></div>
                        </td>
                        <td class="fw-bold text-success"><?= number_format($r['prix_total'], 0, ',', ' ') ?> €</td>
                        <td>
                            <?php if($r['avec_chauffeur']): ?>
                                <span class="badge bg-dark rounded-pill"><i class="fa-solid fa-user-tie me-1"></i> Yes</span>
                            <?php else: ?>
                                <span class="text-muted small">No</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form action="index.php?action=admin_res_update" method="POST" class="d-flex align-items-center">
                                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                <?php 
                                $status_color = match($r['status_reservation']) {
                                    'en_attente' => 'text-warning',
                                    'validee' => 'text-success',
                                    'en_cours' => 'text-primary',
                                    'terminee' => 'text-secondary',
                                    'annulee' => 'text-danger',
                                    default => 'text-dark'
                                };
                                ?>
                                <select name="status" class="form-select form-select-sm rounded-pill border-0 bg-light fw-bold <?= $status_color ?>" style="width: 140px; cursor:pointer;" onchange="this.form.submit()">
                                    <option value="en_attente" <?= $r['status_reservation']=='en_attente'?'selected':'' ?>>En attente</option>
                                    <option value="validee" <?= $r['status_reservation']=='validee'?'selected':'' ?>>Validée</option>
                                    <option value="en_cours" <?= $r['status_reservation']=='en_cours'?'selected':'' ?>>En cours</option>
                                    <option value="terminee" <?= $r['status_reservation']=='terminee'?'selected':'' ?>>Terminée</option>
                                    <option value="annulee" <?= $r['status_reservation']=='annulee'?'selected':'' ?>>Annulée</option>
                                </select>
                            </form>
                        </td>
                        <td class="pe-4 text-end">
                            <button class="btn btn-light btn-sm rounded-circle shadow-sm text-primary" style="width: 35px; height: 35px;" title="Print Contract"><i class="fa-solid fa-print"></i></button>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>
PHP,

    'app/views/admin/cars.php' => <<<'PHP'
<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h2 class="fw-bold mb-1">Fleet Management</h2>
        <p class="text-muted">Manage your vehicles, pricing, and availability.</p>
    </div>
    <a href="index.php?action=admin_car_form" class="btn btn-dark rounded-pill px-4"><i class="fa-solid fa-plus me-2"></i> Add New Car</a>
</div>

<div class="row g-4">
    <?php foreach($cars as $car): ?>
    <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden position-relative">
            <!-- Status Badge -->
            <div class="position-absolute top-0 start-0 m-3 z-index-10">
                <?php if($car['status'] == 'disponible'): ?>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 border border-success border-opacity-25"><i class="fa-solid fa-circle me-1" style="font-size:8px;"></i> Available</span>
                <?php elseif($car['status'] == 'maintenance'): ?>
                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 border border-warning border-opacity-25"><i class="fa-solid fa-wrench me-1"></i> Maintenance</span>
                <?php else: ?>
                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 border border-danger border-opacity-25"><i class="fa-solid fa-lock me-1"></i> Rented</span>
                <?php endif; ?>
            </div>
            
            <div class="bg-light p-4 d-flex justify-content-center align-items-center" style="height: 180px;">
                <img src="<?= htmlspecialchars($car['image_principale']) ?>" alt="" style="max-height: 120px; max-width: 100%; object-fit: contain;">
            </div>
            
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <span class="text-muted small fw-bold"><?= htmlspecialchars($car['immatriculation']) ?></span>
                    <span class="badge bg-light text-dark border"><?= htmlspecialchars($car['cat_nom']) ?></span>
                </div>
                <h5 class="fw-bold mb-3 text-dark"><?= htmlspecialchars($car['marque'].' '.$car['modele']) ?></h5>
                
                <div class="d-flex gap-3 mb-4 text-muted small">
                    <div><i class="fa-solid fa-gas-pump me-1"></i> <?= htmlspecialchars($car['type_carburant']) ?></div>
                    <div><i class="fa-solid fa-gears me-1"></i> <?= htmlspecialchars($car['boite_vitesse']) ?></div>
                </div>
                
                <div class="d-flex justify-content-between align-items-end border-top pt-3">
                    <div>
                        <span class="text-muted small d-block" style="line-height:1;">Daily Rate</span>
                        <h5 class="text-success fw-bold mb-0 mt-1"><?= $car['prix_journalier'] ?> €</h5>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="index.php?action=admin_car_form&id=<?= $car['id'] ?>" class="btn btn-light rounded-circle shadow-sm text-primary" style="width: 38px; height: 38px; display:flex; align-items:center; justify-content:center;"><i class="fa-solid fa-pen"></i></a>
                        <a href="index.php?action=admin_car_delete&id=<?= $car['id'] ?>" class="btn btn-light rounded-circle shadow-sm text-danger" style="width: 38px; height: 38px; display:flex; align-items:center; justify-content:center;" onclick="return confirm('Delete this vehicle?');"><i class="fa-solid fa-trash"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>
PHP,

    'app/views/admin/car_form.php' => <<<'PHP'
<?php ob_start(); 
$isEdit = isset($car) && $car;
?>
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <a href="index.php?action=admin_cars" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="fa-solid fa-arrow-left me-2"></i> Back to Fleet</a>
        <h2 class="fw-bold mb-1"><?= $isEdit ? 'Edit Vehicle' : 'Add New Vehicle' ?></h2>
        <p class="text-muted">Fill in the details below to update the fleet.</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-5">
            <form action="index.php?action=admin_car_save" method="POST">
                <?php if($isEdit): ?>
                    <input type="hidden" name="id" value="<?= $car['id'] ?>">
                <?php endif; ?>
                
                <h5 class="fw-bold mb-4">General Information</h5>
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">License Plate</label>
                        <input type="text" name="immatriculation" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $isEdit ? htmlspecialchars($car['immatriculation']) : '' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Category</label>
                        <select name="category_id" class="form-select form-select-lg bg-light border-0 rounded-pill px-4" required>
                            <?php foreach($categories as $cat): ?>
                                <option value="<?= $cat['id'] ?>" <?= ($isEdit && $car['category_id'] == $cat['id']) ? 'selected' : '' ?>><?= htmlspecialchars($cat['nom']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Brand</label>
                        <input type="text" name="marque" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $isEdit ? htmlspecialchars($car['marque']) : '' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Model</label>
                        <input type="text" name="modele" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $isEdit ? htmlspecialchars($car['modele']) : '' ?>" required>
                    </div>
                </div>

                <h5 class="fw-bold mb-4">Specifications</h5>
                <div class="row g-4 mb-5">
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted small ms-2">Fuel Type</label>
                        <select name="type_carburant" class="form-select form-select-lg bg-light border-0 rounded-pill px-4">
                            <?php $fuels = ['Essence', 'Diesel', 'Electrique', 'Hybride']; foreach($fuels as $f): ?>
                                <option value="<?= $f ?>" <?= ($isEdit && $car['type_carburant'] == $f) ? 'selected' : '' ?>><?= $f ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted small ms-2">Transmission</label>
                        <select name="boite_vitesse" class="form-select form-select-lg bg-light border-0 rounded-pill px-4">
                            <option value="Manuelle" <?= ($isEdit && $car['boite_vitesse'] == 'Manuelle') ? 'selected' : '' ?>>Manual</option>
                            <option value="Automatique" <?= ($isEdit && $car['boite_vitesse'] == 'Automatique') ? 'selected' : '' ?>>Automatic</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-bold text-muted small ms-2">Seats</label>
                        <input type="number" name="nb_places" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $isEdit ? htmlspecialchars($car['nb_places']) : '5' ?>">
                    </div>
                </div>

                <h5 class="fw-bold mb-4">Pricing & Details</h5>
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Daily Price (€)</label>
                        <input type="number" step="0.01" name="prix_journalier" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $isEdit ? htmlspecialchars($car['prix_journalier']) : '' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Security Deposit (€)</label>
                        <input type="number" step="0.01" name="caution" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $isEdit ? htmlspecialchars($car['caution']) : '0' ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Image URL (Main)</label>
                        <input type="text" name="image_principale" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $isEdit ? htmlspecialchars($car['image_principale']) : '' ?>" placeholder="https://...">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Status</label>
                        <select name="status" class="form-select form-select-lg bg-light border-0 rounded-pill px-4">
                            <option value="disponible" <?= ($isEdit && $car['status'] == 'disponible') ? 'selected' : '' ?>>Available</option>
                            <option value="maintenance" <?= ($isEdit && $car['status'] == 'maintenance') ? 'selected' : '' ?>>Maintenance</option>
                            <option value="louée" <?= ($isEdit && $car['status'] == 'louée') ? 'selected' : '' ?>>Rented</option>
                        </select>
                    </div>
                </div>

                <div class="text-end">
                    <a href="index.php?action=admin_cars" class="btn btn-light btn-lg rounded-pill px-5 fw-bold me-2">Cancel</a>
                    <button type="submit" class="btn btn-dark btn-lg rounded-pill px-5 fw-bold"><?= $isEdit ? 'Save Changes' : 'Add Vehicle' ?></button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>
PHP
];

foreach ($files as $filepath => $content) {
    file_put_contents($filepath, $content);
}

// Update index.php and AdminController to support Add/Edit/Delete Car
$index_content = <<<'PHP'
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
    case 'admin_car_form': AdminController::carForm(); break;
    case 'admin_car_save': AdminController::carSave(); break;
    case 'admin_car_delete': AdminController::carDelete(); break;
    
    case 'admin_reservations': AdminController::reservations(); break;
    case 'admin_res_update': AdminController::updateReservationStatus(); break;
    
    default: echo "404 Not Found"; break;
}
PHP;
file_put_contents('index.php', $index_content);

$admin_controller_content = <<<'PHP'
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
}
PHP;
file_put_contents('app/controllers/AdminController.php', $admin_controller_content);
echo "Admin pages harmonisées et completées.";
?>
