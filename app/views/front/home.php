<?php $title = "Accueil"; ob_start(); ?>

<!-- ================= VUE MOBILE ================= -->
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

    <div class="yellow-banner" style="background: linear-gradient(to right, rgba(0,0,0,0.88) 0%, rgba(0,0,0,0.4) 60%, rgba(0,0,0,0.1) 100%), url('assets/img/3d-car-with-minimal-background.jpg') center/cover no-repeat; color: white;">
        <div style="position: relative; z-index: 2;">
            <h3 class="fs-4" style="color:white;">Nouveautés</h3>
            <p style="color: rgba(255,255,255,0.8);">Véhicules Premium</p>
            <a href="index.php?action=search" class="btn-dark-pill" style="background: #f4c053; color: #000;">Explorer</a>
        </div>
        <img src="assets/img/3d-car-with-minimal-background.jpg" alt="SUV" style="opacity: 0; position: absolute;">
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
        <?php 
        $view_pdo = get_pdo();
        foreach($brands as $b): 
            // On cherche si un logo existe pour cette marque
            $stmt = $view_pdo->prepare("SELECT brand_logo FROM cars WHERE marque = ? AND brand_logo IS NOT NULL AND brand_logo != '' LIMIT 1");
            $stmt->execute([$b]);
            $logo = $stmt->fetchColumn();
        ?>
        <a href="index.php?action=search&q=<?= urlencode($b) ?>" class="text-decoration-none me-3">
            <div style="width: 65px; height: 65px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.03); border: 1px solid #f0f0f0; overflow: hidden; padding: 12px;">
                <?php 
                $brand_lower = strtolower($b);
                $external_logo = "https://raw.githubusercontent.com/fawazahmed0/car-logos/master/logos/" . $brand_lower . ".png";
                
                if($logo): ?>
                    <img src="<?= htmlspecialchars($logo) ?>" style="width: 100%; height: 100%; object-fit: contain;">
                <?php else: ?>
                    <img src="<?= $external_logo ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" style="width: 100%; height: 100%; object-fit: contain;">
                    <span class="fs-4 fw-bold text-dark" style="display: none;"><?= substr($b, 0, 1) ?></span>
                <?php endif; ?>
            </div>
            <small class="fw-bold text-muted d-block text-uppercase" style="font-size: 0.6rem;"><?= htmlspecialchars($b) ?></small>
        </a>
        <?php endforeach; ?>
    </div>

    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Top Véhicules</h6>
        <a href="index.php?action=search" class="text-warning text-decoration-none small fw-bold">Tout voir</a>
    </div>
    
    <div class="row g-3">
        <?php foreach ($cars as $car): ?>
        <div class="col-6">
            <a href="index.php?action=reserve&id=<?= $car['id'] ?>" class="car-card text-decoration-none">
                <div class="fav-btn toggle-favorite <?= $car['is_fav'] ? 'active' : '' ?>" data-id="<?= $car['id'] ?>">
                    <i class="<?= $car['is_fav'] ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart' ?>"></i>
                </div>
                <div style="height: 100px; overflow: hidden; border-radius: 12px; margin-bottom: 8px; background: #f8f9fa; display: flex; align-items: center; justify-content: center;">
                    <?php if(!empty($car['image_principale'])): ?>
                        <img src="<?= htmlspecialchars($car['image_principale']) ?>" alt="<?= htmlspecialchars($car['modele']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                    <?php else: ?>
                        <i class="fa-solid fa-car fa-2x text-muted opacity-25"></i>
                    <?php endif; ?>
                </div>
                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;"><?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></h6>
                <p class="text-muted mb-0 mt-1" style="font-size: 0.75rem;">
                    <?= number_format($car['prix_journalier'], 0, ',', ' ') ?> FCFA <span style="font-size: 0.65rem;">/jour</span>
                </p>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ================= VUE ORDINATEUR ================= -->
<div class="d-none d-md-block">
    <div class="container-fluid px-5 mt-4">
        <div class="desktop-hero position-relative overflow-hidden" style="border-radius: 30px; background: linear-gradient(to right, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.3) 60%, rgba(0,0,0,0.05) 100%), url('assets/img/3d-car-with-minimal-background.jpg') center/cover no-repeat; padding: 120px 80px; color: white;">
            <div class="row">
                <div class="col-lg-7">
                    <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill fw-bold">+100 Véhicules Premium</span>
                    <h1 class="display-4 fw-bold mb-3" style="line-height: 1.2;">La location de voitures,<br>réinventée.</h1>
                    <p class="lead mb-5 opacity-75" style="max-width: 500px;">Profitez du frisson de conduire notre large gamme de véhicules premium. Réservations flexibles et meilleurs prix garantis.</p>
                </div>
            </div>
            
            <div class="card border-0 shadow-lg" style="border-radius: 24px; max-width: 1000px; position: absolute; bottom: -25px; left: 50%; transform: translateX(-50%); z-index: 10; width: 90%;">
                <div class="card-body p-4">
                    <form action="index.php" method="GET" class="row g-3 align-items-end">
                        <input type="hidden" name="action" value="search">
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small mb-1 ms-2">Où & Quoi ?</label>
                            <div class="input-group bg-light rounded-pill px-3" style="height: 45px;">
                                <span class="input-group-text bg-transparent border-0 text-muted"><i class="fa-solid fa-magnifying-glass"></i></span>
                                <input type="text" name="q" class="form-control bg-transparent border-0 ps-0" placeholder="Marque, modèle..." style="box-shadow:none;">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small mb-1 ms-2">Date de départ</label>
                            <input type="date" name="date_debut" class="form-control bg-light border-0 rounded-pill px-4" style="height: 45px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold text-muted small mb-1 ms-2">Date de retour</label>
                            <input type="date" name="date_fin" class="form-control bg-light border-0 rounded-pill px-4" style="height: 45px;">
                        </div>
                        <div class="col-md-3">
                            <button type="submit" class="btn btn-dark w-100 fw-bold rounded-pill" style="height: 45px; background: #1a1a1a;">
                                <i class="fa-solid fa-search me-2"></i> Rechercher
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div style="height: 80px;"></div>

    <div class="container-fluid px-5 mb-5">
        <h5 class="fw-bold mb-4">Toutes les Marques</h5>
        <div class="horizontal-scroll text-center align-items-center mb-4">
            <?php 
            $view_pdo = get_pdo();
            foreach($brands as $b): 
                $stmt = $view_pdo->prepare("SELECT brand_logo FROM cars WHERE marque = ? AND brand_logo IS NOT NULL AND brand_logo != '' LIMIT 1");
                $stmt->execute([$b]);
                $logo = $stmt->fetchColumn();
            ?>
            <a href="index.php?action=search&q=<?= urlencode($b) ?>" class="text-decoration-none me-4">
                <div style="width: 85px; height: 85px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 12px; box-shadow: 0 4px 20px rgba(0,0,0,0.04); border: 1px solid #f0f0f0; overflow: hidden; padding: 18px; transition: 0.3s;" class="brand-circle">
                    <?php 
                    $brand_lower = strtolower($b);
                    $external_logo = "https://raw.githubusercontent.com/fawazahmed0/car-logos/master/logos/" . $brand_lower . ".png";
                    if($logo): ?>
                        <img src="<?= htmlspecialchars($logo) ?>" style="width: 100%; height: 100%; object-fit: contain;">
                    <?php else: ?>
                        <img src="<?= $external_logo ?>" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';" style="width: 100%; height: 100%; object-fit: contain;">
                        <span class="fs-4 fw-bold text-dark" style="display: none;"><?= substr($b, 0, 1) ?></span>
                    <?php endif; ?>
                </div>
                <small class="fw-bold text-muted d-block text-uppercase" style="font-size: 0.75rem; letter-spacing: 0.5px;"><?= htmlspecialchars($b) ?></small>
            </a>
            <?php endforeach; ?>
        </div>
    </div>
    
    <style>
        .brand-circle:hover { transform: translateY(-5px); border-color: #f4c053; box-shadow: 0 10px 25px rgba(244, 192, 83, 0.15); }
    </style>

    <div class="container-fluid px-5 mt-5 mb-5 pb-5">
        <div class="row g-5">
            <div class="col-lg-3">
                <form action="index.php" method="GET" class="card border-0 shadow-sm p-4 sticky-top" style="border-radius: 24px; top: 100px;">
                    <input type="hidden" name="action" value="search">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Filtres</h5>
                        <a href="index.php?action=search" class="text-muted text-decoration-none small">Réinitialiser</a>
                    </div>
                    
                    <h6 class="fw-bold mb-3">Catégories</h6>
                    <div class="d-flex flex-column gap-2 mb-4 text-muted">
                        <?php 
                        $pdo = get_pdo();
                        $cats = $pdo->query("SELECT * FROM categories")->fetchAll();
                        foreach($cats as $cat): 
                        ?>
                        <label class="form-check-label d-flex justify-content-between" style="cursor: pointer;">
                            <div>
                                <input type="checkbox" name="categories[]" value="<?= $cat['id'] ?>" class="form-check-input me-2"> 
                                <?= htmlspecialchars($cat['nom']) ?>
                            </div>
                        </label>
                        <?php endforeach; ?>
                    </div>

                    <h6 class="fw-bold mb-3">Boîte de Vitesse</h6>
                    <div class="mb-4">
                        <select name="transmission" class="form-select border-0 bg-light rounded-pill px-3">
                            <option value="">Toutes</option>
                            <option value="Automatique">Automatique</option>
                            <option value="Manuelle">Manuelle</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-warning w-100 fw-bold rounded-pill py-2">Appliquer</button>
                </form>
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
                            <div class="bg-light position-relative d-flex align-items-center justify-content-center" style="height: 180px; overflow: hidden;">
                                <?php if(!empty($car['image_principale'])): ?>
                                    <img src="<?= htmlspecialchars($car['image_principale']) ?>" alt="<?= htmlspecialchars($car['modele']) ?>" style="width: 100%; height: 100%; object-fit: cover;">
                                <?php else: ?>
                                    <i class="fa-solid fa-car fa-4x text-muted opacity-25"></i>
                                <?php endif; ?>
                            </div>
                            <div class="fav-btn toggle-favorite <?= $car['is_fav'] ? 'active' : '' ?>" data-id="<?= $car['id'] ?>">
                                <i class="<?= $car['is_fav'] ? 'fa-solid fa-heart text-danger' : 'fa-regular fa-heart' ?>"></i>
                            </div>
                            <div class="mt-auto">
                                <span class="badge bg-light text-dark mb-2 px-3 py-2 rounded-pill"><?= htmlspecialchars($car['categorie_nom'] ?? 'Auto') ?></span>
                                <h5 class="fw-bold mb-1 text-dark fs-5"><?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></h5>
                                <p class="text-muted small mb-3"><i class="fa-solid fa-gas-pump me-1"></i> <?= htmlspecialchars($car['type_carburant']) ?> &bull; <i class="fa-solid fa-gears me-1"></i> <?= htmlspecialchars($car['boite_vitesse']) ?></p>
                                <div class="d-flex justify-content-between align-items-center border-top pt-3">
                                    <div>
                                        <span class="text-muted small d-block" style="line-height: 1;">Tarif journalier</span>
                                        <h5 class="text-dark fw-bold mb-0 mt-1"><?= number_format($car['prix_journalier'], 0, ',', ' ') ?> FCFA</h5>
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