<?php
$files = [
    'app/views/front/search_results.php' => <<<'PHP'
<?php $title = "Résultats de recherche"; ob_start(); ?>
<div class="container-fluid px-4 px-md-5 mt-4 mt-md-5 mb-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="fw-bold text-dark">Vehicles available from <?= htmlspecialchars(date('d/m/Y', strtotime($_GET['date_debut']))) ?> to <?= htmlspecialchars(date('d/m/Y', strtotime($_GET['date_fin']))) ?></h3>
        <a href="index.php" class="btn btn-outline-dark rounded-pill px-4"><i class="fa-solid fa-arrow-left me-2"></i> Change Dates</a>
    </div>
    
    <?php if(empty($cars)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center">
            <i class="fa-solid fa-car-side fa-4x text-muted mb-3"></i>
            <h4 class="fw-bold">No cars available</h4>
            <p class="text-muted">Please try different dates or contact our agency.</p>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($cars as $car): ?>
            <div class="col-md-6 col-lg-4 col-xl-3">
                <a href="index.php?action=reserve&id=<?= $car['id'] ?>&date_debut=<?= $_GET['date_debut'] ?>&date_fin=<?= $_GET['date_fin'] ?>" class="car-card text-decoration-none h-100 d-flex flex-column">
                    <div class="fav-btn"><i class="fa-regular fa-heart"></i></div>
                    <img src="<?= htmlspecialchars($car['image_principale']) ?>" alt="<?= htmlspecialchars($car['modele']) ?>" style="height: 160px; object-fit: contain;">
                    <div class="mt-auto">
                        <span class="badge bg-light text-dark mb-2 px-3 py-2 rounded-pill border"><?= htmlspecialchars($car['categorie_nom'] ?? 'Auto') ?></span>
                        <h5 class="fw-bold mb-1 text-dark fs-5"><?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></h5>
                        <p class="text-muted small mb-3"><i class="fa-solid fa-gas-pump me-1"></i> <?= htmlspecialchars($car['type_carburant']) ?> &bull; <i class="fa-solid fa-gears me-1"></i> <?= htmlspecialchars($car['boite_vitesse']) ?></p>
                        <div class="d-flex justify-content-between align-items-center border-top pt-3">
                            <div>
                                <span class="text-muted small d-block" style="line-height: 1;">Daily rate</span>
                                <h5 class="text-success fw-bold mb-0 mt-1"><?= number_format($car['prix_journalier'], 0, ',', ' ') ?> €</h5>
                            </div>
                            <span class="btn btn-dark rounded-pill px-4 py-2 fw-bold">Rent Now</span>
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
        <!-- Informations Voiture -->
        <div class="col-lg-4 order-lg-2">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden position-sticky" style="top: 100px;">
                <div class="p-4 text-center bg-light">
                    <img src="<?= htmlspecialchars($car['image_principale']) ?>" class="img-fluid" style="height: 200px; object-fit: contain;">
                </div>
                <div class="card-body p-4 bg-white">
                    <span class="badge bg-dark text-white mb-2 px-3 py-2 rounded-pill"><?= htmlspecialchars($car['categorie_nom']) ?></span>
                    <h3 class="fw-bold mb-4"><?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></h3>
                    
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                        <span class="text-muted">Daily Rate</span>
                        <span class="fw-bold text-success fs-5"><?= number_format($car['prix_journalier'], 0, ',', ' ') ?> €</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                        <span class="text-muted">Security Deposit <i class="fa-solid fa-circle-info small"></i></span>
                        <span class="fw-bold"><?= number_format($car['caution'], 0, ',', ' ') ?> €</span>
                    </div>
                    
                    <div class="bg-light p-3 rounded-4 mt-4">
                        <h6 class="fw-bold mb-3"><i class="fa-solid fa-shield-halved text-warning me-2"></i> Protection Included</h6>
                        <ul class="list-unstyled mb-0 small text-muted">
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Theft Protection</li>
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Third Party Liability</li>
                            <li><i class="fa-solid fa-check text-success me-2"></i> Free Cancellation</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="col-lg-8 order-lg-1">
            <a href="javascript:history.back()" class="text-decoration-none text-muted mb-4 d-inline-block"><i class="fa-solid fa-arrow-left me-2"></i> Back to search</a>
            
            <h2 class="fw-bold mb-4">Complete your booking</h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger rounded-4 border-0 shadow-sm"><i class="fa-solid fa-triangle-exclamation me-2"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form action="index.php?action=confirm_reserve" method="POST">
                <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                
                <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
                    <h5 class="fw-bold mb-4"><span class="badge bg-warning text-dark rounded-circle me-2" style="width: 25px; height: 25px; display: inline-flex; align-items: center; justify-content: center;">1</span> Rental Period</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Pick-up Date</label>
                            <input type="date" name="date_debut" class="form-control form-control-lg bg-light border-0 rounded-4" value="<?= $_GET['date_debut'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Drop-off Date</label>
                            <input type="date" name="date_fin" class="form-control form-control-lg bg-light border-0 rounded-4" value="<?= $_GET['date_fin'] ?? '' ?>" required>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
                    <h5 class="fw-bold mb-4"><span class="badge bg-warning text-dark rounded-circle me-2" style="width: 25px; height: 25px; display: inline-flex; align-items: center; justify-content: center;">2</span> Locations</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Pick-up Location</label>
                            <select name="lieu_prise_id" class="form-select form-select-lg bg-light border-0 rounded-4" required>
                                <option value="">Select a location...</option>
                                <?php foreach($locations as $loc): ?>
                                    <option value="<?= $loc['id'] ?>"><?= htmlspecialchars($loc['nom']) ?> (+<?= $loc['frais_supplementaire'] ?>€)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Drop-off Location</label>
                            <select name="lieu_retour_id" class="form-select form-select-lg bg-light border-0 rounded-4" required>
                                <option value="">Select a location...</option>
                                <?php foreach($locations as $loc): ?>
                                    <option value="<?= $loc['id'] ?>"><?= htmlspecialchars($loc['nom']) ?> (+<?= $loc['frais_supplementaire'] ?>€)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4 rounded-4 mb-5">
                    <h5 class="fw-bold mb-4"><span class="badge bg-warning text-dark rounded-circle me-2" style="width: 25px; height: 25px; display: inline-flex; align-items: center; justify-content: center;">3</span> Extras</h5>
                    
                    <div class="form-check form-switch d-flex align-items-center p-3 border rounded-4">
                        <input class="form-check-input ms-0 mt-0" type="checkbox" role="switch" id="chauffeur" name="avec_chauffeur" value="1" style="width: 40px; height: 20px; cursor: pointer;">
                        <label class="form-check-label ms-3 w-100 d-flex justify-content-between align-items-center" for="chauffeur" style="cursor: pointer;">
                            <div>
                                <strong class="d-block">Private Driver</strong>
                                <small class="text-muted">Relax and let a professional drive you.</small>
                            </div>
                            <span class="badge bg-dark rounded-pill px-3 py-2">+150 € / day</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-dark btn-lg w-100 fw-bold rounded-pill shadow" style="height: 60px;">Confirm Booking & Pay Later <i class="fa-solid fa-arrow-right ms-2"></i></button>
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
                    <h2 class="fw-bold mt-3">Welcome Back</h2>
                    <p class="text-muted">Sign in to manage your bookings</p>
                </div>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger rounded-4 border-0 text-start"><i class="fa-solid fa-circle-exclamation me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success rounded-4 border-0 text-start"><i class="fa-solid fa-circle-check me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <form action="index.php?action=do_login" method="POST" class="text-start">
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small ms-2">Email Address</label>
                        <input type="email" name="email" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small ms-2">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" required>
                    </div>
                    <button type="submit" class="btn btn-dark btn-lg w-100 fw-bold rounded-pill mb-4" style="height: 55px;">Sign In</button>
                    <div class="text-center">
                        <span class="text-muted">Don't have an account?</span> <a href="index.php?action=register" class="text-dark fw-bold text-decoration-none">Sign up</a>
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
                    <h2 class="fw-bold mt-3">Create an Account</h2>
                    <p class="text-muted">Join AutoRent and hit the road</p>
                </div>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger rounded-4 border-0 text-start"><i class="fa-solid fa-circle-exclamation me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form action="index.php?action=do_register" method="POST" class="text-start">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small ms-2">First Name</label>
                            <input type="text" name="prenom" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small ms-2">Last Name</label>
                            <input type="text" name="nom" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small ms-2">Email Address</label>
                        <input type="email" name="email" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small ms-2">Password</label>
                        <input type="password" name="password" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" required>
                    </div>
                    <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold rounded-pill mb-4 shadow-sm" style="height: 55px;">Create Account</button>
                    <div class="text-center">
                        <span class="text-muted">Already have an account?</span> <a href="index.php?action=login" class="text-dark fw-bold text-decoration-none">Sign in</a>
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
        <h2 class="fw-bold mb-0">My Bookings</h2>
        <a href="index.php" class="btn btn-dark rounded-pill px-4"><i class="fa-solid fa-plus me-2"></i> New Booking</a>
    </div>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success border-0 shadow-sm rounded-4"><i class="fa-solid fa-circle-check me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if(empty($reservations)): ?>
        <div class="card border-0 shadow-sm rounded-4 p-5 text-center bg-light">
            <i class="fa-regular fa-calendar-xmark fa-4x text-muted mb-3"></i>
            <h4 class="fw-bold">No bookings yet</h4>
            <p class="text-muted">When you book a car, it will appear here.</p>
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
                                <p class="text-muted small mb-3"><i class="fa-regular fa-calendar me-2"></i> <?= date('M d, Y', strtotime($r['date_debut'])) ?> &rarr; <?= date('M d, Y', strtotime($r['date_fin'])) ?></p>
                                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                                    <div>
                                        <small class="text-muted d-block" style="line-height:1;">Total Cost</small>
                                        <span class="fw-bold fs-5 text-success"><?= number_format($r['prix_total'], 0, ',', ' ') ?> €</span>
                                    </div>
                                    <button class="btn btn-outline-dark btn-sm rounded-pill px-3">Details</button>
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
PHP,

    'app/views/layouts/admin.php' => <<<'PHP'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoRent Workspace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f6f8fa; color: #1a1a1a; }
        .sidebar { min-height: 100vh; background: #ffffff; border-right: 1px solid #eee; padding-top: 30px; position: sticky; top: 0; }
        .sidebar a { color: #666; text-decoration: none; padding: 15px 30px; display: flex; align-items: center; font-weight: 500; transition: 0.2s; border-right: 4px solid transparent; }
        .sidebar a:hover, .sidebar a.active { color: #1a1a1a; background: #fbfbfb; border-right: 4px solid #f4c053; font-weight: 600; }
        .sidebar i { width: 25px; font-size: 1.1rem; }
        .card { border: none; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); }
        .table > :not(caption) > * > * { padding: 15px 20px; border-bottom-color: #f0f0f0; }
        .badge { border-radius: 8px; font-weight: 500; padding: 6px 12px; }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar" style="width: 280px; flex-shrink: 0;">
            <div class="text-center mb-5 px-4">
                <h3 class="fw-bold text-dark"><i class="fa-solid fa-car-side" style="color: #f4c053;"></i> AutoRent</h3>
                <span class="badge bg-dark text-warning rounded-pill mt-1">Admin Workspace</span>
            </div>
            
            <p class="text-muted small fw-bold px-4 mb-2 ms-2">MENU</p>
            <a href="index.php?action=admin_dashboard" class="<?= (isset($_GET['action']) && $_GET['action']=='admin_dashboard')?'active':'' ?>"><i class="fa-solid fa-gauge"></i> Dashboard</a>
            <a href="index.php?action=admin_reservations" class="<?= (isset($_GET['action']) && strpos($_GET['action'], 'admin_res')!==false)?'active':'' ?>"><i class="fa-solid fa-calendar-check"></i> Bookings</a>
            <a href="index.php?action=admin_cars" class="<?= (isset($_GET['action']) && strpos($_GET['action'], 'admin_car')!==false)?'active':'' ?>"><i class="fa-solid fa-car"></i> Fleet</a>
            
            <p class="text-muted small fw-bold px-4 mt-5 mb-2 ms-2">OTHER</p>
            <a href="index.php" class="text-primary"><i class="fa-solid fa-globe"></i> View Website</a>
            <a href="index.php?action=logout" class="text-danger mt-2"><i class="fa-solid fa-power-off"></i> Logout</a>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-5 overflow-auto" style="height: 100vh;">
            <!-- Header Top Bar -->
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div class="search-bar w-50 bg-white rounded-pill px-4 py-2 d-flex align-items-center shadow-sm border">
                    <i class="fa-solid fa-magnifying-glass text-muted me-3"></i>
                    <input type="text" class="border-0 bg-transparent w-100 outline-none" placeholder="Search reservations, cars, customers..." style="outline:none;">
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm border" style="width: 45px; height: 45px;">
                        <i class="fa-regular fa-bell"></i>
                    </div>
                    <img src="https://ui-avatars.com/api/?name=Admin&background=1a1a1a&color=fff" class="rounded-circle shadow-sm" style="width: 45px;">
                </div>
            </div>

            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 shadow-sm mb-4">
                    <i class="fa-solid fa-circle-check me-2"></i><?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?= $content ?>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
PHP,

    'app/views/admin/dashboard.php' => <<<'PHP'
<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <h2 class="fw-bold mb-1">Dashboard Overview</h2>
        <p class="text-muted">Welcome back, Admin. Here's what's happening today.</p>
    </div>
    <button class="btn btn-dark rounded-pill px-4"><i class="fa-solid fa-download me-2"></i> Export Report</button>
</div>

<!-- Statistiques -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card bg-white h-100">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="bg-success bg-opacity-10 text-success rounded-circle d-flex align-items-center justify-content-center me-4" style="width:60px; height:60px; font-size: 1.5rem;">
                    <i class="fa-solid fa-euro-sign"></i>
                </div>
                <div>
                    <p class="text-muted small fw-bold mb-1">Monthly Revenue</p>
                    <h3 class="fw-bold mb-0"><?= number_format($stats['ca_mensuel'], 0, ',', ' ') ?> €</h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-white h-100">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-4" style="width:60px; height:60px; font-size: 1.5rem;">
                    <i class="fa-solid fa-car"></i>
                </div>
                <div>
                    <p class="text-muted small fw-bold mb-1">Total Fleet</p>
                    <h3 class="fw-bold mb-0"><?= $stats['total_cars'] ?> <span class="fs-6 text-muted fw-normal">cars</span></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-white h-100">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="bg-warning bg-opacity-10 text-warning rounded-circle d-flex align-items-center justify-content-center me-4" style="width:60px; height:60px; font-size: 1.5rem;">
                    <i class="fa-regular fa-clock"></i>
                </div>
                <div>
                    <p class="text-muted small fw-bold mb-1">Pending Bookings</p>
                    <h3 class="fw-bold mb-0"><?= $stats['attente'] ?></h3>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-dark text-white h-100" style="background: linear-gradient(135deg, #1a1a1a 0%, #333 100%);">
            <div class="card-body p-4 d-flex align-items-center">
                <div class="bg-white bg-opacity-10 text-white rounded-circle d-flex align-items-center justify-content-center me-4" style="width:60px; height:60px; font-size: 1.5rem;">
                    <i class="fa-solid fa-calendar-check"></i>
                </div>
                <div>
                    <p class="text-white-50 small fw-bold mb-1">Total Bookings</p>
                    <h3 class="fw-bold mb-0 text-warning"><?= $stats['total_reservations'] ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dernières réservations -->
<div class="card mb-5">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Recent Bookings</h5>
        <a href="index.php?action=admin_reservations" class="btn btn-light btn-sm rounded-pill px-3">View All</a>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="text-muted small text-uppercase" style="letter-spacing: 1px;">
                    <tr>
                        <th>Customer</th>
                        <th>Vehicle</th>
                        <th>Period</th>
                        <th>Amount</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php foreach($recent_reservations as $r): ?>
                    <tr>
                        <td class="fw-bold text-dark"><?= htmlspecialchars($r['nom'].' '.$r['prenom']) ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-light rounded p-2 me-3"><i class="fa-solid fa-car text-muted"></i></div>
                                <span class="fw-medium"><?= htmlspecialchars($r['marque'].' '.$r['modele']) ?></span>
                            </div>
                        </td>
                        <td class="text-muted"><i class="fa-regular fa-calendar me-2"></i><?= date('M d', strtotime($r['date_debut'])) ?> &rarr; <?= date('M d', strtotime($r['date_fin'])) ?></td>
                        <td class="fw-bold text-success"><?= number_format($r['prix_total'], 0, ',', ' ') ?> €</td>
                        <td>
                            <?php 
                            $badge = match($r['status_reservation']) {
                                'en_attente' => 'bg-warning text-dark',
                                'validee' => 'bg-success',
                                'en_cours' => 'bg-primary',
                                'terminee' => 'bg-secondary',
                                'annulee' => 'bg-danger',
                                default => 'bg-light text-dark'
                            };
                            ?>
                            <span class="badge <?= $badge ?> rounded-pill px-3"><?= ucfirst($r['status_reservation']) ?></span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>
PHP
];

foreach ($files as $filepath => $content) {
    file_put_contents($filepath, $content);
}
echo "Toutes les pages ont été harmonisées avec le design système.";
?>
