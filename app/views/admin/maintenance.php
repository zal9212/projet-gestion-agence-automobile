<?php ob_start(); ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Maintenance de la Flotte</h2>
        <p class="text-muted mb-0">Suivi des vidanges, visites techniques et assurances.</p>
    </div>
    <a href="index.php?action=admin_cars" class="btn btn-dark rounded-pill px-4 flex-fill flex-md-initial"><i class="fa-solid fa-car me-2"></i> Gérer la Flotte</a>
</div>

<div class="row g-4">
    <?php foreach($cars as $car): ?>
    <div class="col-md-6 col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 h-100">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="fw-bold mb-0 text-dark"><?= htmlspecialchars($car['immatriculation']) ?></h5>
                <span class="badge bg-light text-dark border small"><?= htmlspecialchars($car['marque'].' '.$car['modele']) ?></span>
            </div>
            
            <div class="d-flex align-items-center text-muted small mb-4">
                <div class="bg-light rounded-circle d-flex align-items-center justify-content-center me-2" style="width:30px; height:30px;">
                    <i class="fa-solid fa-gauge-high" style="font-size: 0.8rem;"></i>
                </div>
                <span><?= number_format($car['kilometrage'], 0, ',', ' ') ?> km parcourus</span>
            </div>
            
            <div class="space-y-3">
                <div class="d-flex justify-content-between align-items-center small py-2 border-bottom border-light">
                    <span class="text-muted"><i class="fa-solid fa-shield-halved me-2"></i> Assurance</span>
                    <?php if($car['date_assurance']): ?>
                        <?php $expired = strtotime($car['date_assurance']) < time(); ?>
                        <span class="fw-bold <?= $expired ? 'text-danger' : 'text-success' ?>">
                            <?= date('d/m/Y', strtotime($car['date_assurance'])) ?>
                            <?php if($expired): ?> <i class="fa-solid fa-circle-exclamation ms-1"></i><?php endif; ?>
                        </span>
                    <?php else: ?>
                        <span class="text-danger fw-bold">Non renseigné</span>
                    <?php endif; ?>
                </div>

                <div class="d-flex justify-content-between align-items-center small py-2 border-bottom border-light">
                    <span class="text-muted"><i class="fa-solid fa-wrench me-2"></i> Visite Technique</span>
                    <?php if(isset($car['date_visite_technique']) && $car['date_visite_technique']): ?>
                        <?php $expired = strtotime($car['date_visite_technique']) < time(); ?>
                        <span class="fw-bold <?= $expired ? 'text-danger' : 'text-success' ?>">
                            <?= date('d/m/Y', strtotime($car['date_visite_technique'])) ?>
                            <?php if($expired): ?> <i class="fa-solid fa-circle-exclamation ms-1"></i><?php endif; ?>
                        </span>
                    <?php else: ?>
                        <span class="text-warning fw-bold">À mettre à jour</span>
                    <?php endif; ?>
                </div>
            </div>

            <div class="mt-4 pt-2">
                <a href="index.php?action=admin_car_form&id=<?= $car['id'] ?>" class="btn btn-outline-dark btn-sm w-100 rounded-pill">
                    <i class="fa-solid fa-pen-to-square me-2"></i> Actualiser les données
                </a>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>