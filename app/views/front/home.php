<?php $title = "Accueil"; ob_start(); ?>

<!-- ================= MOBILE VIEW ================= -->
<div class="d-md-none px-4 mt-2 pt-3 pb-5">
    <?php if(isset($_SESSION['user_id'])): ?>
        <h1 class="fw-bold mb-1">Salut <?= htmlspecialchars($_SESSION['user_prenom']) ?> !</h1>
    <?php else: ?>
        <h1 class="fw-bold mb-1">Bienvenue !</h1>
    <?php endif; ?>
    <p class="text-muted mb-4" style="font-size: 0.95rem;">Trouvez la voiture de vos rêves..</p>

    <form action="index.php" method="GET" class="search-bar">
        <input type="hidden" name="action" value="search">
        <i class="fa-solid fa-magnifying-glass text-muted ms-2 fs-5"></i>
        <input type="text" class="form-control" name="q" placeholder="Rechercher un véhicule" style="height: 45px;">
        <button type="submit" class="border-0 p-0 bg-transparent">
            <div class="filter-btn"><i class="fa-solid fa-sliders"></i></div>
        </button>
    </form>

    <div class="yellow-banner">
        <div style="position: relative; z-index: 2;">
            <h3 class="fs-4">Nouveautés</h3>
            <p>Véhicules Premium</p>
            <a href="index.php?action=search" class="btn-dark-pill">Explorer</a>
        </div>
        <img src="https://pngimg.com/uploads/suv/suv_PNG31.png" alt="SUV">
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Catégories Populaires</h6>
    </div>
    <div class="horizontal-scroll">
        <a href="#" class="pill-btn active">SUV</a>
        <a href="#" class="pill-btn">Berline</a>
        <a href="#" class="pill-btn">Citadine</a>
        <a href="#" class="pill-btn">Utilitaire</a>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
        <h6 class="fw-bold mb-0">Toutes les Marques</h6>
    </div>
    <div class="horizontal-scroll text-center align-items-center mb-4">
        <div class="me-3">
            <div style="width: 65px; height: 65px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <i class="fa-brands fa-hooli fs-1 text-dark"></i>
            </div>
            <small class="fw-bold text-muted d-block" style="font-size: 0.65rem;">HYUNDAI</small>
        </div>
        <div class="me-3">
            <div style="width: 65px; height: 65px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <span class="fs-4 fw-bold text-dark">KIA</span>
            </div>
            <small class="fw-bold text-muted d-block" style="font-size: 0.65rem;">KIA</small>
        </div>
        <div class="me-3">
            <div style="width: 65px; height: 65px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <i class="fa-brands fa-typo3 fs-1 text-dark"></i>
            </div>
            <small class="fw-bold text-muted d-block" style="font-size: 0.65rem;">TOYOTA</small>
        </div>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Top Véhicules</h6>
        <a href="index.php?action=search" class="text-warning text-decoration-none small fw-bold">Tout voir</a>
    </div>
    
    <div class="row g-3">
        <?php foreach ($cars as $car): ?>
        <div class="col-6">
            <a href="index.php?action=reserve&id=<?= $car['id'] ?>" class="car-card text-decoration-none">
                <div class="fav-btn"><i class="fa-regular fa-heart"></i></div>
                <img src="<?= htmlspecialchars($car['image_principale']) ?>" alt="<?= htmlspecialchars($car['modele']) ?>" style="height:80px;">
                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;"><?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></h6>
                <p class="text-muted mb-0 mt-1" style="font-size: 0.75rem;">
                    <?= number_format($car['prix_journalier'], 0, ',', ' ') ?> € <span style="font-size: 0.65rem;">/jour</span>
                </p>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ================= DESKTOP VIEW ================= -->
<div class="d-none d-md-block">
    <div class="container-fluid px-5 mt-4">
        <div class="desktop-hero position-relative overflow-hidden" style="border-radius: 30px; background: linear-gradient(to right, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 100%), url('https://images.unsplash.com/photo-1503376712341-ea43105ea122?auto=format&fit=crop&q=80&w=1920') center/cover; padding: 120px 80px; color: white;">
            <div class="row">
                <div class="col-lg-7">
                    <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill fw-bold">+100 Véhicules Premium</span>
                    <h1 class="display-4 fw-bold mb-3" style="line-height: 1.2;">La location de voitures,<br>réinventée.</h1>
                    <p class="lead mb-5 opacity-75" style="max-width: 500px;">Profitez du frisson de conduire notre large gamme de véhicules premium. Réservations flexibles et meilleurs prix garantis.</p>
                </div>
            </div>
            
            <div class="card border-0 shadow-lg" style="border-radius: 20px; max-width: 900px; position: absolute; bottom: -20px; left: 80px; right: 80px; z-index: 10;">
                <div class="card-body p-4">
                    <form action="index.php" method="GET" class="row g-3 align-items-end">
                        <input type="hidden" name="action" value="search">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark mb-1">Date de départ</label>
                            <input type="date" name="date_debut" class="form-control form-control-lg bg-light border-0" style="border-radius: 12px;" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark mb-1">Date de retour</label>
                            <input type="date" name="date_fin" class="form-control form-control-lg bg-light border-0" style="border-radius: 12px;" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-dark btn-lg w-100 fw-bold" style="border-radius: 12px; height: 50px;">Rechercher</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div style="height: 60px;"></div>

    <div class="container-fluid px-5 mt-5 mb-5 pb-5">
        <div class="row g-5">
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm p-4 sticky-top" style="border-radius: 24px; top: 100px;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Filtres</h5>
                        <a href="#" class="text-muted text-decoration-none small">Réinitialiser</a>
                    </div>
                    
                    <h6 class="fw-bold mb-3">Catégories</h6>
                    <div class="d-flex flex-column gap-2 mb-4 text-muted">
                        <label class="form-check-label d-flex justify-content-between">
                            <div><input type="checkbox" class="form-check-input me-2" checked> SUV</div>
                        </label>
                        <label class="form-check-label d-flex justify-content-between">
                            <div><input type="checkbox" class="form-check-input me-2"> Berline</div>
                        </label>
                        <label class="form-check-label d-flex justify-content-between">
                            <div><input type="checkbox" class="form-check-input me-2"> Premium</div>
                        </label>
                    </div>

                    <h6 class="fw-bold mb-3">Boîte de Vitesse</h6>
                    <div class="d-flex gap-2 mb-4">
                        <button class="btn btn-dark btn-sm rounded-pill px-4 py-2">Auto</button>
                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-4 py-2">Manuelle</button>
                    </div>

                    <button class="btn btn-warning w-100 fw-bold rounded-pill py-2">Appliquer</button>
                </div>
            </div>

            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
                    <h4 class="fw-bold mb-0">Véhicules Recommandés</h4>
                    <select class="form-select border-0 bg-white shadow-sm rounded-pill px-4 py-2 w-auto" style="font-size: 0.95rem;">
                        <option>Trier par : Recommandé</option>
                        <option>Prix : Croissant</option>
                        <option>Prix : Décroissant</option>
                    </select>
                </div>

                <div class="row g-4">
                    <?php foreach ($cars as $car): ?>
                    <div class="col-md-6 col-xl-4">
                        <a href="index.php?action=reserve&id=<?= $car['id'] ?>" class="car-card h-100 d-flex flex-column">
                            <div class="fav-btn"><i class="fa-regular fa-heart"></i></div>
                            <img src="<?= htmlspecialchars($car['image_principale']) ?>" alt="<?= htmlspecialchars($car['modele']) ?>" style="height: 180px;">
                            <div class="mt-auto">
                                <span class="badge bg-light text-dark mb-2 px-3 py-2 rounded-pill"><?= htmlspecialchars($car['categorie_nom'] ?? 'Auto') ?></span>
                                <h5 class="fw-bold mb-1 text-dark fs-5"><?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></h5>
                                <p class="text-muted small mb-3"><i class="fa-solid fa-gas-pump me-1"></i> <?= htmlspecialchars($car['type_carburant']) ?> &bull; <i class="fa-solid fa-gears me-1"></i> <?= htmlspecialchars($car['boite_vitesse']) ?></p>
                                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                    <div>
                                        <span class="text-muted small d-block" style="line-height: 1;">Tarif journalier</span>
                                        <h5 class="text-dark fw-bold mb-0 mt-1"><?= number_format($car['prix_journalier'], 0, ',', ' ') ?> €</h5>
                                    </div>
                                    <span class="btn btn-dark rounded-pill px-4 py-2 fw-bold">Louer</span>
                                </div>
                            </div>
                        </a>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $content = ob_get_clean(); require 'app/views/layouts/front.php'; ?>