<?php
require_once 'app/config.php';
$pdo = getPDO();

// 1. Ajouter la colonne pour sauvegarder la signature tactile
try {
    $pdo->exec("ALTER TABLE reservations ADD COLUMN signature_base64 LONGTEXT NULL;");
} catch(PDOException $e) {}

$files = [
    // 2. LA VUE DU PROCESSUS DE CHECK-OUT (Remise des clés)
    'app/views/admin/checkout_process.php' => <<<'PHP'
<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="index.php?action=admin_checkin" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="fa-solid fa-arrow-left me-2"></i> Back to Desk</a>
        <h2 class="fw-bold mb-1">Vehicle Handover (Check-out)</h2>
        <p class="text-muted">Fill out the departure conditions and ask the client to sign.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <!-- Infos Réservation -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h5 class="fw-bold mb-3">Booking #<?= $reservation['id'] ?></h5>
            <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                <div class="bg-light rounded p-3 me-3"><i class="fa-solid fa-car text-dark fa-2x"></i></div>
                <div>
                    <h6 class="fw-bold mb-0"><?= htmlspecialchars($reservation['marque'] . ' ' . $reservation['modele']) ?></h6>
                    <span class="badge bg-dark text-white rounded-pill"><?= htmlspecialchars($reservation['immatriculation']) ?></span>
                </div>
            </div>
            <p class="text-muted small mb-1">Client</p>
            <h6 class="fw-bold mb-3"><?= htmlspecialchars($reservation['nom'] . ' ' . $reservation['prenom']) ?></h6>
            <p class="text-muted small mb-1">Rental Period</p>
            <h6 class="fw-bold mb-0"><?= date('d/m/Y', strtotime($reservation['date_debut'])) ?> &rarr; <?= date('d/m/Y', strtotime($reservation['date_fin'])) ?></h6>
        </div>
    </div>

    <div class="col-lg-8">
        <form action="index.php?action=admin_save_checkout" method="POST" id="checkoutForm">
            <input type="hidden" name="id" value="<?= $reservation['id'] ?>">
            <input type="hidden" name="signature_base64" id="signature_data">
            
            <div class="card border-0 shadow-sm rounded-4 p-5 mb-4">
                <h5 class="fw-bold mb-4">1. Departure Conditions</h5>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Departure Mileage (km)</label>
                        <input type="number" name="kilometrage_depart" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $car['kilometrage'] ?? 0 ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Fuel Level</label>
                        <select name="niveau_carburant_depart" class="form-select form-select-lg bg-light border-0 rounded-pill px-4" required>
                            <option value="1/4">1/4 (Low)</option>
                            <option value="1/2">1/2 (Half)</option>
                            <option value="3/4">3/4</option>
                            <option value="Plein" selected>Full Tank</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-5 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">2. Client Signature</h5>
                    <button type="button" class="btn btn-light btn-sm rounded-pill px-3" onclick="clearSignature()"><i class="fa-solid fa-eraser me-2"></i> Clear</button>
                </div>
                <div class="border rounded-4 bg-light p-2 mb-3" style="touch-action: none;">
                    <canvas id="signaturePad" width="600" height="200" style="width: 100%; border-radius: 15px; background: white; cursor: crosshair;"></canvas>
                </div>
                <p class="text-muted small text-center mb-0">I confirm the vehicle conditions stated above.</p>
            </div>

            <button type="submit" class="btn btn-success btn-lg w-100 fw-bold rounded-pill shadow" onclick="return saveSignature()"><i class="fa-solid fa-check-circle me-2"></i> Confirm Check-out & Generate Contract</button>
        </form>
    </div>
</div>

<script>
    // Logique de dessin pour la signature
    const canvas = document.getElementById('signaturePad');
    const ctx = canvas.getContext('2d');
    let isDrawing = false;
    
    // Ajuster la taille réelle du canvas pour qu'il soit net
    canvas.width = canvas.offsetWidth;
    ctx.lineWidth = 3;
    ctx.lineCap = 'round';
    ctx.strokeStyle = '#1a1a1a';

    function getMousePos(canvas, evt) {
        var rect = canvas.getBoundingClientRect();
        return {
            x: (evt.clientX || evt.touches[0].clientX) - rect.left,
            y: (evt.clientY || evt.touches[0].clientY) - rect.top
        };
    }

    const startDrawing = (e) => { isDrawing = true; draw(e); };
    const stopDrawing = () => { isDrawing = false; ctx.beginPath(); };
    const draw = (e) => {
        if (!isDrawing) return;
        e.preventDefault();
        const pos = getMousePos(canvas, e);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
    };

    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);

    // Support tactile (tablette/mobile)
    canvas.addEventListener('touchstart', startDrawing);
    canvas.addEventListener('touchmove', draw);
    canvas.addEventListener('touchend', stopDrawing);

    function clearSignature() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }

    function saveSignature() {
        // Exiger que le canvas ne soit pas vide (basique)
        const blank = document.createElement('canvas');
        blank.width = canvas.width; blank.height = canvas.height;
        if(canvas.toDataURL() == blank.toDataURL()) {
            alert("Veuillez demander au client de signer.");
            return false;
        }
        document.getElementById('signature_data').value = canvas.toDataURL();
        return true;
    }
</script>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>
PHP,

    // 3. LA VUE DU CONTRAT PDF (Version Impression HTML)
    'app/views/admin/contract_print.php' => <<<'PHP'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Rental Contract #<?= $reservation['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background: #fff; color: #000; padding: 40px; }
        .contract-header { border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 30px; }
        .section-title { background: #f0f0f0; padding: 5px 10px; font-weight: bold; margin-top: 30px; margin-bottom: 15px; border-left: 4px solid #000; }
        table th { background-color: #f9f9f9; }
        .signature-box { border: 1px solid #ccc; height: 150px; text-align: center; margin-top: 20px; }
        .signature-img { max-height: 140px; max-width: 100%; object-fit: contain; }
        @media print {
            body { padding: 0; }
            button { display: none !important; }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="btn btn-dark float-end mb-4"><i class="fa-solid fa-print"></i> Print Contract</button>
    
    <div class="contract-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-0">AutoRent Agency</h2>
            <p class="mb-0 text-muted">123 Business Road, City Center<br>contact@autorent.com | +1 234 567 890</p>
        </div>
        <div class="text-end">
            <h3 class="mb-1">RENTAL AGREEMENT</h3>
            <p class="mb-0 fw-bold">Contract #: AR-<?= date('Y') ?>-<?= str_pad($reservation['id'], 5, "0", STR_PAD_LEFT) ?></p>
            <p class="mb-0">Date: <?= date('d/m/Y') ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="section-title">LESSEE (CLIENT) DETAILS</div>
            <p><strong>Name:</strong> <?= htmlspecialchars($reservation['nom'].' '.$reservation['prenom']) ?><br>
            <strong>Email:</strong> <?= htmlspecialchars($reservation['email']) ?><br>
            <strong>Phone:</strong> <?= htmlspecialchars($reservation['telephone'] ?? 'N/A') ?></p>
        </div>
        <div class="col-6">
            <div class="section-title">VEHICLE DETAILS</div>
            <p><strong>Make & Model:</strong> <?= htmlspecialchars($reservation['marque'].' '.$reservation['modele']) ?><br>
            <strong>License Plate:</strong> <?= htmlspecialchars($reservation['immatriculation']) ?><br>
            <strong>VIN:</strong> <?= htmlspecialchars($car['vin'] ?? 'N/A') ?></p>
        </div>
    </div>

    <div class="section-title">RENTAL PERIOD & CONDITIONS</div>
    <table class="table table-bordered">
        <tr>
            <th width="25%">Pick-up Date</th><td><?= date('d/m/Y H:i', strtotime($reservation['date_debut'])) ?></td>
            <th width="25%">Return Date</th><td><?= date('d/m/Y H:i', strtotime($reservation['date_fin'])) ?></td>
        </tr>
        <tr>
            <th>Departure Mileage</th><td><?= $reservation['kilometrage_depart'] ?> km</td>
            <th>Fuel Level</th><td><?= htmlspecialchars($reservation['niveau_carburant_depart']) ?></td>
        </tr>
    </table>

    <div class="section-title">FINANCIAL SUMMARY</div>
    <table class="table table-bordered">
        <tr><th>Rental Total Amount</th><td class="text-end fw-bold"><?= number_format($reservation['prix_total'], 2, '.', ' ') ?> €</td></tr>
        <tr><th>Security Deposit (Authorized)</th><td class="text-end"><?= number_format($car['caution'], 2, '.', ' ') ?> €</td></tr>
    </table>

    <div class="section-title">TERMS & SIGNATURES</div>
    <p class="small text-muted">The lessee acknowledges receiving the vehicle in the condition described above and agrees to the standard terms and conditions of AutoRent. The vehicle must be returned at the agreed date and time. Any traffic violations or damages incurred during the rental period are the sole responsibility of the lessee.</p>

    <div class="row mt-5">
        <div class="col-6">
            <p class="fw-bold text-center">Agency Representative</p>
            <div class="signature-box d-flex align-items-center justify-content-center text-muted">AutoRent Authorized</div>
        </div>
        <div class="col-6">
            <p class="fw-bold text-center">Lessee Signature</p>
            <div class="signature-box border-0">
                <?php if($reservation['signature_base64']): ?>
                    <img src="<?= $reservation['signature_base64'] ?>" class="signature-img">
                <?php else: ?>
                    <div class="text-muted mt-5">Not signed yet</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
PHP
];

foreach ($files as $filepath => $content) {
    file_put_contents($filepath, $content);
}

// 4. Mettre à jour AdminController
$controller_additions = <<<'PHP'
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
PHP;

$admin_controller = file_get_contents('app/controllers/AdminController.php');
// Remove the last closing brace } and append new methods
$admin_controller = substr(trim($admin_controller), 0, -1) . "\n" . $controller_additions . "\n}";
file_put_contents('app/controllers/AdminController.php', $admin_controller);

// 5. Mettre à jour index.php (Routes)
$index_php = file_get_contents('index.php');
$routes = "    case 'admin_checkout_process': AdminController::checkoutProcess(); break;\n    case 'admin_save_checkout': AdminController::saveCheckout(); break;\n    case 'admin_print_contract': AdminController::printContract(); break;\n    default:";
$index_php = str_replace('    default:', $routes, $index_php);
file_put_contents('index.php', $index_php);

// 6. Mettre à jour le bouton dans admin_checkin.php
$checkin_file = 'app/views/admin/checkin.php';
$checkin_content = file_get_contents($checkin_file);
$checkin_content = str_replace(
    '<button class="btn btn-warning btn-sm rounded-pill px-3 fw-bold">Process</button>',
    '<a href="index.php?action=admin_checkout_process&id=<?= $r[\'id\'] ?>" class="btn btn-warning btn-sm rounded-pill px-4 fw-bold shadow-sm">Process Check-out</a>',
    $checkin_content
);
file_put_contents($checkin_file, $checkin_content);

echo "Modules fondamentaux (Check-out interactif + Contrat PDF) générés avec succès.";
?>
