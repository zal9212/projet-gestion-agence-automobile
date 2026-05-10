<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Teranga Auto - <?= $title ?? 'Location de Voitures' ?></title>
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
    <!-- En-tête Ordinateur -->
    <header class="desktop-nav">
        <div class="navbar-desktop-container">
            <a href="index.php"><img src="logo.png" alt="Teranga Auto Logo" style="height: 60px;"></a>
            <div class="nav-links d-flex align-items-center">
                <a href="index.php">Accueil</a>
                <a href="index.php?action=search">Notre Flotte</a>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <a href="index.php?action=history">Mes Réservations</a>
                    <?php if(in_array($_SESSION['user_role'], ['admin', 'employee'])): ?>
                        <a href="index.php?action=admin_dashboard" style="color: var(--accent-yellow);">
                            <?= $_SESSION['user_role'] === 'admin' ? 'Administration' : 'Espace Agent' ?>
                        </a>
                    <?php endif; ?>
                    
                    <!-- Notifications Desktop -->
                    <div class="dropdown d-inline-block ms-4">
                        <a href="#" class="text-dark text-decoration-none position-relative" data-bs-toggle="dropdown">
                            <i class="fa-regular fa-bell fs-5"></i>
                            <?php $notif_count = count_unread_notifications($_SESSION['user_id']); ?>
                            <?php if($notif_count > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.5rem;"><?= $notif_count ?></span>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-3 rounded-4" style="width: 300px;">
                            <?php $notifs = get_unread_notifications($_SESSION['user_id']); ?>
                            <?php if(empty($notifs)): ?>
                                <li class="text-center py-2 text-muted small">Aucune alerte</li>
                            <?php else: ?>
                                <?php foreach($notifs as $n): ?>
                                    <li><a class="dropdown-item rounded-3 mb-1 p-2" href="index.php?action=notif_read&id=<?= $n['id'] ?>"><small><?= htmlspecialchars($n['message']) ?></small></a></li>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </ul>
                    </div>

                    <a href="index.php?action=profile" class="ms-4"><img src="https://ui-avatars.com/api/?name=<?= urlencode($_SESSION['user_prenom']) ?>&background=random" style="width: 40px; border-radius: 50%;"></a>
                <?php else: ?>
                    <a href="index.php?action=login" class="btn btn-dark rounded-pill px-4 ms-4 text-white">Connexion</a>
                <?php endif; ?>
            </div>
        </div>
    </header>

    <!-- Header Mobile -->
    <div class="mobile-topbar d-md-none">
        <a href="index.php?action=profile" class="icon-btn"><i class="fa-solid fa-user-gear"></i></a>
        <a href="index.php"><img src="logo.png" alt="Teranga Auto Logo" style="height: 45px;"></a>
        <div class="dropdown">
            <a href="#" class="icon-btn position-relative" data-bs-toggle="dropdown">
                <i class="fa-regular fa-bell"></i>
                <?php if(isset($_SESSION['user_id'])): ?>
                    <?php $notif_count = count_unread_notifications($_SESSION['user_id']); ?>
                    <?php if($notif_count > 0): ?>
                        <span class="position-absolute top-25 start-75 translate-middle p-1 bg-danger border border-light rounded-circle" style="top: 10px; left: 30px;"></span>
                    <?php endif; ?>
                <?php endif; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-3 rounded-4" style="width: 280px;">
                <?php if(!isset($_SESSION['user_id'])): ?>
                    <li class="text-center py-2 text-muted small">Connectez-vous pour voir vos alertes</li>
                <?php else: ?>
                    <?php $notifs = get_unread_notifications($_SESSION['user_id']); ?>
                    <?php if(empty($notifs)): ?>
                        <li class="text-center py-2 text-muted small">Pas d'alertes</li>
                    <?php else: ?>
                        <?php foreach($notifs as $n): ?>
                            <li><a class="dropdown-item rounded-3 mb-1 p-2" href="index.php?action=notif_read&id=<?= $n['id'] ?>"><small><?= htmlspecialchars($n['message']) ?></small></a></li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        <?= $content ?>
    </main>

    <!-- Navigation Bottom (Mobile) -->
    <div class="bottom-nav d-md-none">
        <a href="index.php" class="<?= (!isset($_GET['action']) || $_GET['action'] == 'home') ? 'active' : '' ?>"><i class="fa-solid fa-house"></i></a>
        <a href="index.php?action=search" class="<?= (isset($_GET['action']) && $_GET['action'] == 'search') ? 'active' : '' ?>"><i class="fa-solid fa-magnifying-glass"></i></a>
        <a href="index.php?action=favorites" class="<?= (isset($_GET['action']) && $_GET['action'] == 'favorites') ? 'active' : '' ?>"><i class="fa-regular fa-heart"></i></a>
        <a href="<?= isset($_SESSION['user_id']) ? 'index.php?action=logout' : 'index.php?action=login' ?>"><i class="fa-regular fa-user"></i></a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // GESTION DES FAVORIS (AJAX)
        document.querySelectorAll('.toggle-favorite').forEach(btn => {
            btn.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                const carId = this.dataset.id;
                const icon = this.querySelector('i');
                
                fetch('index.php?action=toggle_favorite&id=' + carId)
                    .then(response => response.json())
                    .then(data => {
                        if (data.status === 'added') {
                            icon.className = 'fa-solid fa-heart text-danger';
                            this.classList.add('active');
                        } else if (data.status === 'removed') {
                            icon.className = 'fa-regular fa-heart';
                            this.classList.remove('active');
                        } else if (data.status === 'error') {
                            window.location.href = 'index.php?action=login';
                        }
                    })
                    .catch(err => console.error('Erreur favoris:', err));
            });
        });
    });
    </script>
</body>
</html>