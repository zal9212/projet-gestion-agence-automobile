<?php $title = "Résultats de recherche"; ob_start(); ?>
<div class="container-fluid px-4 px-md-5 mt-4 mt-md-5 mb-5">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <h3 class="fw-bold text-dark mb-0">
            <?php if(!empty($_GET['date_debut']) && !empty($_GET['date_fin'])): ?>
                Disponibilités du <?= htmlspecialchars(date('d/m/Y', strtotime($_GET['date_debut']))) ?> au <?= htmlspecialchars(date('d/m/Y', strtotime($_GET['date_fin']))) ?>
            <?php else: ?>
                Véhicules disponibles
            <?php endif; ?>
        </h3>
        <a href="index.php" class="btn btn-outline-dark rounded-pill px-4 flex-fill flex-md-initial text-nowrap"><i class="fa-solid fa-arrow-left me-2"></i> Modifier les dates</a>
    </div>
    
    <div class="row g-5 mt-2">
        <!-- Sidebar Filtres (Desktop) -->
        <div class="col-lg-3 d-none d-lg-block">
            <form action="index.php" method="GET" class="card border-0 shadow-sm p-4 sticky-top" style="border-radius: 24px; top: 100px;">
                <input type="hidden" name="action" value="search">
                <?php if(!empty($_GET['date_debut'])): ?><input type="hidden" name="date_debut" value="<?= htmlspecialchars($_GET['date_debut']) ?>"><?php endif; ?>
                <?php if(!empty($_GET['date_fin'])): ?><input type="hidden" name="date_fin" value="<?= htmlspecialchars($_GET['date_fin']) ?>"><?php endif; ?>
                
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">Filtres</h5>
                    <a href="index.php?action=search" class="text-muted text-decoration-none small">Effacer</a>
                </div>
                
                <h6 class="fw-bold mb-3">Catégories</h6>
                <div class="d-flex flex-column gap-2 mb-4 text-muted">
                    <?php 
                    $selected_cats = $_GET['categories'] ?? [];
                    foreach($categories as $cat): 
                    ?>
                    <label class="form-check-label d-flex justify-content-between" style="cursor: pointer;">
                        <div>
                            <input type="checkbox" name="categories[]" value="<?= $cat['id'] ?>" class="form-check-input me-2" <?= in_array($cat['id'], $selected_cats) ? 'checked' : '' ?>> 
                            <?= htmlspecialchars($cat['nom']) ?>
                        </div>
                    </label>
                    <?php endforeach; ?>
                </div>

                <h6 class="fw-bold mb-3">Boîte de Vitesse</h6>
                <div class="mb-4">
                    <select name="transmission" class="form-select border-0 bg-light rounded-pill px-3">
                        <option value="">Toutes</option>
                        <option value="Automatique" <?= ($_GET['transmission'] ?? '') == 'Automatique' ? 'selected' : '' ?>>Automatique</option>
                        <option value="Manuelle" <?= ($_GET['transmission'] ?? '') == 'Manuelle' ? 'selected' : '' ?>>Manuelle</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-warning w-100 fw-bold rounded-pill py-2">Appliquer</button>
            </form>
        </div>

        <!-- Résultats -->
        <div class="col-lg-9">
            <?php if(empty($cars)): ?>
                <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
                    <i class="fa-solid fa-car-side fa-4x text-muted mb-3"></i>
                    <h4 class="fw-bold">Aucun véhicule trouvé</h4>
                    <p class="text-muted">Essayez de modifier vos filtres ou vos dates.</p>
                </div>
            <?php else: ?>
                <div class="row g-4">
                    <?php foreach ($cars as $car): ?>
                    <div class="col-md-6 col-xl-4">
                        <a href="index.php?action=reserve&id=<?= $car['id'] ?><?= !empty($_GET['date_debut']) ? '&date_debut='.urlencode($_GET['date_debut']) : '' ?><?= !empty($_GET['date_fin']) ? '&date_fin='.urlencode($_GET['date_fin']) : '' ?>" class="car-card text-decoration-none h-100 d-flex flex-column">
                            <div style="height: 180px; overflow: hidden; border-radius: 15px; flex-shrink: 0; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                                <?php if(!empty($car['image_principale'])): ?>
                                    <img src="<?= htmlspecialchars($car['image_principale']) ?>" alt="<?= htmlspecialchars($car['modele']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <i class="fa-solid fa-car fa-4x text-muted opacity-25"></i>
                                <?php endif; ?>
                            </div>
                            <div class="mt-auto p-3">
                                <span class="badge bg-light text-dark mb-2 px-3 py-2 rounded-pill border"><?= htmlspecialchars($car['categorie_nom'] ?? 'Auto') ?></span>
                                <h5 class="fw-bold mb-1 text-dark fs-5"><?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></h5>
                                <p class="text-muted small mb-3"><i class="fa-solid fa-gas-pump me-1"></i> <?= htmlspecialchars($car['type_carburant']) ?> &bull; <i class="fa-solid fa-gears me-1"></i> <?= htmlspecialchars($car['boite_vitesse']) ?></p>
                                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                    <div>
                                        <span class="text-muted small d-block" style="line-height: 1;">Tarif journalier</span>
                                        <h5 class="text-success fw-bold mb-0 mt-1"><?= number_format($car['prix_journalier'], 0, ',', ' ') ?> FCFA</h5>
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
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/front.php'; ?>