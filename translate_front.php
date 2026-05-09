<?php
$files = [
    'app/views/layouts/front.php' => <<<'PHP'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>AutoRent - <?= $title ?? 'Location de Voitures' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root { --primary-bg: #fdfdfd; --dark-color: #1a1a1a; --accent-yellow: #f4c053; --light-grey: #f0f2f5; }
        body { font-family: 'Outfit', sans-serif; background-color: var(--primary-bg); color: var(--dark-color); padding-bottom: 90px; }
        @media (min-width: 768px) {
            .bottom-nav { display: none !important; }
            body { padding-bottom: 0; }
            .desktop-nav { display: flex !important; }
            .mobile-topbar { display: none !important; }
            .navbar-desktop-container { max-width: 1300px; margin: 0 auto; width: 100%; display: flex; justify-content: space-between; align-items: center; }
        }
        .mobile-topbar { display: flex; justify-content: space-between; align-items: center; padding: 20px 20px 10px 20px; }
        .icon-btn { width: 40px; height: 40px; border-radius: 12px; display: flex; align-items: center; justify-content: center; text-decoration: none; color: var(--dark-color); font-size: 1.2rem; }
        .desktop-nav { display: none; padding: 25px 30px; background: white; box-shadow: 0 4px 20px rgba(0,0,0,0.02); position: sticky; top: 0; z-index: 1000; }
        .desktop-nav .nav-links a { color: var(--dark-color); text-decoration: none; font-weight: 500; margin-left: 30px; transition: 0.2s; font-size: 1.05rem; }
        .desktop-nav .nav-links a:hover { color: var(--accent-yellow); }
        .bottom-nav { position: fixed; bottom: 20px; left: 20px; right: 20px; background: white; border-radius: 30px; display: flex; justify-content: space-around; align-items: center; padding: 15px 0; box-shadow: 0 10px 30px rgba(0,0,0,0.08); z-index: 1000; }
        .bottom-nav a { color: #b0b0b0; font-size: 1.3rem; padding: 10px 20px; border-radius: 20px; text-decoration: none; transition: all 0.3s ease; }
        .bottom-nav a.active { background: var(--dark-color); color: white; }
        .search-bar { background: white; border-radius: 25px; padding: 5px 5px 5px 20px; display: flex; align-items: center; box-shadow: 0 8px 20px rgba(0,0,0,0.03); margin-bottom: 30px; }
        .search-bar input { border: none; background: transparent; box-shadow: none; }
        .search-bar input:focus { outline: none; box-shadow: none; }
        .filter-btn { background: var(--dark-color); color: white; border-radius: 50%; width: 45px; height: 45px; display: flex; align-items: center; justify-content: center; }
        .pill-btn { background: white; border: 1px solid #f0f0f0; border-radius: 25px; padding: 10px 20px; font-size: 0.9rem; color: #666; font-weight: 500; white-space: nowrap; cursor: pointer; text-decoration: none; display: inline-block; }
        .pill-btn.active { background: var(--dark-color); color: white; border-color: var(--dark-color); }
        .horizontal-scroll { display: flex; overflow-x: auto; gap: 12px; padding-bottom: 5px; margin-bottom: 25px; scrollbar-width: none; }
        .horizontal-scroll::-webkit-scrollbar { display: none; }
        .yellow-banner { background: var(--accent-yellow); border-radius: 28px; padding: 25px 30px; display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px; position: relative; overflow: hidden; }
        .yellow-banner h3 { font-weight: 700; margin-bottom: 5px; color: var(--dark-color); }
        .yellow-banner p { color: #554315; font-size: 0.9rem; margin-bottom: 20px; }
        .btn-dark-pill { background: var(--dark-color); color: white; border-radius: 20px; padding: 8px 25px; font-weight: 500; text-decoration: none; font-size: 0.9rem; display: inline-block; }
        .yellow-banner img { position: absolute; right: -10px; bottom: 0; max-width: 140px; }
        .car-card { background: white; border-radius: 24px; padding: 18px; box-shadow: 0 8px 25px rgba(0,0,0,0.03); border: 1px solid #f9f9f9; transition: transform 0.2s, box-shadow 0.2s; position: relative; display: block; text-decoration: none; }
        .car-card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(0,0,0,0.06); }
        .car-card img { width: 100%; object-fit: contain; margin-bottom: 15px; }
        .fav-btn { position: absolute; top: 15px; right: 15px; background: #fff; border: 1px solid #eee; border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center; color: #ccc; cursor: pointer; z-index: 10; transition: 0.2s; }
        .fav-btn:hover { color: #ff4757; border-color: #ff4757; }
    </style>
</head>
<body>
    <!-- Header Desktop -->
    <header class="desktop-nav">
        <div class="navbar-desktop-container">
            <h3 class="fw-bold mb-0"><i class="fa-solid fa-car-side" style="color: var(--accent-yellow);"></i> AutoRent</h3>
            <div class="nav-links d-flex align-items-center">
                <a href="index.php">Accueil</a>
                <a href="index.php?action=search">Notre Flotte</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="index.php?action=history">Mes Réservations</a>
                    <?php if($_SESSION['user_role'] === 'admin'): ?>
                        <a href="index.php?action=admin_dashboard" style="color: var(--accent-yellow);">Administration</a>
                    <?php endif; ?>
                    <a href="index.php?action=logout" class="ms-4"><img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_prenom']) ?>&background=random" style="width: 40px; border-radius: 50%;"></a>
                <?php else: ?>
                    <a href="index.php?action=login" class="btn btn-dark rounded-pill px-4 ms-4 text-white">Connexion</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Header Mobile -->
    <div class="mobile-topbar d-md-none">
        <a href="#" class="icon-btn"><i class="fa-solid fa-border-all"></i></a>
        <h5 class="fw-bold mb-0">Accueil</h5>
        <a href="#" class="icon-btn position-relative">
            <i class="fa-regular fa-bell"></i>
            <span class="position-absolute top-25 start-75 translate-middle p-1 bg-danger border border-light rounded-circle" style="top: 10px; left: 30px;"></span>
        </a>
    </div>

    <!-- Main Content -->
    <main>
        <?= $content ?>
    </main>

    <!-- Navigation Bottom (Mobile) -->
    <div class="bottom-nav d-md-none">
        <a href="index.php" class="<?= (!isset($_GET['action']) || $_GET['action'] == 'home') ? 'active' : '' ?>"><i class="fa-solid fa-house"></i></a>
        <a href="index.php?action=search" class="<?= (isset($_GET['action']) && $_GET['action'] == 'search') ? 'active' : '' ?>"><i class="fa-solid fa-magnifying-glass"></i></a>
        <a href="index.php?action=history" class="<?= (isset($_GET['action']) && $_GET['action'] == 'history') ? 'active' : '' ?>"><i class="fa-regular fa-heart"></i></a>
        <a href="<?= isset($_SESSION['user_id']) ? 'index.php?action=logout' : 'index.php?action=login' ?>"><i class="fa-regular fa-user"></i></a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
PHP,

    'app/views/front/home.php' => <<<'PHP'
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
PHP,

    'app/views/front/search_results.php' => <<<'PHP'
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
PHP,

    'app/views/front/reserve.php' => <<<'PHP'
<?php $title = "Réserver ".$car['marque']; ob_start(); ?>
<div class="container-fluid px-4 px-md-5 mt-4 mt-md-5 mb-5 pb-5">
    <div class="row g-5">
        <div class="col-lg-4 order-lg-2">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden position-sticky" style="top: 100px;">
                <div class="p-4 text-center bg-light">
                    <img src="<?= htmlspecialchars($car['image_principale']) ?>" class="img-fluid" style="height: 200px; object-fit: contain;">
                </div>
                <div class="card-body p-4 bg-white">
                    <span class="badge bg-dark text-white mb-2 px-3 py-2 rounded-pill"><?= htmlspecialchars($car['categorie_nom']) ?></span>
                    <h3 class="fw-bold mb-4"><?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></h3>
                    
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                        <span class="text-muted">Tarif Journalier</span>
                        <span class="fw-bold text-success fs-5"><?= number_format($car['prix_journalier'], 0, ',', ' ') ?> €</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                        <span class="text-muted">Dépôt de Garantie (Caution) <i class="fa-solid fa-circle-info small"></i></span>
                        <span class="fw-bold"><?= number_format($car['caution'], 0, ',', ' ') ?> €</span>
                    </div>
                    
                    <div class="bg-light p-3 rounded-4 mt-4">
                        <h6 class="fw-bold mb-3"><i class="fa-solid fa-shield-halved text-warning me-2"></i> Protections Incluses</h6>
                        <ul class="list-unstyled mb-0 small text-muted">
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Protection contre le vol</li>
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Assurance Responsabilité Civile</li>
                            <li><i class="fa-solid fa-check text-success me-2"></i> Annulation Gratuite</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 order-lg-1">
            <a href="javascript:history.back()" class="text-decoration-none text-muted mb-4 d-inline-block"><i class="fa-solid fa-arrow-left me-2"></i> Retour aux résultats</a>
            
            <h2 class="fw-bold mb-4">Finalisez votre réservation</h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger rounded-4 border-0 shadow-sm"><i class="fa-solid fa-triangle-exclamation me-2"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form action="index.php?action=confirm_reserve" method="POST">
                <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                
                <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
                    <h5 class="fw-bold mb-4"><span class="badge bg-warning text-dark rounded-circle me-2" style="width: 25px; height: 25px; display: inline-flex; align-items: center; justify-content: center;">1</span> Période de Location</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Date de départ</label>
                            <input type="date" name="date_debut" class="form-control form-control-lg bg-light border-0 rounded-4" value="<?= $_GET['date_debut'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Date de retour</label>
                            <input type="date" name="date_fin" class="form-control form-control-lg bg-light border-0 rounded-4" value="<?= $_GET['date_fin'] ?? '' ?>" required>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
                    <h5 class="fw-bold mb-4"><span class="badge bg-warning text-dark rounded-circle me-2" style="width: 25px; height: 25px; display: inline-flex; align-items: center; justify-content: center;">2</span> Lieux de prise en charge</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Lieu de départ</label>
                            <select name="lieu_prise_id" class="form-select form-select-lg bg-light border-0 rounded-4" required>
                                <option value="">Choisir un lieu...</option>
                                <?php foreach($locations as $loc): ?>
                                    <option value="<?= $loc['id'] ?>"><?= htmlspecialchars($loc['nom']) ?> (+<?= $loc['frais_supplementaire'] ?>€)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Lieu de restitution</label>
                            <select name="lieu_retour_id" class="form-select form-select-lg bg-light border-0 rounded-4" required>
                                <option value="">Choisir un lieu...</option>
                                <?php foreach($locations as $loc): ?>
                                    <option value="<?= $loc['id'] ?>"><?= htmlspecialchars($loc['nom']) ?> (+<?= $loc['frais_supplementaire'] ?>€)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4 rounded-4 mb-5">
                    <h5 class="fw-bold mb-4"><span class="badge bg-warning text-dark rounded-circle me-2" style="width: 25px; height: 25px; display: inline-flex; align-items: center; justify-content: center;">3</span> Options & Extras</h5>
                    
                    <div class="form-check form-switch d-flex align-items-center p-3 border rounded-4">
                        <input class="form-check-input ms-0 mt-0" type="checkbox" role="switch" id="chauffeur" name="avec_chauffeur" value="1" style="width: 40px; height: 20px; cursor: pointer;">
                        <label class="form-check-label ms-3 w-100 d-flex justify-content-between align-items-center" for="chauffeur" style="cursor: pointer;">
                            <div>
                                <strong class="d-block">Chauffeur Privé</strong>
                                <small class="text-muted">Détendez-vous et laissez un professionnel vous conduire.</small>
                            </div>
                            <span class="badge bg-dark rounded-pill px-3 py-2">+150 € / jour</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-dark btn-lg w-100 fw-bold rounded-pill shadow" style="height: 60px;">Confirmer & Payer plus tard <i class="fa-solid fa-arrow-right ms-2"></i></button>
            </form>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/front.php'; ?>
PHP,

    'app/views/front/login.php' => <<<'PHP'
<?php $title = "Connexion"; ob_start(); ?>
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4 p-5 text-center">
                <div class="mb-4">
                    <i class="fa-solid fa-car-side fa-3x" style="color: var(--accent-yellow);"></i>
                    <h2 class="fw-bold mt-3">Heureux de vous revoir</h2>
                    <p class="text-muted">Connectez-vous pour gérer vos réservations</p>
                </div>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger rounded-4 border-0 text-start"><i class="fa-solid fa-circle-exclamation me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form action="index.php?action=do_login" method="POST" class="text-start">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small ms-2">Adresse Email</label>
                        <input type="email" name="email" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small ms-2">Mot de passe</label>
                        <input type="password" name="password" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" required>
                    </div>
                    <button type="submit" class="btn btn-dark btn-lg w-100 fw-bold rounded-pill mb-4" style="height: 55px;">Se Connecter</button>
                    <div class="text-center">
                        <span class="text-muted">Vous n'avez pas de compte ?</span> <a href="index.php?action=register" class="text-dark fw-bold text-decoration-none">S'inscrire</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/front.php'; ?>
PHP,

    'app/views/front/register.php' => <<<'PHP'
<?php $title = "Inscription"; ob_start(); ?>
<div class="container mt-5 mb-5 pb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4 p-5 text-center">
                <div class="mb-4">
                    <i class="fa-solid fa-car-side fa-3x" style="color: var(--accent-yellow);"></i>
                    <h2 class="fw-bold mt-3">Créer un compte</h2>
                    <p class="text-muted">Rejoignez AutoRent en quelques clics</p>
                </div>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger rounded-4 border-0 text-start"><i class="fa-solid fa-circle-exclamation me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form action="index.php?action=do_register" method="POST" class="text-start">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small ms-2">Prénom</label>
                            <input type="text" name="prenom" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small ms-2">Nom</label>
                            <input type="text" name="nom" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small ms-2">Adresse Email</label>
                        <input type="email" name="email" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small ms-2">Mot de passe</label>
                        <input type="password" name="password" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" required>
                    </div>
                    <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold rounded-pill mb-4 shadow-sm" style="height: 55px;">S'inscrire</button>
                    <div class="text-center">
                        <span class="text-muted">Vous avez déjà un compte ?</span> <a href="index.php?action=login" class="text-dark fw-bold text-decoration-none">Se connecter</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/front.php'; ?>
PHP,

    'app/views/front/history.php' => <<<'PHP'
<?php $title = "Mes Réservations"; ob_start(); ?>
<div class="container-fluid px-4 px-md-5 mt-4 mt-md-5 mb-5 pb-5">
    <div class="d-flex justify-content-between align-items-center mb-5">
        <h2 class="fw-bold mb-0">Mes Réservations</h2>
        <a href="index.php" class="btn btn-dark rounded-pill px-4"><i class="fa-solid fa-plus me-2"></i> Nouvelle Réservation</a>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success border-0 shadow-sm rounded-4"><i class="fa-solid fa-circle-check me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if(empty($reservations)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-light">
            <i class="fa-regular fa-calendar-xmark fa-4x text-muted mb-3"></i>
            <h4 class="fw-bold">Aucune réservation</h4>
            <p class="text-muted">Vos futurs voyages s'afficheront ici.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach($reservations as $r): ?>
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <div class="row g-0 h-100">
                        <div class="col-4 bg-light d-flex align-items-center justify-content-center p-3">
                            <img src="<?= htmlspecialchars($r['image_principale']) ?>" class="img-fluid" style="object-fit: contain;">
                        </div>
                        <div class="col-8">
                            <div class="card-body p-4">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <h5 class="fw-bold mb-0"><?= htmlspecialchars($r['marque'].' '.$r['modele']) ?></h5>
                                    <?php 
                                    $badge = match($r['status_reservation']) {
                                        'en_attente' => 'bg-warning text-dark',
                                        'validee' => 'bg-success',
                                        'en_cours' => 'bg-primary',
                                        'terminee' => 'bg-secondary',
                                        'annulee' => 'bg-danger',
                                        default => 'bg-light'
                                    };
                                    ?>
                                    <span class="badge <?= $badge ?> rounded-pill px-3 py-2 border">
                                        <?= ucfirst(str_replace('_', ' ', $r['status_reservation'])) ?>
                                    </span>
                                </div>
                                <p class="text-muted small mb-3"><i class="fa-regular fa-calendar me-2"></i> <?= date('d/m/Y', strtotime($r['date_debut'])) ?> &rarr; <?= date('d/m/Y', strtotime($r['date_fin'])) ?></p>
                                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                                    <div>
                                        <small class="text-muted d-block" style="line-height:1;">Coût Total</small>
                                        <span class="fw-bold fs-5 text-success"><?= number_format($r['prix_total'], 0, ',', ' ') ?> €</span>
                                    </div>
                                    <button class="btn btn-outline-dark btn-sm rounded-pill px-3">Détails</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/front.php'; ?>
PHP
];

foreach ($files as $filepath => $content) {
    file_put_contents($filepath, $content);
}
echo "Fichiers Front-end traduits en français.";
?>
