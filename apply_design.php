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
            --primary-bg: #f9f9f9;
            --dark-color: #1a1a1a;
            --accent-yellow: #f4c053; /* Jaune chaud identique à l'image */
            --light-grey: #f0f2f5;
        }
        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--primary-bg);
            color: var(--dark-color);
            padding-bottom: 90px; /* Espace pour bottom nav sur mobile */
        }
        
        /* Ajustements Desktop */
        @media (min-width: 768px) {
            .app-container {
                max-width: 1000px;
                margin: 0 auto;
                padding: 0 20px;
                background: white;
                min-height: 100vh;
                box-shadow: 0 0 40px rgba(0,0,0,0.05);
            }
            .bottom-nav { display: none !important; }
            body { padding-bottom: 0; background-color: #eef0f3; }
            .desktop-nav { display: flex !important; }
            .mobile-topbar { display: none !important; }
        }

        /* Topbar Mobile */
        .mobile-topbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px 20px 10px 20px;
        }
        .icon-btn {
            width: 40px; height: 40px;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            text-decoration: none; color: var(--dark-color);
            font-size: 1.2rem;
        }

        /* Navbar Desktop */
        .desktop-nav {
            display: none;
            justify-content: space-between;
            align-items: center;
            padding: 30px 20px;
            border-bottom: 1px solid #eee;
        }
        .desktop-nav .nav-links a {
            color: var(--dark-color);
            text-decoration: none;
            font-weight: 500;
            margin-left: 25px;
            transition: 0.2s;
        }
        .desktop-nav .nav-links a:hover { color: var(--accent-yellow); }

        /* Navigation Bottom (Mobile) */
        .bottom-nav {
            position: fixed;
            bottom: 20px; left: 20px; right: 20px;
            background: white;
            border-radius: 30px;
            display: flex;
            justify-content: space-around;
            align-items: center;
            padding: 15px 0;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
            z-index: 1000;
        }
        .bottom-nav a {
            color: #b0b0b0;
            font-size: 1.3rem;
            padding: 10px 20px;
            border-radius: 20px;
            text-decoration: none;
            transition: all 0.3s ease;
            display: flex; align-items: center; justify-content: center;
        }
        .bottom-nav a.active {
            background: var(--dark-color);
            color: white;
        }

        /* Barre de Recherche */
        .search-bar {
            background: white;
            border-radius: 25px;
            padding: 5px 5px 5px 20px;
            display: flex; align-items: center;
            box-shadow: 0 8px 20px rgba(0,0,0,0.03);
            margin-bottom: 30px;
        }
        .search-bar input {
            border: none; background: transparent; box-shadow: none; font-size: 1rem;
        }
        .search-bar input:focus { outline: none; box-shadow: none; }
        .filter-btn {
            background: var(--dark-color); color: white; border-radius: 50%;
            width: 45px; height: 45px; display: flex; align-items: center; justify-content: center;
            cursor: pointer;
        }

        /* Boutons Pillules (Filtres) */
        .pill-btn {
            background: white; border: 1px solid #f0f0f0; border-radius: 25px;
            padding: 10px 20px; font-size: 0.9rem; color: #666; font-weight: 500;
            white-space: nowrap; cursor: pointer; text-decoration: none; display: inline-block;
            box-shadow: 0 2px 10px rgba(0,0,0,0.02);
        }
        .pill-btn.active { background: var(--dark-color); color: white; border-color: var(--dark-color); }
        
        .horizontal-scroll {
            display: flex; overflow-x: auto; gap: 12px; padding-bottom: 5px; margin-bottom: 25px;
            scrollbar-width: none;
        }
        .horizontal-scroll::-webkit-scrollbar { display: none; }

        /* Cartes Voitures */
        .car-card {
            background: white; border-radius: 24px; padding: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.03); border: 1px solid #f9f9f9;
            transition: transform 0.2s; position: relative; display: block; text-decoration: none;
        }
        .car-card:hover { transform: translateY(-5px); }
        .car-card img { width: 100%; height: 110px; object-fit: contain; margin-bottom: 10px; }
        .fav-btn {
            position: absolute; top: 12px; right: 12px; background: #fff; border: 1px solid #eee;
            border-radius: 50%; width: 32px; height: 32px; display: flex; align-items: center; justify-content: center;
            color: #ccc; cursor: pointer; z-index: 10;
        }
        .fav-btn.active { color: #ff4757; }
        
        /* Bannière Jaune */
        .yellow-banner {
            background: var(--accent-yellow); border-radius: 28px; padding: 25px 30px;
            display: flex; justify-content: space-between; align-items: center; margin-bottom: 35px;
            position: relative; overflow: hidden;
        }
        .yellow-banner h3 { font-weight: 700; margin-bottom: 5px; color: var(--dark-color); }
        .yellow-banner p { color: #554315; font-size: 0.9rem; margin-bottom: 20px; }
        .btn-dark-pill { background: var(--dark-color); color: white; border-radius: 20px; padding: 8px 25px; font-weight: 500; text-decoration: none; font-size: 0.9rem; display: inline-block; }
        .yellow-banner img { position: absolute; right: -20px; bottom: 0; max-width: 180px; }
        
        @media (max-width: 768px) {
            .yellow-banner img { max-width: 140px; right: -10px; }
        }
    </style>
</head>
<body>

    <div class="app-container">
        <!-- Header Desktop -->
        <header class="desktop-nav">
            <h3 class="fw-bold mb-0"><i class="fa-solid fa-car-side" style="color: var(--accent-yellow);"></i> AutoRent</h3>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="index.php?action=search">Explore</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="index.php?action=history">My Bookings</a>
                    <?php if($_SESSION['user_role'] === 'admin'): ?>
                        <a href="index.php?action=admin_dashboard" style="color: var(--accent-yellow);">Dashboard</a>
                    <?php endif; ?>
                    <a href="index.php?action=logout" class="ms-4"><img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_prenom']) ?>&background=random" style="width: 40px; border-radius: 50%;"></a>
                <?php else: ?>
                    <a href="index.php?action=login" class="btn btn-dark rounded-pill px-4 ms-3 text-white">Login</a>
                <?php endif; ?>
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

        <main class="pb-4">
            <?= $content ?>
        </main>
    </div>

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

<div class="px-4 px-md-5 mt-2 mt-md-5 pt-3">
    <!-- Titre et Greeting -->
    <?php if(isset($_SESSION['user_id'])): ?>
        <h1 class="fw-bold mb-1">Hi <?= htmlspecialchars($_SESSION['user_prenom']) ?>!</h1>
    <?php else: ?>
        <h1 class="fw-bold mb-1">Welcome!</h1>
    <?php endif; ?>
    <p class="text-muted mb-4" style="font-size: 0.95rem;">Search your favourite car here..</p>

    <!-- Barre de recherche stylisée -->
    <form action="index.php" method="GET" class="search-bar">
        <input type="hidden" name="action" value="search">
        <i class="fa-solid fa-magnifying-glass text-muted ms-2 fs-5"></i>
        <input type="text" class="form-control" name="q" placeholder="Search products" style="height: 45px;">
        <button type="submit" class="border-0 p-0 bg-transparent">
            <div class="filter-btn"><i class="fa-solid fa-sliders"></i></div>
        </button>
    </form>

    <!-- Bannière Jaune (Explore Latest) -->
    <div class="yellow-banner">
        <div style="position: relative; z-index: 2;">
            <h3 class="fs-4">Explore Latest</h3>
            <p>Cars with Price</p>
            <a href="index.php?action=search" class="btn-dark-pill">Explore</a>
        </div>
        <!-- Image de SUV transparente -->
        <img src="https://pngimg.com/uploads/suv/suv_PNG31.png" alt="SUV Car">
    </div>

    <!-- Filtres : Popular Segments -->
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h6 class="fw-bold mb-0">Popular Segments</h6>
    </div>
    <div class="horizontal-scroll">
        <a href="#" class="pill-btn active">SUV</a>
        <a href="#" class="pill-btn">Sedan</a>
        <a href="#" class="pill-btn">Hatchback</a>
        <a href="#" class="pill-btn">Convertible</a>
        <a href="#" class="pill-btn">Minivan</a>
    </div>

    <!-- Filtres : All Brands -->
    <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
        <h6 class="fw-bold mb-0">All Brands</h6>
    </div>
    <div class="horizontal-scroll text-center align-items-center">
        <!-- Cercles de marques -->
        <div class="me-3">
            <div style="width: 65px; height: 65px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <i class="fa-brands fa-hooli fs-1 text-dark"></i>
            </div>
            <small class="fw-bold text-muted d-block" style="font-size: 0.65rem; letter-spacing: 0.5px;">HYUNDAI</small>
        </div>
        <div class="me-3">
            <div style="width: 65px; height: 65px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <span class="fs-4 fw-bold text-dark">KIA</span>
            </div>
            <small class="fw-bold text-muted d-block" style="font-size: 0.65rem; letter-spacing: 0.5px;">KIA</small>
        </div>
        <div class="me-3">
            <div style="width: 65px; height: 65px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <i class="fa-brands fa-typo3 fs-1 text-dark"></i>
            </div>
            <small class="fw-bold text-muted d-block" style="font-size: 0.65rem; letter-spacing: 0.5px;">TOYOTA</small>
        </div>
        <div class="me-3">
            <div style="width: 65px; height: 65px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin-bottom: 8px; box-shadow: 0 4px 15px rgba(0,0,0,0.03);">
                <span class="fs-4 fw-bold text-dark">TATA</span>
            </div>
            <small class="fw-bold text-muted d-block" style="font-size: 0.65rem; letter-spacing: 0.5px;">TATA</small>
        </div>
    </div>

    <!-- Filtres : Fuel Type -->
    <div class="d-flex justify-content-between align-items-center mb-3 mt-2">
        <h6 class="fw-bold mb-0">Fuel Type</h6>
    </div>
    <div class="horizontal-scroll">
        <a href="#" class="pill-btn active">CNG</a>
        <a href="#" class="pill-btn">Petrol</a>
        <a href="#" class="pill-btn">Diesel</a>
        <a href="#" class="pill-btn">Electric</a>
        <a href="#" class="pill-btn">Hybrid</a>
    </div>

    <!-- Section : Top Cars -->
    <div class="d-flex justify-content-between align-items-center mb-3 mt-3">
        <h6 class="fw-bold mb-0">Top Cars</h6>
        <a href="index.php?action=search" class="text-warning text-decoration-none small fw-bold">View all</a>
    </div>
    
    <div class="row g-3">
        <?php foreach ($cars as $car): ?>
        <div class="col-6 col-md-4">
            <a href="index.php?action=reserve&id=<?= $car['id'] ?>" class="car-card text-decoration-none">
                <div class="fav-btn"><i class="fa-regular fa-heart"></i></div>
                <img src="<?= htmlspecialchars($car['image_principale']) ?>" alt="<?= htmlspecialchars($car['modele']) ?>">
                <h6 class="fw-bold mb-0 text-dark" style="font-size: 0.9rem;"><?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></h6>
                <p class="text-muted mb-0 mt-1" style="font-size: 0.75rem;">
                    <?= number_format($car['prix_journalier'], 0, ',', ' ') ?> € <span style="font-size: 0.65rem;">/day</span>
                </p>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php $content = ob_get_clean(); require 'app/views/layouts/front.php'; ?>
PHP
];

foreach ($files as $filepath => $content) {
    file_put_contents($filepath, $content);
}
echo "Design appliqué avec succès.";
?>
