<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h2 class="fw-bold mb-1">Gestion de la Flotte</h2>
        <p class="text-muted">Gérez vos véhicules, tarifs et disponibilités.</p>
    </div>
    <a href="index.php?action=admin_car_form" class="btn btn-dark rounded-pill px-4"><i class="fa-solid fa-plus me-2"></i> Ajouter Véhicule</a>
</div>

<div class="row g-4">
    <?php foreach($cars as $car): ?>
    <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden position-relative">
            <div class="position-absolute top-0 start-0 m-3 z-index-10">
                <?php if($car['status'] == 'disponible'): ?>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 border border-success border-opacity-25"><i class="fa-solid fa-circle me-1" style="font-size:8px;"></i> Dispo</span>
                <?php elseif($car['status'] == 'maintenance'): ?>
                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 border border-warning border-opacity-25"><i class="fa-solid fa-wrench me-1"></i> Maintenance</span>
                <?php else: ?>
                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 border border-danger border-opacity-25"><i class="fa-solid fa-lock me-1"></i> Louée</span>
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
                        <span class="text-muted small d-block" style="line-height:1;">Tarif journalier</span>
                        <h5 class="text-success fw-bold mb-0 mt-1"><?= $car['prix_journalier'] ?> €</h5>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="index.php?action=admin_car_form&id=<?= $car['id'] ?>" class="btn btn-light rounded-circle shadow-sm text-primary" style="width: 38px; height: 38px; display:flex; align-items:center; justify-content:center;"><i class="fa-solid fa-pen"></i></a>
                        <a href="index.php?action=admin_car_delete&id=<?= $car['id'] ?>" class="btn btn-light rounded-circle shadow-sm text-danger" style="width: 38px; height: 38px; display:flex; align-items:center; justify-content:center;" onclick="return confirm('Supprimer ce véhicule ?');"><i class="fa-solid fa-trash"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>