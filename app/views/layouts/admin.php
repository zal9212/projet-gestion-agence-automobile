<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Teranga Auto ERP Workspace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f4f7f6; color: #1a1a1a; overflow-x: hidden; }
        /* Desktop Sidebar */
        .sidebar { min-height: 100vh; background: #ffffff; border-right: 1px solid #eee; padding-top: 30px; position: sticky; top: 0; overflow-y: auto; width: 280px; flex-shrink: 0; }
        .sidebar-link { color: #666; text-decoration: none; padding: 12px 30px; display: flex; align-items: center; font-weight: 500; transition: 0.2s; border-right: 4px solid transparent; font-size: 0.95rem; }
        .sidebar-link:hover, .sidebar-link.active { color: #1a1a1a; background: #fbfbfb; border-right: 4px solid #f4c053; font-weight: 600; }
        .sidebar-link i { width: 25px; font-size: 1.1rem; }
        
        .card { border: none; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); }
        .main-content { height: 100vh; overflow-y: auto; width: 100%; }
        
        /* Mobile Adjustments */
        .mobile-header { display: none; background: white; padding: 15px 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); position: sticky; top: 0; z-index: 1000; }
        @media (max-width: 991.98px) {
            .sidebar { display: none; } /* Hide desktop sidebar */
            .mobile-header { display: flex; justify-content: space-between; align-items: center; }
            .main-content { height: auto; overflow-y: visible; padding: 15px !important; }
            .search-bar-container { display: none !important; } /* Hide big search on mobile */
            h2 { font-size: 1.5rem !important; }
        }
        
        /* Tableaux adaptatifs */
        .table-responsive { border-radius: 15px; }
        .table-responsive table th, .table-responsive table td { white-space: nowrap; }
        .gantt-container { overflow-x: auto; -webkit-overflow-scrolling: touch; }
    </style>
</head>
<body>

    <!-- En-tête Mobile (Visible uniquement sur petits écrans) -->
    <div class="mobile-header">
        <a href="index.php?action=admin_dashboard"><img src="logo.png" alt="Teranga Auto Logo" style="height: 40px;"></a>
        <button class="btn btn-light border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileSidebar">
            <i class="fa-solid fa-bars fs-4"></i>
        </button>
    </div>

    <!-- Menu latéral escamotable pour Mobile -->
    <div class="offcanvas offcanvas-start border-0" tabindex="-1" id="mobileSidebar">
        <div class="offcanvas-header pt-4 px-4">
            <img src="logo.png" alt="Teranga Auto Logo" style="height: 50px;">
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0 pt-3">
            <p class="text-muted small fw-bold px-4 mb-2 ms-2 mt-2" style="letter-spacing: 1px;">CŒUR DE MÉTIER</p>
            <a href="index.php?action=admin_dashboard" class="sidebar-link <?= (isset($_GET['action']) && $_GET['action']=='admin_dashboard')?'active':'' ?>"><i class="fa-solid fa-gauge"></i> Tableau de bord</a>
            <a href="index.php?action=admin_gantt" class="sidebar-link <?= (isset($_GET['action']) && $_GET['action']=='admin_gantt')?'active':'' ?>"><i class="fa-solid fa-chart-gantt"></i> Planning Gantt</a>
            <a href="index.php?action=admin_reservations" class="sidebar-link <?= (isset($_GET['action']) && $_GET['action']=='admin_reservations')?'active':'' ?>"><i class="fa-solid fa-calendar-check"></i> Réservations</a>
            <a href="index.php?action=admin_cars" class="sidebar-link <?= (isset($_GET['action']) && strpos($_GET['action'], 'admin_car')!==false)?'active':'' ?>"><i class="fa-solid fa-car"></i> Flotte de Véhicules</a>
            
            <p class="text-muted small fw-bold px-4 mb-2 ms-2 mt-4" style="letter-spacing: 1px;">OPÉRATIONS</p>
            <a href="index.php?action=admin_checkin" class="sidebar-link <?= (isset($_GET['action']) && $_GET['action']=='admin_checkin')?'active':'' ?>"><i class="fa-solid fa-clipboard-check"></i> Départs / Retours</a>
            <a href="index.php?action=admin_maintenance" class="sidebar-link <?= (isset($_GET['action']) && $_GET['action']=='admin_maintenance')?'active':'' ?>"><i class="fa-solid fa-wrench"></i> Maintenance</a>
            
            <p class="text-muted small fw-bold px-4 mb-2 ms-2 mt-4" style="letter-spacing: 1px;">FINANCE & CLIENTS</p>
            <a href="index.php?action=admin_crm" class="sidebar-link <?= (isset($_GET['action']) && $_GET['action']=='admin_crm')?'active':'' ?>"><i class="fa-solid fa-users"></i> CRM & Clients</a>
            <?php if($_SESSION['user_role'] === 'admin'): ?>
                <a href="index.php?action=admin_staff" class="sidebar-link <?= (isset($_GET['action']) && strpos($_GET['action'], 'admin_staff')!==false)?'active':'' ?>"><i class="fa-solid fa-user-shield"></i> Personnel (Staff)</a>
            <?php endif; ?>
            
            <p class="text-muted small fw-bold px-4 mt-5 mb-2 ms-2">AUTRES</p>
            <a href="index.php" class="sidebar-link text-primary"><i class="fa-solid fa-globe"></i> Voir le site</a>
            <a href="index.php?action=logout" class="sidebar-link text-danger mt-2"><i class="fa-solid fa-power-off"></i> Déconnexion</a>
        </div>
    </div>

    <!-- Desktop Layout -->
    <div class="d-flex">
        <!-- Desktop Sidebar (Hidden on mobile) -->
        <div class="sidebar d-none d-lg-block">
            <div class="text-center mb-4 px-4">
                <img src="logo.png" alt="Teranga Auto Logo" style="height: 75px;" class="mb-2">
                <br>
                <span class="badge bg-dark text-warning rounded-pill mt-1">
                    <?= $_SESSION['user_role'] === 'admin' ? 'ERP Admin' : 'Workspace Agent' ?>
                </span>
            </div>
            
            <p class="text-muted small fw-bold px-4 mb-2 ms-2 mt-4" style="letter-spacing: 1px;">CŒUR DE MÉTIER</p>
            <a href="index.php?action=admin_dashboard" class="sidebar-link <?= (isset($_GET['action']) && $_GET['action']=='admin_dashboard')?'active':'' ?>"><i class="fa-solid fa-gauge"></i> Tableau de bord</a>
            <a href="index.php?action=admin_gantt" class="sidebar-link <?= (isset($_GET['action']) && $_GET['action']=='admin_gantt')?'active':'' ?>"><i class="fa-solid fa-chart-gantt"></i> Planning Gantt</a>
            <a href="index.php?action=admin_reservations" class="sidebar-link <?= (isset($_GET['action']) && $_GET['action']=='admin_reservations')?'active':'' ?>"><i class="fa-solid fa-calendar-check"></i> Réservations</a>
            <a href="index.php?action=admin_cars" class="sidebar-link <?= (isset($_GET['action']) && strpos($_GET['action'], 'admin_car')!==false)?'active':'' ?>"><i class="fa-solid fa-car"></i> Flotte de Véhicules</a>
            
            <p class="text-muted small fw-bold px-4 mb-2 ms-2 mt-4" style="letter-spacing: 1px;">OPÉRATIONS</p>
            <a href="index.php?action=admin_checkin" class="sidebar-link <?= (isset($_GET['action']) && $_GET['action']=='admin_checkin')?'active':'' ?>"><i class="fa-solid fa-clipboard-check"></i> Départs / Retours</a>
            <a href="index.php?action=admin_maintenance" class="sidebar-link <?= (isset($_GET['action']) && $_GET['action']=='admin_maintenance')?'active':'' ?>"><i class="fa-solid fa-wrench"></i> Maintenance</a>
            
            <p class="text-muted small fw-bold px-4 mb-2 ms-2 mt-4" style="letter-spacing: 1px;">FINANCE & CLIENTS</p>
            <a href="index.php?action=admin_crm" class="sidebar-link <?= (isset($_GET['action']) && $_GET['action']=='admin_crm')?'active':'' ?>"><i class="fa-solid fa-users"></i> CRM & Clients</a>
            <?php if($_SESSION['user_role'] === 'admin'): ?>
                <a href="index.php?action=admin_staff" class="sidebar-link <?= (isset($_GET['action']) && strpos($_GET['action'], 'admin_staff')!==false)?'active':'' ?>"><i class="fa-solid fa-user-shield"></i> Personnel (Staff)</a>
            <?php endif; ?>
            
            <p class="text-muted small fw-bold px-4 mt-5 mb-2 ms-2">AUTRES</p>
            <a href="index.php" class="sidebar-link text-primary"><i class="fa-solid fa-globe"></i> Voir le site</a>
            <a href="index.php?action=logout" class="sidebar-link text-danger mt-2 mb-5"><i class="fa-solid fa-power-off"></i> Déconnexion</a>
        </div>

        <!-- Main Content Area -->
        <div class="main-content p-4 p-md-5">
            <!-- Desktop Topbar (Hidden on mobile) -->
            <div class="d-flex justify-content-between align-items-center mb-4 mb-md-5 search-bar-container">
                <div class="search-bar w-50 bg-white rounded-pill px-4 py-2 d-flex align-items-center shadow-sm border">
                    <i class="fa-solid fa-magnifying-glass text-muted me-3"></i>
                    <input type="text" class="border-0 bg-transparent w-100" placeholder="Rechercher un VIN, client, réservation..." style="outline:none;">
                </div>
                <div class="d-flex align-items-center gap-3">
                    <!-- Notifications Dropdown -->
                    <div class="dropdown">
                        <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm border position-relative" style="width: 45px; height: 45px; cursor:pointer;" data-bs-toggle="dropdown">
                            <i class="fa-regular fa-bell"></i>
                            <?php $notif_count = count_unread_notifications($_SESSION['user_id']); ?>
                            <?php if($notif_count > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.6rem;">
                                    <?= $notif_count ?>
                                </span>
                            <?php endif; ?>
                        </div>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-3 rounded-4" style="width: 300px;">
                            <li class="px-2 mb-2"><h6 class="fw-bold mb-0">Notifications</h6></li>
                            <?php $notifs = get_unread_notifications($_SESSION['user_id']); ?>
                            <?php if(empty($notifs)): ?>
                                <li class="text-center py-3 text-muted small">Aucune nouvelle notification</li>
                            <?php else: ?>
                                <?php foreach($notifs as $n): ?>
                                    <li><a class="dropdown-item rounded-3 mb-1 p-2 text-wrap" href="index.php?action=notif_read&id=<?= $n['id'] ?>">
                                        <div class="small fw-bold"><?= htmlspecialchars($n['message']) ?></div>
                                        <div class="text-muted" style="font-size: 0.7rem;"><?= date('H:i', strtotime($n['created_at'])) ?></div>
                                    </a></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <a href="index.php?action=admin_profile">
                        <?php if(!empty($_SESSION['user_photo'])): ?>
                            <img src="<?= htmlspecialchars($_SESSION['user_photo']) ?>" class="rounded-circle shadow-sm" style="width: 45px; height: 45px; object-fit: cover; border: 2px solid var(--accent-yellow);">
                        <?php else: ?>
                            <img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_prenom']) ?>&background=1a1a1a&color=fff" class="rounded-circle shadow-sm" style="width: 45px;">
                        <?php endif; ?>
                    </a>
                </div>
            </div>
            
            <!-- Injected View Content -->
            <?= $content ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>