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