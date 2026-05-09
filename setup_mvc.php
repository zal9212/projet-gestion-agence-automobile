<?php
// Script pour générer l'architecture MVC et les premiers fichiers Bootstrap

$dirs = [
    'app',
    'app/controllers',
    'app/models',
    'app/views',
    'app/views/layouts',
    'app/views/admin',
    'app/views/front',
    'public',
    'public/css',
    'public/js',
    'public/uploads'
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) mkdir($dir, 0777, true);
}

$files = [
    'app/config.php' => <<<'PHP'
<?php
define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'car_rental_db');
define('DB_USER', 'root');
define('DB_PASS', '');

function getPDO() {
    static $pdo = null;
    if ($pdo === null) {
        $pdo = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME.";charset=utf8", DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]);
    }
    return $pdo;
}
PHP,

    'app/views/layouts/front.php' => <<<'PHP'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoRent - <?= $title ?? 'Location de Voitures' ?></title>
    <!-- Bootstrap 5 CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- FontAwesome pour les icônes -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero { background: linear-gradient(rgba(0,0,0,0.5), rgba(0,0,0,0.5)), url('https://images.unsplash.com/photo-1449965408869-eaa3f722e40d?auto=format&fit=crop&q=80&w=1920') center/cover; padding: 100px 0; color: white; }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php"><i class="fa-solid fa-car-side text-warning"></i> AutoRent</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Accueil</a></li>
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=history">Mes Réservations</a></li>
                        <?php if($_SESSION['user_role'] === 'admin'): ?>
                            <li class="nav-item"><a class="nav-link text-warning" href="index.php?action=admin_dashboard">Dashboard Admin</a></li>
                        <?php endif; ?>
                        <li class="nav-item"><a class="nav-link text-danger" href="index.php?action=logout"><i class="fa-solid fa-right-from-bracket"></i> Déconnexion</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="index.php?action=login">Connexion</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <main>
        <?= $content ?>
    </main>

    <footer class="bg-dark text-white text-center py-4 mt-5">
        <div class="container">
            <p>&copy; <?= date('Y') ?> AutoRent. Tous droits réservés.</p>
        </div>
    </footer>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
PHP,

    'app/views/front/home.php' => <<<'PHP'
<?php $title = "Accueil"; ob_start(); ?>

<div class="hero text-center">
    <div class="container">
        <h1 class="display-4 fw-bold">Trouvez la voiture idéale pour votre voyage</h1>
        <p class="lead mb-5">Louez facilement avec ou sans chauffeur. Meilleurs prix garantis.</p>
        
        <!-- Moteur de recherche horizontal -->
        <div class="card p-4 shadow-lg text-dark text-start" style="max-width: 900px; margin: 0 auto; border-radius: 15px;">
            <form action="index.php" method="GET" class="row g-3">
                <input type="hidden" name="action" value="search">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Date de départ</label>
                    <input type="date" class="form-control" name="date_debut" required>
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold">Date de retour</label>
                    <input type="date" class="form-control" name="date_fin" required>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-warning w-100 fw-bold"><i class="fa-solid fa-search"></i> Rechercher</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="container mt-5">
    <h2 class="text-center mb-4 fw-bold">Notre Flotte Recommandée</h2>
    <div class="row g-4">
        <?php foreach ($cars as $car): ?>
        <div class="col-md-4">
            <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                <img src="<?= htmlspecialchars($car['image_principale']) ?>" class="card-img-top" alt="Voiture" style="height: 220px; object-fit: cover;">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="badge bg-primary"><?= htmlspecialchars($car['categorie_nom'] ?? 'Auto') ?></span>
                        <h5 class="card-title mb-0 fw-bold"><?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></h5>
                    </div>
                    <p class="text-muted small mb-3"><i class="fa-solid fa-gas-pump"></i> <?= htmlspecialchars($car['type_carburant']) ?> &nbsp;&bull;&nbsp; <i class="fa-solid fa-gears"></i> <?= htmlspecialchars($car['boite_vitesse']) ?> &nbsp;&bull;&nbsp; <i class="fa-solid fa-users"></i> <?= $car['nb_places'] ?> places</p>
                    <hr>
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0 text-success fw-bold"><?= number_format($car['prix_journalier'], 0, ',', ' ') ?> €<small class="text-muted fs-6">/jr</small></h4>
                        <a href="index.php?action=reserve&id=<?= $car['id'] ?>" class="btn btn-outline-primary">Réserver</a>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<?php $content = ob_get_clean(); require 'app/views/layouts/front.php'; ?>
PHP,

    'index.php' => <<<'PHP'
<?php
session_start();
require_once 'app/config.php';

$action = $_GET['action'] ?? 'home';

switch ($action) {
    case 'home':
        $pdo = getPDO();
        $stmt = $pdo->query("SELECT c.*, cat.nom as categorie_nom FROM cars c LEFT JOIN categories cat ON c.category_id = cat.id WHERE c.status = 'disponible' LIMIT 6");
        $cars = $stmt->fetchAll();
        require 'app/views/front/home.php';
        break;
        
    case 'login':
        // A implémenter (Vue connexion)
        echo "Page de connexion (à migrer sur Bootstrap)";
        break;
        
    case 'admin_dashboard':
        // A implémenter (Dashboard)
        echo "Dashboard Admin (à construire avec Bootstrap)";
        break;

    default:
        echo "404 Not Found";
        break;
}
PHP
];

foreach ($files as $filepath => $content) {
    file_put_contents($filepath, $content);
}
echo "Architecture MVC et Bootstrap générés avec succès.";
?>
