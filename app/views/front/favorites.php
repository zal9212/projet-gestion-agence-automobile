<?php $title = "Mes Favoris"; ob_start(); ?>

<!-- ================= VUE MOBILE ================= -->
<div class="d-md-none px-4 mt-2 pt-3 pb-5">
    <h1 class="fw-bold mb-1">Mes Favoris</h1>
    <p class="text-muted mb-4" style="font-size: 0.95rem;"><?= count($cars) ?> véhicule(s) sauvegardé(s)</p>

    <?php if(empty($cars)): ?>
        <div class="text-center py-5 mt-4">
            <div style="width: 90px; height: 90px; background: #f8f8f8; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px;">
                <i class="fa-regular fa-heart fa-2x text-muted"></i>
            </div>
            <h5 class="fw-bold">Aucun favori pour l'instant</h5>
            <p class="text-muted small">Ajoutez des voitures à vos favoris en appuyant sur le cœur.</p>
            <a href="index.php?action=search" class="btn btn-dark rounded-pill px-4 mt-2">Explorer la flotte</a>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($cars as $car): ?>
            <div class="col-6">
                <a href="index.php?action=reserve&id=<?= $car['id'] ?>" class="car-card text-decoration-none d-flex flex-column" style="height: 100%;">
                    <div class="fav-btn toggle-favorite active" data-id="<?= $car['id'] ?>">
                        <i class="fa-solid fa-heart text-danger"></i>
                    </div>
                    <div style="height: 110px; overflow: hidden; border-radius: 14px; margin-bottom: 12px;">
                        <img src="<?= htmlspecialchars($car['image_principale']) ?>" alt="<?= htmlspecialchars($car['modele']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    </div>
                    <span class="badge bg-light text-dark mb-1 px-2 py-1 rounded-pill border" style="font-size: 0.65rem; align-self: flex-start;"><?= htmlspecialchars($car['categorie_nom'] ?? 'Auto') ?></span>
                    <h6 class="fw-bold text-dark mb-0" style="font-size: 0.9rem;"><?= htmlspecialchars($car['marque'].' '.$car['modele']) ?></h6>
                    <p class="text-warning fw-bold mb-0 mt-auto" style="font-size: 0.85rem;"><?= number_format($car['prix_journalier'], 0, ',', ' ') ?> <span class="text-muted fw-normal" style="font-size: 0.7rem;">FCFA/j</span></p>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<!-- ================= VUE DESKTOP ================= -->
<div class="d-none d-md-block container py-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <div>
            <h1 class="fw-bold mb-1">Mes Favoris</h1>
            <p class="text-muted"><?= count($cars) ?> véhicule(s) sauvegardé(s)</p>
        </div>
        <a href="index.php?action=search" class="btn btn-dark rounded-pill px-4">
            <i class="fa-solid fa-magnifying-glass me-2"></i> Explorer la flotte
        </a>
    </div>

    <?php if(empty($cars)): ?>
        <div class="text-center py-5">
            <div class="card border-0 shadow-sm rounded-4 p-5" style="max-width: 450px; margin: 0 auto;">
                <i class="fa-regular fa-heart fa-3x text-muted mb-4"></i>
                <h4 class="fw-bold">Aucun favori</h4>
                <p class="text-muted">Vous n'avez pas encore sauvegardé de véhicule. Parcourez notre flotte et cliquez sur le cœur pour sauvegarder vos préférences.</p>
                <a href="index.php?action=search" class="btn btn-dark rounded-pill px-4 mt-2">
                    <i class="fa-solid fa-car me-2"></i> Voir tous les véhicules
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($cars as $car): ?>
            <div class="col-md-4 col-lg-3">
                <div class="car-card" style="position: relative;">
                    <!-- Bouton retirer des favoris -->
                    <button class="toggle-favorite fav-btn active" data-id="<?= $car['id'] ?>" style="position: absolute; top: 15px; right: 15px; z-index: 10; background: white; border: 1px solid #eee; border-radius: 50%; width: 36px; height: 36px; display: flex; align-items: center; justify-content: center; cursor: pointer;">
                        <i class="fa-solid fa-heart text-danger"></i>
                    </button>
                    <a href="index.php?action=reserve&id=<?= $car['id'] ?>" class="text-decoration-none">
                        <div style="height: 160px; overflow: hidden; border-radius: 16px; margin-bottom: 15px;">
                            <img src="<?= htmlspecialchars($car['image_principale']) ?>" alt="<?= htmlspecialchars($car['modele']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                        </div>
                        <span class="badge bg-light text-dark mb-2 px-3 py-1 rounded-pill border" style="font-size: 0.7rem;"><?= htmlspecialchars($car['categorie_nom'] ?? 'Auto') ?></span>
                        <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($car['marque'].' '.$car['modele']) ?></h5>
                        <div class="d-flex gap-3 text-muted small mb-3">
                            <span><i class="fa-solid fa-gas-pump me-1"></i><?= htmlspecialchars($car['type_carburant']) ?></span>
                            <span><i class="fa-solid fa-gears me-1"></i><?= htmlspecialchars($car['boite_vitesse']) ?></span>
                        </div>
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <span class="text-muted small">Par jour</span>
                                <h5 class="fw-bold text-success mb-0"><?= number_format($car['prix_journalier'], 0, ',', ' ') ?> FCFA</h5>
                            </div>
                            <span class="btn btn-dark btn-sm rounded-pill px-3">Réserver</span>
                        </div>
                    </a>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>

<?php $content = ob_get_clean(); require 'app/views/layouts/front.php'; ?>
