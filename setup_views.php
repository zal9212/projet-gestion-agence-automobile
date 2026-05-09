<?php
// Script de génération des vues MVC (Front et Admin) avec Bootstrap 5
$files = [
    'app/views/layouts/admin.php' => <<<'PHP'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - AutoRent</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f4f6f9; }
        .sidebar { min-height: 100vh; background: #343a40; color: white; padding-top: 20px; }
        .sidebar a { color: #c2c7d0; text-decoration: none; padding: 10px 20px; display: block; }
        .sidebar a:hover, .sidebar a.active { color: white; background: #495057; border-left: 4px solid #ffc107; }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar" style="width: 250px;">
            <div class="text-center mb-4">
                <h4 class="text-warning fw-bold"><i class="fa-solid fa-car-side"></i> AutoRent</h4>
                <small>Espace Administrateur</small>
            </div>
            <a href="index.php?action=admin_dashboard"><i class="fa-solid fa-gauge me-2"></i> Tableau de bord</a>
            <a href="index.php?action=admin_reservations"><i class="fa-solid fa-calendar-check me-2"></i> Réservations</a>
            <a href="index.php?action=admin_cars"><i class="fa-solid fa-car me-2"></i> Flotte Automobile</a>
            <hr class="text-secondary">
            <a href="index.php" class="text-info"><i class="fa-solid fa-globe me-2"></i> Voir le site</a>
            <a href="index.php?action=logout" class="text-danger"><i class="fa-solid fa-power-off me-2"></i> Déconnexion</a>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-4">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="alert alert-success alert-dismissible fade show">
                    <?= $_SESSION['success']; unset($_SESSION['success']); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger alert-dismissible fade show">
                    <?= $_SESSION['error']; unset($_SESSION['error']); ?>
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
<h2 class="mb-4 fw-bold">Tableau de Bord</h2>

<!-- Statistiques -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card bg-primary text-white shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <h1 class="display-4 fw-bold"><?= $stats['ca_mensuel'] ?> €</h1>
                <p class="mb-0">Chiffre d'Affaires (Mois)</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <h1 class="display-4 fw-bold"><?= $stats['total_cars'] ?></h1>
                <p class="mb-0">Véhicules dans la Flotte</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-dark shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <h1 class="display-4 fw-bold"><?= $stats['attente'] ?></h1>
                <p class="mb-0">Réservations en attente</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white shadow-sm border-0 h-100">
            <div class="card-body text-center">
                <h1 class="display-4 fw-bold"><?= $stats['total_reservations'] ?></h1>
                <p class="mb-0">Total Réservations</p>
            </div>
        </div>
    </div>
</div>

<!-- Dernières réservations -->
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3">
        <h5 class="mb-0 fw-bold">Dernières Réservations</h5>
    </div>
    <div class="card-body p-0">
        <table class="table table-hover mb-0">
            <thead class="table-light">
                <tr>
                    <th>Client</th>
                    <th>Véhicule</th>
                    <th>Période</th>
                    <th>Montant</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($recent_reservations as $r): ?>
                <tr>
                    <td><?= htmlspecialchars($r['nom'].' '.$r['prenom']) ?></td>
                    <td><?= htmlspecialchars($r['marque'].' '.$r['modele']) ?></td>
                    <td>Du <?= date('d/m/Y', strtotime($r['date_debut'])) ?> au <?= date('d/m/Y', strtotime($r['date_fin'])) ?></td>
                    <td class="fw-bold text-success"><?= $r['prix_total'] ?> €</td>
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
                        <span class="badge <?= $badge ?>"><?= ucfirst($r['status_reservation']) ?></span>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>
PHP,

    'app/views/admin/reservations.php' => <<<'PHP'
<?php ob_start(); ?>
<h2 class="mb-4 fw-bold">Gestion des Réservations</h2>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover table-striped mb-0 align-middle">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Client</th>
                    <th>Voiture</th>
                    <th>Dates</th>
                    <th>Montant</th>
                    <th>Chauffeur</th>
                    <th>Statut</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($reservations as $r): ?>
                <tr>
                    <td>#<?= $r['id'] ?></td>
                    <td>
                        <?= htmlspecialchars($r['nom'].' '.$r['prenom']) ?><br>
                        <small class="text-muted"><i class="fa-solid fa-phone"></i> <?= htmlspecialchars($r['telephone']) ?></small>
                    </td>
                    <td><?= htmlspecialchars($r['marque'].' ('.$r['immatriculation'].')') ?></td>
                    <td><small><?= date('d/m', strtotime($r['date_debut'])) ?> &rarr; <?= date('d/m', strtotime($r['date_fin'])) ?></small></td>
                    <td class="fw-bold"><?= $r['prix_total'] ?> €</td>
                    <td><?= $r['avec_chauffeur'] ? '<span class="badge bg-info">Oui</span>' : 'Non' ?></td>
                    <td>
                        <form action="index.php?action=admin_res_update" method="POST" class="d-flex">
                            <input type="hidden" name="id" value="<?= $r['id'] ?>">
                            <select name="status" class="form-select form-select-sm me-2" style="width: 130px;">
                                <option value="en_attente" <?= $r['status_reservation']=='en_attente'?'selected':'' ?>>En attente</option>
                                <option value="validee" <?= $r['status_reservation']=='validee'?'selected':'' ?>>Validée</option>
                                <option value="en_cours" <?= $r['status_reservation']=='en_cours'?'selected':'' ?>>En cours</option>
                                <option value="terminee" <?= $r['status_reservation']=='terminee'?'selected':'' ?>>Terminée</option>
                                <option value="annulee" <?= $r['status_reservation']=='annulee'?'selected':'' ?>>Annulée</option>
                            </select>
                            <button type="submit" class="btn btn-sm btn-primary"><i class="fa-solid fa-check"></i></button>
                        </form>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-secondary" title="Contrat PDF"><i class="fa-solid fa-file-pdf"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>
PHP,

    'app/views/admin/cars.php' => <<<'PHP'
<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2 class="fw-bold">Flotte Automobile</h2>
    <button class="btn btn-success"><i class="fa-solid fa-plus"></i> Ajouter un Véhicule</button>
</div>

<div class="card shadow-sm border-0">
    <div class="card-body p-0">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th>Image</th>
                    <th>Immatriculation</th>
                    <th>Véhicule</th>
                    <th>Catégorie</th>
                    <th>Prix/J</th>
                    <th>Caution</th>
                    <th>Statut</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach($cars as $car): ?>
                <tr>
                    <td><img src="<?= htmlspecialchars($car['image_principale']) ?>" alt="" style="width: 60px; height: 40px; object-fit: cover; border-radius: 5px;"></td>
                    <td class="fw-bold"><?= htmlspecialchars($car['immatriculation']) ?></td>
                    <td><?= htmlspecialchars($car['marque'].' '.$car['modele']) ?><br><small class="text-muted"><?= htmlspecialchars($car['type_carburant'].' | '.$car['boite_vitesse']) ?></small></td>
                    <td><?= htmlspecialchars($car['cat_nom']) ?></td>
                    <td class="text-success fw-bold"><?= $car['prix_journalier'] ?> €</td>
                    <td><?= $car['caution'] ?> €</td>
                    <td>
                        <?php if($car['status'] == 'disponible'): ?>
                            <span class="badge bg-success"><i class="fa-solid fa-check"></i> Dispo</span>
                        <?php elseif($car['status'] == 'maintenance'): ?>
                            <span class="badge bg-warning text-dark"><i class="fa-solid fa-wrench"></i> Atelier</span>
                        <?php else: ?>
                            <span class="badge bg-danger"><i class="fa-solid fa-key"></i> Louée</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary"><i class="fa-solid fa-pen"></i></button>
                        <button class="btn btn-sm btn-outline-danger"><i class="fa-solid fa-trash"></i></button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>
PHP,

    'app/views/front/search_results.php' => <<<'PHP'
<?php $title = "Résultats de recherche"; ob_start(); ?>
<div class="container mt-5">
    <h2 class="mb-4">Véhicules disponibles du <?= htmlspecialchars($_GET['date_debut']) ?> au <?= htmlspecialchars($_GET['date_fin']) ?></h2>
    
    <?php if(empty($cars)): ?>
        <div class="alert alert-warning">Aucun véhicule n'est disponible pour ces dates. Veuillez essayer d'autres dates.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach ($cars as $car): ?>
            <div class="col-md-4">
                <div class="card h-100 shadow-sm border-0 rounded-4 overflow-hidden">
                    <img src="<?= htmlspecialchars($car['image_principale']) ?>" class="card-img-top" style="height: 220px; object-fit: cover;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-primary"><?= htmlspecialchars($car['categorie_nom'] ?? 'Auto') ?></span>
                            <h5 class="card-title mb-0 fw-bold"><?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></h5>
                        </div>
                        <p class="text-muted small mb-3"><i class="fa-solid fa-gas-pump"></i> <?= htmlspecialchars($car['type_carburant']) ?> &nbsp;&bull;&nbsp; <i class="fa-solid fa-gears"></i> <?= htmlspecialchars($car['boite_vitesse']) ?> &nbsp;&bull;&nbsp; <i class="fa-solid fa-users"></i> <?= $car['nb_places'] ?> places</p>
                        <hr>
                        <div class="d-flex justify-content-between align-items-center">
                            <h4 class="mb-0 text-success fw-bold"><?= number_format($car['prix_journalier'], 0, ',', ' ') ?> €<small class="text-muted fs-6">/jr</small></h4>
                            <a href="index.php?action=reserve&id=<?= $car['id'] ?>&date_debut=<?= $_GET['date_debut'] ?>&date_fin=<?= $_GET['date_fin'] ?>" class="btn btn-outline-primary">Réserver</a>
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

    'app/views/front/reserve.php' => <<<'PHP'
<?php $title = "Réserver ".$car['marque']; ob_start(); ?>
<div class="container mt-5 mb-5">
    <div class="row">
        <!-- Infos Voiture -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <img src="<?= htmlspecialchars($car['image_principale']) ?>" class="card-img-top" style="border-radius: 15px 15px 0 0;">
                <div class="card-body bg-dark text-white rounded-bottom-4">
                    <h4 class="fw-bold"><?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></h4>
                    <p class="mb-1"><i class="fa-solid fa-check text-success"></i> Prix journalier: <strong><?= $car['prix_journalier'] ?> €</strong></p>
                    <p class="mb-1"><i class="fa-solid fa-shield text-info"></i> Caution bloquée: <strong><?= $car['caution'] ?> €</strong></p>
                    <p class="mb-0 text-muted"><small>Catégorie: <?= htmlspecialchars($car['categorie_nom']) ?></small></p>
                </div>
            </div>
        </div>

        <!-- Formulaire -->
        <div class="col-md-8">
            <div class="card shadow-sm border-0 p-4 rounded-4">
                <h3 class="mb-4 fw-bold">Détails de la réservation</h3>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form action="index.php?action=confirm_reserve" method="POST">
                    <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                    
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date de départ</label>
                            <input type="date" name="date_debut" class="form-control" value="<?= $_GET['date_debut'] ?? '' ?>" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Date de retour</label>
                            <input type="date" name="date_fin" class="form-control" value="<?= $_GET['date_fin'] ?? '' ?>" required>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3">Lieux (Prise et Retour)</h5>
                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label">Prise en charge</label>
                            <select name="lieu_prise_id" class="form-select" required>
                                <option value="">Choisir un lieu...</option>
                                <?php foreach($locations as $loc): ?>
                                    <option value="<?= $loc['id'] ?>"><?= htmlspecialchars($loc['nom']) ?> (+<?= $loc['frais_supplementaire'] ?>€)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Restitution</label>
                            <select name="lieu_retour_id" class="form-select" required>
                                <option value="">Choisir un lieu...</option>
                                <?php foreach($locations as $loc): ?>
                                    <option value="<?= $loc['id'] ?>"><?= htmlspecialchars($loc['nom']) ?> (+<?= $loc['frais_supplementaire'] ?>€)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <h5 class="fw-bold mb-3">Options</h5>
                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="checkbox" role="switch" id="chauffeur" name="avec_chauffeur" value="1">
                        <label class="form-check-label" for="chauffeur">
                            <strong>Option Chauffeur</strong> <span class="text-muted">(+150€ / jour)</span>
                        </label>
                    </div>

                    <hr>
                    <div class="text-end mt-4">
                        <button type="submit" class="btn btn-warning btn-lg fw-bold px-5">Confirmer et Payer plus tard <i class="fa-solid fa-arrow-right"></i></button>
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
<div class="container mt-5">
    <h2 class="mb-4 fw-bold">Mes Réservations</h2>
    
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
    <?php endif; ?>

    <?php if(empty($reservations)): ?>
        <div class="alert alert-info">Vous n'avez pas encore de réservations.</div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach($reservations as $r): ?>
            <div class="col-md-6">
                <div class="card shadow-sm border-0 h-100 rounded-4">
                    <div class="row g-0">
                        <div class="col-md-4">
                            <img src="<?= htmlspecialchars($r['image_principale']) ?>" class="img-fluid rounded-start-4 h-100" style="object-fit: cover;">
                        </div>
                        <div class="col-md-8">
                            <div class="card-body">
                                <h5 class="card-title fw-bold"><?= htmlspecialchars($r['marque'].' '.$r['modele']) ?></h5>
                                <p class="card-text mb-1"><small class="text-muted">Du <?= date('d/m/Y', strtotime($r['date_debut'])) ?> au <?= date('d/m/Y', strtotime($r['date_fin'])) ?></small></p>
                                <p class="card-text mb-2">Total : <strong class="text-success"><?= $r['prix_total'] ?> €</strong></p>
                                
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
                                <span class="badge <?= $badge ?> px-3 py-2">Statut: <?= ucfirst($r['status_reservation']) ?></span>
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

    'app/views/front/login.php' => <<<'PHP'
<?php $title = "Connexion"; ob_start(); ?>
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow-lg border-0 rounded-4 p-4">
                <div class="text-center mb-4">
                    <h2 class="fw-bold"><i class="fa-solid fa-user-circle text-primary"></i> Connexion</h2>
                    <p class="text-muted">Accédez à votre espace AutoRent</p>
                </div>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
                <?php endif; ?>

                <form action="index.php?action=do_login" method="POST">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-envelope"></i></span>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Mot de passe</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fa-solid fa-lock"></i></span>
                            <input type="password" name="password" class="form-control" required>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary w-100 fw-bold mb-3">Se connecter</button>
                    <div class="text-center">
                        <a href="index.php?action=register" class="text-decoration-none">Pas encore de compte ? S'inscrire</a>
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
<div class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4 p-4">
                <div class="text-center mb-4">
                    <h2 class="fw-bold"><i class="fa-solid fa-user-plus text-success"></i> Créer un compte</h2>
                    <p class="text-muted">Rejoignez AutoRent pour louer facilement</p>
                </div>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form action="index.php?action=do_register" method="POST">
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Prénom</label>
                            <input type="text" name="prenom" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold">Nom</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold">Email</label>
                        <input type="email" name="email" class="form-control" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold">Mot de passe</label>
                        <input type="password" name="password" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100 fw-bold mb-3">S'inscrire</button>
                    <div class="text-center">
                        <a href="index.php?action=login" class="text-decoration-none">Déjà un compte ? Se connecter</a>
                    </div>
                </form>
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
echo "Vues générées.\n";
?>
