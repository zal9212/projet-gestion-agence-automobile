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