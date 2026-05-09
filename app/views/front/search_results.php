<?php $title = "Résultats de recherche"; ob_start(); ?>
<div class="container-fluid px-4 px-md-5 mt-4 mt-md-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark">
            <?php if(!empty($_GET['date_debut']) && !empty($_GET['date_fin'])): ?>
                Véhicules disponibles du <?= htmlspecialchars(date('d/m/Y', strtotime($_GET['date_debut']))) ?> au <?= htmlspecialchars(date('d/m/Y', strtotime($_GET['date_fin']))) ?>
            <?php else: ?>
                Tous nos véhicules disponibles
            <?php endif; ?>
        </h3>
        <a href="index.php" class="btn btn-outline-dark rounded-pill px-4"><i class="fa-solid fa-arrow-left me-2"></i> Modifier les dates</a>
    </div>
    
    <?php if(empty($cars)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
            <i class="fa-solid fa-car-side fa-4x text-muted mb-3"></i>
            <h4 class="fw-bold">Aucun véhicule disponible</h4>
            <p class="text-muted">Veuillez essayer d'autres dates ou contacter l'agence.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($cars as $car): ?>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="index.php?action=reserve&id=<?= $car['id'] ?><?= !empty($_GET['date_debut']) ? '&date_debut='.urlencode($_GET['date_debut']) : '' ?><?= !empty($_GET['date_fin']) ? '&date_fin='.urlencode($_GET['date_fin']) : '' ?>" class="car-card text-decoration-none h-100 d-flex flex-column">
                    <div class="fav-btn"><i class="fa-regular fa-heart"></i></div>
                    <img src="<?= htmlspecialchars($car['image_principale']) ?>" alt="<?= htmlspecialchars($car['modele']) ?>" style="height: 160px; object-fit: contain;">
                    <div class="mt-auto">
                        <span class="badge bg-light text-dark mb-2 px-3 py-2 rounded-pill border"><?= htmlspecialchars($car['categorie_nom'] ?? 'Auto') ?></span>
                        <h5 class="fw-bold mb-1 text-dark fs-5"><?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></h5>
                        <p class="text-muted small mb-3"><i class="fa-solid fa-gas-pump me-1"></i> <?= htmlspecialchars($car['type_carburant']) ?> &bull; <i class="fa-solid fa-gears me-1"></i> <?= htmlspecialchars($car['boite_vitesse']) ?></p>
                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <div>
                                <span class="text-muted small d-block" style="line-height: 1;">Tarif journalier</span>
                                <h5 class="text-success fw-bold mb-0 mt-1"><?= number_format($car['prix_journalier'], 0, ',', ' ') ?> €</h5>
                            </div>
                            <span class="btn btn-dark rounded-pill px-4 py-2 fw-bold">Louer</span>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/front.php'; ?>