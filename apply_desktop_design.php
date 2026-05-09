<?php
$files = [
    'app/views/layouts/front.php' => <<<'PHP'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>AutoRent - <?= $title ?? 'Location de Voitures' ?></title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-bg: #fdfdfd;
            --dark-color: #1a1a1a;
            --accent-yellow: #f4c053;
            --light-grey: #f0f2f5;
        }
        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--primary-bg);
            color: var(--dark-color);
            padding-bottom: 90px; /* Mobile nav space */
        }
        
        /* DESKTOP STYLES */
        @media (min-width: 768px) {
            .bottom-nav { display: none !important; }
            body { padding-bottom: 0; }
            .desktop-nav { display: flex !important; }
            .mobile-topbar { display: none !important; }
            .navbar-desktop-container {
                max-width: 1300px;
                margin: 0 auto;
                width: 100%;
                display: flex;
                justify-content: space-between;
                align-items: center;
            }
        }

        /* Topbar Mobile */
        .mobile-topbar {
            display: flex; justify-content: space-between; align-items: center;
            padding: 20px 20px 10px 20px;
        }
        .icon-btn {
            width: 40px; height: 40px; border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; color: var(--dark-color); font-size: 1.2rem;
        }

        /* Navbar Desktop */
        .desktop-nav {
            display: none;
            padding: 25px 30px;
            background: white;
            box-shadow: 0 4px 20px rgba(0,0,0,0.02);
            position: sticky; top: 0; z-index: 1000;
        }
        .desktop-nav .nav-links a {
            color: var(--dark-color); text-decoration: none; font-weight: 500;
            margin-left: 30px; transition: 0.2s; font-size: 1.05rem;
        }
        .desktop-nav .nav-links a:hover { color: var(--accent-yellow); }

        /* Navigation Bottom (Mobile) */
        .bottom-nav {
            position: fixed; bottom: 20px; left: 20px; right: 20px;
            background: white; border-radius: 30px;
            display: flex; justify-content: space-around; align-items: center;
            padding: 15px 0; box-shadow: 0 10px 30px rgba(0,0,0,0.08); z-index: 1000;
        }
        .bottom-nav a {
            color: #b0b0b0; font-size: 1.3rem; padding: 10px 20px;
            border-radius: 20px; text-decoration: none; transition: all 0.3s ease;
        }
        .bottom-nav a.active { background: var(--dark-color); color: white; }

        /* MOBILE SPECIFIC UI Elements */
        .search-bar {
            background: white; border-radius: 25px; padding: 5px 5px 5px 20px;
            display: flex; align-items: center; box-shadow: 0 8px 20px rgba(0,0,0,0.03);
            margin-bottom: 30px;
        }
        .search-bar input { border: none; background: transparent; box-shadow: none; }
        .search-bar input:focus { outline: none; box-shadow: none; }
        .filter-btn {
            background: var(--dark-color); color: white; border-radius: 50%;
            width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;
        }
        .pill-btn {
            background: white; border: 1px solid #f0f0f0; border-radius: 25px;
            padding: 10px 20px; font-size: 0.9rem; color: #666; font-weight: 500;
            white-space: nowrap; cursor: pointer; text-decoration: none; display: inline-block;
        }
        .pill-btn.active { background: var(--dark-color); color: white; border-color: var(--dark-color); }
        .horizontal-scroll {
            display: flex; overflow-x: auto; gap: 12px; padding-bottom: 5px; margin-bottom: 25px; scrollbar-width: none;
        }
        .horizontal-scroll::-webkit-scrollbar { display: none; }
        .yellow-banner {
            background: var(--accent-yellow); border-radius: 28px; padding: 25px 30px;
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px;
            position: relative; overflow: hidden;
        }
        .yellow-banner h3 { font-weight: 700; margin-bottom: 5px; color: var(--dark-color); }
        .yellow-banner p { color: #554315; font-size: 0.9rem; margin-bottom: 20px; }
        .btn-dark-pill { background: var(--dark-color); color: white; border-radius: 20px; padding: 8px 25px; font-weight: 500; text-decoration: none; font-size: 0.9rem; display: inline-block; }
        .yellow-banner img { position: absolute; right: -10px; bottom: 0; max-width: 140px; }

        /* Shared Cards */
        .car-card {
            background: white; border-radius: 24px; padding: 18px;
            box-shadow: 0 8px 25px rgba(0,0,0,0.03); border: 1px solid #f9f9f9;
            transition: transform 0.2s, box-shadow 0.2s; position: relative; display: block; text-decoration: none;
        }
        .car-card:hover { transform: translateY(-5px); box-shadow: 0 12px 30px rgba(0,0,0,0.06); }
        .car-card img { width: 100%; object-fit: contain; margin-bottom: 15px; }
        .fav-btn {
            position: absolute; top: 15px; right: 15px; background: #fff; border: 1px solid #eee;
            border-radius: 50%; width: 35px; height: 35px; display: flex; align-items: center; justify-content: center;
            color: #ccc; cursor: pointer; z-index: 10; transition: 0.2s;
        }
        .fav-btn:hover { color: #ff4757; border-color: #ff4757; }
    </style>
</head>
<body>

    <!-- Header Desktop -->
    <header class="desktop-nav">
        <div class="navbar-desktop-container">
            <h3 class="fw-bold mb-0"><i class="fa-solid fa-car-side" style="color: var(--accent-yellow);"></i> AutoRent</h3>
            <div class="nav-links d-flex align-items-center">
                <a href="index.php">Home</a>
                <a href="index.php?action=search">Explore Fleet</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="index.php?action=history">My Bookings</a>
                    <?php if($_SESSION['user_role'] === 'admin'): ?>
                        <a href="index.php?action=admin_dashboard" style="color: var(--accent-yellow);">Dashboard</a>
                    <?php endif; ?>
                    <a href="index.php?action=logout" class="ms-4"><img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_prenom']) ?>&background=random" style="width: 40px; border-radius: 50%;"></a>
                <?php else: ?>
                    <a href="index.php?action=login" class="btn btn-dark rounded-pill px-4 ms-4 text-white">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Header Mobile -->
    <div class="mobile-topbar d-md-none">
        <a href="#" class="icon-btn"><i class="fa-solid fa-border-all"></i></a>
        <h5 class="fw-bold mb-0">Home</h5>
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

<!-- ================= MOBILE VIEW (Hidden on Desktop) ================= -->
<div class="d-md-none px-4 mt-2 pt-3 pb-5">
    <!-- Titre et Greeting -->
    <?php if(isset($_SESSION['user_id'])): ?>
        <h1 class="fw-bold mb-1">Hi <?= htmlspecialchars($_SESSION['user_prenom']) ?>!</h1>
    <?php else: ?>
        <h1 class="fw-bold mb-1">Welcome!</h1>
    <?php endif; ?>
    <p class="text-muted mb-4" style="font-size: 0.95rem;">Search your favourite car here..</p>

    <!-- Barre de recherche -->
    <form action="index.php" method="GET" class="search-bar">
        <input type="hidden" name="action" value="search">
        <i class="fa-solid fa-magnifying-glass text-muted ms-2 fs-5"></i>
        <input type="text" class="form-control" name="q" placeholder="Search products" style="height: 45px;">
        <button type="submit" class="border-0 p-0 bg-transparent">
            <div class="filter-btn"><i class="fa-solid fa-sliders"></i></div>
        </button>
    </form>

    <!-- Bannière Jaune -->
    <div class="yellow-banner">
        <div style="position: relative; z-index: 2;">
            <h3 class="fs-4">Explore Latest</h3>
            <p>Cars with Price</p>
            <a href="index.php?action=search" class="btn-dark-pill">Explore</a>
        </div>
        <img src="https://pngimg.com/uploads/suv/suv_PNG31.png" alt="SUV">
    </div>

    <!-- Filtres : Popular Segments -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Popular Segments</h6>
    </div>
    <div class="horizontal-scroll">
        <a href="#" class="pill-btn active">SUV</a>
        <a href="#" class="pill-btn">Sedan</a>
        <a href="#" class="pill-btn">Hatchback</a>
        <a href="#" class="pill-btn">Compact</a>
    </div>

    <!-- Filtres : Brands -->
    <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
        <h6 class="fw-bold mb-0">All Brands</h6>
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

    <!-- Section : Top Cars -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Top Cars</h6>
        <a href="index.php?action=search" class="text-warning text-decoration-none small fw-bold">View all</a>
    </div>
    
    <div class="row g-3">
        <?php foreach ($cars as $car): ?>
        <div class="col-6">
            <a href="index.php?action=reserve&id=<?= $car['id'] ?>" class="car-card text-decoration-none">
                <div class="fav-btn"><i class="fa-regular fa-heart"></i></div>
                <img src="<?= htmlspecialchars($car['image_principale']) ?>" alt="<?= htmlspecialchars($car['modele']) ?>" style="height:80px;">
                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;"><?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></h6>
                <p class="text-muted mb-0 mt-1" style="font-size: 0.75rem;">
                    <?= number_format($car['prix_journalier'], 0, ',', ' ') ?> € <span style="font-size: 0.65rem;">/day</span>
                </p>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- ================= DESKTOP VIEW (Hidden on Mobile) ================= -->
<div class="d-none d-md-block">
    
    <!-- Hero Section -->
    <div class="container-fluid px-5 mt-4">
        <div class="desktop-hero position-relative overflow-hidden" style="border-radius: 30px; background: linear-gradient(to right, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.2) 100%), url('https://images.unsplash.com/photo-1503376712341-ea43105ea122?auto=format&fit=crop&q=80&w=1920') center/cover; padding: 120px 80px; color: white;">
            <div class="row">
                <div class="col-lg-7">
                    <span class="badge bg-warning text-dark mb-3 px-3 py-2 rounded-pill fw-bold">100+ Premium Cars</span>
                    <h1 class="display-4 fw-bold mb-3" style="line-height: 1.2;">Premium Car Rental<br>At Your Fingertips</h1>
                    <p class="lead mb-5 opacity-75" style="max-width: 500px;">Experience the thrill of driving our wide range of premium and luxury cars. Flexible bookings and best prices guaranteed.</p>
                </div>
            </div>
            
            <!-- Desktop Search Panel (Overlapping) -->
            <div class="card border-0 shadow-lg" style="border-radius: 20px; max-width: 900px; position: absolute; bottom: -20px; left: 80px; right: 80px; z-index: 10;">
                <div class="card-body p-4">
                    <form action="index.php" method="GET" class="row g-3 align-items-end">
                        <input type="hidden" name="action" value="search">
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark mb-1">Pick-up Date</label>
                            <input type="date" name="date_debut" class="form-control form-control-lg bg-light border-0" style="border-radius: 12px;" required>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold text-dark mb-1">Drop-off Date</label>
                            <input type="date" name="date_fin" class="form-control form-control-lg bg-light border-0" style="border-radius: 12px;" required>
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-dark btn-lg w-100 fw-bold" style="border-radius: 12px; height: 50px;">Search Available Cars</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Espace pour compenser la div absolue -->
    <div style="height: 60px;"></div>

    <div class="container-fluid px-5 mt-5 mb-5 pb-5">
        <div class="row g-5">
            <!-- Sidebar Filters -->
            <div class="col-lg-3">
                <div class="card border-0 shadow-sm p-4 sticky-top" style="border-radius: 24px; top: 100px;">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0">Filters</h5>
                        <a href="#" class="text-muted text-decoration-none small">Reset</a>
                    </div>
                    
                    <h6 class="fw-bold mb-3">Car Type</h6>
                    <div class="d-flex flex-column gap-2 mb-4 text-muted">
                        <label class="form-check-label d-flex justify-content-between">
                            <div><input type="checkbox" class="form-check-input me-2" checked> SUV</div>
                            <span class="small">24</span>
                        </label>
                        <label class="form-check-label d-flex justify-content-between">
                            <div><input type="checkbox" class="form-check-input me-2"> Sedan</div>
                            <span class="small">18</span>
                        </label>
                        <label class="form-check-label d-flex justify-content-between">
                            <div><input type="checkbox" class="form-check-input me-2"> Luxury</div>
                            <span class="small">8</span>
                        </label>
                    </div>

                    <h6 class="fw-bold mb-3">Transmission</h6>
                    <div class="d-flex gap-2 mb-4">
                        <button class="btn btn-dark btn-sm rounded-pill px-4 py-2">Automatic</button>
                        <button class="btn btn-outline-secondary btn-sm rounded-pill px-4 py-2">Manual</button>
                    </div>

                    <h6 class="fw-bold mb-3">Price Range</h6>
                    <input type="range" class="form-range mb-2" min="0" max="500">
                    <div class="d-flex justify-content-between text-muted small mb-4">
                        <span>0 €</span>
                        <span>500+ €</span>
                    </div>

                    <button class="btn btn-warning w-100 fw-bold rounded-pill py-2">Apply Filters</button>
                </div>
            </div>

            <!-- Car Grid -->
            <div class="col-lg-9">
                <div class="d-flex justify-content-between align-items-center mb-4 pb-2">
                    <h4 class="fw-bold mb-0">Recommended Cars For You</h4>
                    <select class="form-select border-0 bg-white shadow-sm rounded-pill px-4 py-2 w-auto" style="font-size: 0.95rem;">
                        <option>Sort by: Recommended</option>
                        <option>Price: Low to High</option>
                        <option>Price: High to Low</option>
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
                                        <span class="text-muted small d-block" style="line-height: 1;">Daily rate from</span>
                                        <h5 class="text-dark fw-bold mb-0 mt-1"><?= number_format($car['prix_journalier'], 0, ',', ' ') ?> €</h5>
                                    </div>
                                    <span class="btn btn-dark rounded-pill px-4 py-2 fw-bold">Rent Now</span>
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
PHP
];

foreach ($files as $filepath => $content) {
    file_put_contents($filepath, $content);
}
echo "Design Desktop corrigé.";
?>
