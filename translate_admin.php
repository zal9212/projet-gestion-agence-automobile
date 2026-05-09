<?php
$files = [
    'app/views/layouts/admin.php' => <<<'PHP'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AutoRent ERP Workspace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { font-family: 'Outfit', sans-serif; background-color: #f4f7f6; color: #1a1a1a; }
        .sidebar { min-height: 100vh; background: #ffffff; border-right: 1px solid #eee; padding-top: 30px; position: sticky; top: 0; overflow-y: auto;}
        .sidebar a { color: #666; text-decoration: none; padding: 12px 30px; display: flex; align-items: center; font-weight: 500; transition: 0.2s; border-right: 4px solid transparent; font-size: 0.95rem; }
        .sidebar a:hover, .sidebar a.active { color: #1a1a1a; background: #fbfbfb; border-right: 4px solid #f4c053; font-weight: 600; }
        .sidebar i { width: 25px; font-size: 1.1rem; }
        .card { border: none; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); }
    </style>
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="sidebar" style="width: 280px; flex-shrink: 0;">
            <div class="text-center mb-4 px-4">
                <h3 class="fw-bold text-dark"><i class="fa-solid fa-car-side" style="color: #f4c053;"></i> AutoRent</h3>
                <span class="badge bg-dark text-warning rounded-pill mt-1">ERP Pro Edition</span>
            </div>
            
            <p class="text-muted small fw-bold px-4 mb-2 ms-2 mt-4" style="letter-spacing: 1px;">CŒUR DE MÉTIER</p>
            <a href="index.php?action=admin_dashboard" class="<?= (isset($_GET['action']) && $_GET['action']=='admin_dashboard')?'active':'' ?>"><i class="fa-solid fa-gauge"></i> Tableau de bord</a>
            <a href="index.php?action=admin_gantt" class="<?= (isset($_GET['action']) && $_GET['action']=='admin_gantt')?'active':'' ?>"><i class="fa-solid fa-chart-gantt"></i> Planning Gantt</a>
            <a href="index.php?action=admin_reservations" class="<?= (isset($_GET['action']) && $_GET['action']=='admin_reservations')?'active':'' ?>"><i class="fa-solid fa-calendar-check"></i> Réservations</a>
            <a href="index.php?action=admin_cars" class="<?= (isset($_GET['action']) && strpos($_GET['action'], 'admin_car')!==false)?'active':'' ?>"><i class="fa-solid fa-car"></i> Flotte de Véhicules</a>
            
            <p class="text-muted small fw-bold px-4 mb-2 ms-2 mt-4" style="letter-spacing: 1px;">OPÉRATIONS</p>
            <a href="index.php?action=admin_checkin" class="<?= (isset($_GET['action']) && $_GET['action']=='admin_checkin')?'active':'' ?>"><i class="fa-solid fa-clipboard-check"></i> Check-in / Out</a>
            <a href="index.php?action=admin_maintenance" class="<?= (isset($_GET['action']) && $_GET['action']=='admin_maintenance')?'active':'' ?>"><i class="fa-solid fa-wrench"></i> Maintenance</a>
            
            <p class="text-muted small fw-bold px-4 mb-2 ms-2 mt-4" style="letter-spacing: 1px;">FINANCE & CLIENTS</p>
            <a href="index.php?action=admin_crm" class="<?= (isset($_GET['action']) && $_GET['action']=='admin_crm')?'active':'' ?>"><i class="fa-solid fa-users"></i> CRM & Clients</a>
            <a href="#"><i class="fa-solid fa-file-invoice-dollar"></i> Facturation</a>
            
            <p class="text-muted small fw-bold px-4 mt-5 mb-2 ms-2">AUTRES</p>
            <a href="index.php" class="text-primary"><i class="fa-solid fa-globe"></i> Voir le site</a>
            <a href="index.php?action=logout" class="text-danger mt-2"><i class="fa-solid fa-power-off"></i> Déconnexion</a>
        </div>

        <!-- Main Content -->
        <div class="flex-grow-1 p-5 overflow-auto" style="height: 100vh;">
            <div class="d-flex justify-content-between align-items-center mb-5">
                <div class="search-bar w-50 bg-white rounded-pill px-4 py-2 d-flex align-items-center shadow-sm border">
                    <i class="fa-solid fa-magnifying-glass text-muted me-3"></i>
                    <input type="text" class="border-0 bg-transparent w-100" placeholder="Rechercher un VIN, client, réservation..." style="outline:none;">
                </div>
                <div class="d-flex align-items-center gap-3">
                    <div class="bg-white rounded-circle d-flex align-items-center justify-content-center shadow-sm border" style="width: 45px; height: 45px;"><i class="fa-regular fa-bell"></i></div>
                    <img src="https://ui-avatars.com/api/?name=Admin&background=1a1a1a&color=fff" class="rounded-circle shadow-sm" style="width: 45px;">
                </div>
            </div>
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
        <h2 class="fw-bold mb-1">Vue d'ensemble</h2>
        <p class="text-muted">Ravi de vous revoir. Voici les chiffres de la journée.</p>
    </div>
    <button class="btn btn-dark rounded-pill px-4"><i class="fa-solid fa-download me-2"></i> Exporter</button>
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
                    <p class="text-muted small fw-bold mb-1">C.A du mois</p>
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
                    <p class="text-muted small fw-bold mb-1">Taille Flotte</p>
                    <h3 class="fw-bold mb-0"><?= $stats['total_cars'] ?> <span class="fs-6 text-muted fw-normal">vhc.</span></h3>
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
                    <p class="text-muted small fw-bold mb-1">En attente</p>
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
                    <p class="text-white-50 small fw-bold mb-1">Total Réservations</p>
                    <h3 class="fw-bold mb-0 text-warning"><?= $stats['total_reservations'] ?></h3>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Dernières réservations -->
<div class="card mb-5">
    <div class="card-header bg-white border-bottom-0 pt-4 pb-0 px-4 d-flex justify-content-between align-items-center">
        <h5 class="fw-bold mb-0">Réservations Récentes</h5>
        <a href="index.php?action=admin_reservations" class="btn btn-light btn-sm rounded-pill px-3">Tout voir</a>
    </div>
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="text-muted small text-uppercase" style="letter-spacing: 1px;">
                    <tr>
                        <th>Client</th>
                        <th>Véhicule</th>
                        <th>Période</th>
                        <th>Montant</th>
                        <th>Statut</th>
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
                        <td class="text-muted"><i class="fa-regular fa-calendar me-2"></i><?= date('d/m/Y', strtotime($r['date_debut'])) ?> &rarr; <?= date('d/m/Y', strtotime($r['date_fin'])) ?></td>
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
PHP,

    'app/views/admin/cars.php' => <<<'PHP'
<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h2 class="fw-bold mb-1">Gestion de la Flotte</h2>
        <p class="text-muted">Gérez vos véhicules, tarifs et disponibilités.</p>
    </div>
    <a href="index.php?action=admin_car_form" class="btn btn-dark rounded-pill px-4"><i class="fa-solid fa-plus me-2"></i> Ajouter Véhicule</a>
</div>

<div class="row g-4">
    <?php foreach($cars as $car): ?>
    <div class="col-md-6 col-lg-4 col-xl-3">
        <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden position-relative">
            <div class="position-absolute top-0 start-0 m-3 z-index-10">
                <?php if($car['status'] == 'disponible'): ?>
                    <span class="badge bg-success bg-opacity-10 text-success rounded-pill px-3 py-2 border border-success border-opacity-25"><i class="fa-solid fa-circle me-1" style="font-size:8px;"></i> Dispo</span>
                <?php elseif($car['status'] == 'maintenance'): ?>
                    <span class="badge bg-warning bg-opacity-10 text-warning rounded-pill px-3 py-2 border border-warning border-opacity-25"><i class="fa-solid fa-wrench me-1"></i> Maintenance</span>
                <?php else: ?>
                    <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3 py-2 border border-danger border-opacity-25"><i class="fa-solid fa-lock me-1"></i> Louée</span>
                <?php endif; ?>
            </div>
            
            <div class="bg-light p-4 d-flex justify-content-center align-items-center" style="height: 180px;">
                <img src="<?= htmlspecialchars($car['image_principale']) ?>" alt="" style="max-height: 120px; max-width: 100%; object-fit: contain;">
            </div>
            
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-start mb-1">
                    <span class="text-muted small fw-bold"><?= htmlspecialchars($car['immatriculation']) ?></span>
                    <span class="badge bg-light text-dark border"><?= htmlspecialchars($car['cat_nom']) ?></span>
                </div>
                <h5 class="fw-bold mb-3 text-dark"><?= htmlspecialchars($car['marque'].' '.$car['modele']) ?></h5>
                
                <div class="d-flex gap-3 mb-4 text-muted small">
                    <div><i class="fa-solid fa-gas-pump me-1"></i> <?= htmlspecialchars($car['type_carburant']) ?></div>
                    <div><i class="fa-solid fa-gears me-1"></i> <?= htmlspecialchars($car['boite_vitesse']) ?></div>
                </div>
                
                <div class="d-flex justify-content-between align-items-end border-top pt-3">
                    <div>
                        <span class="text-muted small d-block" style="line-height:1;">Tarif journalier</span>
                        <h5 class="text-success fw-bold mb-0 mt-1"><?= $car['prix_journalier'] ?> €</h5>
                    </div>
                    <div class="d-flex gap-2">
                        <a href="index.php?action=admin_car_form&id=<?= $car['id'] ?>" class="btn btn-light rounded-circle shadow-sm text-primary" style="width: 38px; height: 38px; display:flex; align-items:center; justify-content:center;"><i class="fa-solid fa-pen"></i></a>
                        <a href="index.php?action=admin_car_delete&id=<?= $car['id'] ?>" class="btn btn-light rounded-circle shadow-sm text-danger" style="width: 38px; height: 38px; display:flex; align-items:center; justify-content:center;" onclick="return confirm('Supprimer ce véhicule ?');"><i class="fa-solid fa-trash"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>
PHP,

    'app/views/admin/reservations.php' => <<<'PHP'
<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h2 class="fw-bold mb-1">Toutes les Réservations</h2>
        <p class="text-muted">Gérez les demandes et changez les statuts.</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-dark rounded-pill px-4"><i class="fa-solid fa-filter me-2"></i> Filtres</button>
        <button class="btn btn-dark rounded-pill px-4"><i class="fa-solid fa-download me-2"></i> Exporter</button>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light text-muted small text-uppercase" style="letter-spacing: 1px;">
                    <tr>
                        <th class="ps-4 py-3">ID</th>
                        <th class="py-3">Client</th>
                        <th class="py-3">Véhicule</th>
                        <th class="py-3">Dates</th>
                        <th class="py-3">Montant</th>
                        <th class="py-3">Chauffeur</th>
                        <th class="py-3">Statut</th>
                        <th class="pe-4 py-3 text-end">Action</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php foreach($reservations as $r): ?>
                    <tr>
                        <td class="ps-4 fw-bold text-muted">#<?= $r['id'] ?></td>
                        <td>
                            <div class="fw-bold text-dark"><?= htmlspecialchars($r['nom'].' '.$r['prenom']) ?></div>
                            <div class="text-muted small"><i class="fa-solid fa-phone me-1"></i> <?= htmlspecialchars($r['telephone']) ?></div>
                        </td>
                        <td>
                            <div class="fw-medium text-dark"><?= htmlspecialchars($r['marque']) ?></div>
                            <div class="text-muted small"><?= htmlspecialchars($r['immatriculation']) ?></div>
                        </td>
                        <td>
                            <div class="small fw-medium text-dark"><?= date('d/m/Y', strtotime($r['date_debut'])) ?></div>
                            <div class="text-muted small">&rarr; <?= date('d/m/Y', strtotime($r['date_fin'])) ?></div>
                        </td>
                        <td class="fw-bold text-success"><?= number_format($r['prix_total'], 0, ',', ' ') ?> €</td>
                        <td>
                            <?php if($r['avec_chauffeur']): ?>
                                <span class="badge bg-dark rounded-pill"><i class="fa-solid fa-user-tie me-1"></i> Oui</span>
                            <?php else: ?>
                                <span class="text-muted small">Non</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <form action="index.php?action=admin_res_update" method="POST" class="d-flex align-items-center">
                                <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                <?php 
                                $status_color = match($r['status_reservation']) {
                                    'en_attente' => 'text-warning',
                                    'validee' => 'text-success',
                                    'en_cours' => 'text-primary',
                                    'terminee' => 'text-secondary',
                                    'annulee' => 'text-danger',
                                    default => 'text-dark'
                                };
                                ?>
                                <select name="status" class="form-select form-select-sm rounded-pill border-0 bg-light fw-bold <?= $status_color ?>" style="width: 140px; cursor:pointer;" onchange="this.form.submit()">
                                    <option value="en_attente" <?= $r['status_reservation']=='en_attente'?'selected':'' ?>>En attente</option>
                                    <option value="validee" <?= $r['status_reservation']=='validee'?'selected':'' ?>>Validée</option>
                                    <option value="en_cours" <?= $r['status_reservation']=='en_cours'?'selected':'' ?>>En cours</option>
                                    <option value="terminee" <?= $r['status_reservation']=='terminee'?'selected':'' ?>>Terminée</option>
                                    <option value="annulee" <?= $r['status_reservation']=='annulee'?'selected':'' ?>>Annulée</option>
                                </select>
                            </form>
                        </td>
                        <td class="pe-4 text-end">
                            <a href="index.php?action=admin_print_contract&id=<?= $r['id'] ?>" class="btn btn-light btn-sm rounded-circle shadow-sm text-primary" style="width: 35px; height: 35px;" title="Imprimer le contrat"><i class="fa-solid fa-print"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>
PHP,

    'app/views/admin/checkin.php' => <<<'PHP'
<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-5">
    <div><h2 class="fw-bold mb-1">Comptoir Check-in / Check-out</h2><p class="text-muted">Gérez les départs et retours des véhicules en direct.</p></div>
</div>
<div class="alert alert-warning border-0 rounded-4 shadow-sm p-4 d-flex align-items-center">
    <i class="fa-solid fa-tablet-screen-button fa-2x me-3"></i>
    <div>
        <h6 class="fw-bold mb-1">Mode Tablette Activé</h6>
        <p class="mb-0 small text-dark">Utilisez cette interface au comptoir pour valider les kilométrages et faire signer le client sur l'écran.</p>
    </div>
</div>
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <table class="table table-hover align-middle mb-0">
        <thead class="bg-light text-muted small text-uppercase">
            <tr><th class="ps-4 py-3">Réservation</th><th class="py-3">Véhicule</th><th class="py-3">Client</th><th class="py-3">Action Requise</th><th class="pe-4 py-3 text-end">Action</th></tr>
        </thead>
        <tbody class="border-top-0">
            <?php foreach($reservations as $r): ?>
            <tr>
                <td class="ps-4 fw-bold">#<?= $r['id'] ?> <br><small class="text-muted fw-normal"><?= date('d/m', strtotime($r['date_debut'])) ?> &rarr; <?= date('d/m', strtotime($r['date_fin'])) ?></small></td>
                <td><span class="badge bg-dark text-white rounded-pill px-3"><?= htmlspecialchars($r['immatriculation']) ?></span></td>
                <td><?= htmlspecialchars($r['nom'].' '.$r['prenom']) ?></td>
                <td>
                    <?php if($r['status_reservation'] == 'validee'): ?>
                        <span class="badge bg-primary rounded-pill"><i class="fa-solid fa-arrow-right-from-bracket me-1"></i> Prêt pour Départ (Check-out)</span>
                    <?php elseif($r['status_reservation'] == 'en_cours'): ?>
                        <span class="badge bg-success rounded-pill"><i class="fa-solid fa-arrow-right-to-bracket me-1"></i> En attente de Retour (Check-in)</span>
                    <?php else: ?>
                        <span class="text-muted small">Aucune action</span>
                    <?php endif; ?>
                </td>
                <td class="pe-4 text-end">
                    <?php if($r['status_reservation'] == 'validee' || $r['status_reservation'] == 'en_cours'): ?>
                        <a href="index.php?action=admin_checkout_process&id=<?= $r['id'] ?>" class="btn btn-warning btn-sm rounded-pill px-4 fw-bold shadow-sm">Traiter</a>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>
PHP,

    'app/views/admin/checkout_process.php' => <<<'PHP'
<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="index.php?action=admin_checkin" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="fa-solid fa-arrow-left me-2"></i> Retour au Comptoir</a>
        <h2 class="fw-bold mb-1">Remise du Véhicule (Check-out)</h2>
        <p class="text-muted">Remplissez l'état de départ et demandez au client de signer.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h5 class="fw-bold mb-3">Réservation #<?= $reservation['id'] ?></h5>
            <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                <div class="bg-light rounded p-3 me-3"><i class="fa-solid fa-car text-dark fa-2x"></i></div>
                <div>
                    <h6 class="fw-bold mb-0"><?= htmlspecialchars($reservation['marque'] . ' ' . $reservation['modele']) ?></h6>
                    <span class="badge bg-dark text-white rounded-pill"><?= htmlspecialchars($reservation['immatriculation']) ?></span>
                </div>
            </div>
            <p class="text-muted small mb-1">Client</p>
            <h6 class="fw-bold mb-3"><?= htmlspecialchars($reservation['nom'] . ' ' . $reservation['prenom']) ?></h6>
            <p class="text-muted small mb-1">Période</p>
            <h6 class="fw-bold mb-0"><?= date('d/m/Y', strtotime($reservation['date_debut'])) ?> &rarr; <?= date('d/m/Y', strtotime($reservation['date_fin'])) ?></h6>
        </div>
    </div>

    <div class="col-lg-8">
        <form action="index.php?action=admin_save_checkout" method="POST" id="checkoutForm">
            <input type="hidden" name="id" value="<?= $reservation['id'] ?>">
            <input type="hidden" name="signature_base64" id="signature_data">
            
            <div class="card border-0 shadow-sm rounded-4 p-5 mb-4">
                <h5 class="fw-bold mb-4">1. État au Départ</h5>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Kilométrage de Départ (km)</label>
                        <input type="number" name="kilometrage_depart" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $car['kilometrage'] ?? 0 ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Niveau de Carburant</label>
                        <select name="niveau_carburant_depart" class="form-select form-select-lg bg-light border-0 rounded-pill px-4" required>
                            <option value="1/4">1/4 (Basse)</option>
                            <option value="1/2">1/2 (Moitié)</option>
                            <option value="3/4">3/4</option>
                            <option value="Plein" selected>Plein</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-5 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">2. Signature du Client</h5>
                    <button type="button" class="btn btn-light btn-sm rounded-pill px-3" onclick="clearSignature()"><i class="fa-solid fa-eraser me-2"></i> Effacer</button>
                </div>
                <div class="border rounded-4 bg-light p-2 mb-3" style="touch-action: none;">
                    <canvas id="signaturePad" width="600" height="200" style="width: 100%; border-radius: 15px; background: white; cursor: crosshair;"></canvas>
                </div>
                <p class="text-muted small text-center mb-0">Je confirme avoir pris connaissance de l'état du véhicule détaillé ci-dessus.</p>
            </div>

            <button type="submit" class="btn btn-success btn-lg w-100 fw-bold rounded-pill shadow" onclick="return saveSignature()"><i class="fa-solid fa-check-circle me-2"></i> Confirmer Départ & Générer le Contrat</button>
        </form>
    </div>
</div>

<script>
    const canvas = document.getElementById('signaturePad');
    const ctx = canvas.getContext('2d');
    let isDrawing = false;
    
    canvas.width = canvas.offsetWidth;
    ctx.lineWidth = 3;
    ctx.lineCap = 'round';
    ctx.strokeStyle = '#1a1a1a';

    function getMousePos(canvas, evt) {
        var rect = canvas.getBoundingClientRect();
        return {
            x: (evt.clientX || evt.touches[0].clientX) - rect.left,
            y: (evt.clientY || evt.touches[0].clientY) - rect.top
        };
    }

    const startDrawing = (e) => { isDrawing = true; draw(e); };
    const stopDrawing = () => { isDrawing = false; ctx.beginPath(); };
    const draw = (e) => {
        if (!isDrawing) return;
        e.preventDefault();
        const pos = getMousePos(canvas, e);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
    };

    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    canvas.addEventListener('mouseup', stopDrawing);
    canvas.addEventListener('mouseout', stopDrawing);

    canvas.addEventListener('touchstart', startDrawing);
    canvas.addEventListener('touchmove', draw);
    canvas.addEventListener('touchend', stopDrawing);

    function clearSignature() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
    }

    function saveSignature() {
        const blank = document.createElement('canvas');
        blank.width = canvas.width; blank.height = canvas.height;
        if(canvas.toDataURL() == blank.toDataURL()) {
            alert("Veuillez demander au client de signer.");
            return false;
        }
        document.getElementById('signature_data').value = canvas.toDataURL();
        return true;
    }
</script>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>
PHP,

    'app/views/admin/contract_print.php' => <<<'PHP'
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contrat de Location #<?= $reservation['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background: #fff; color: #000; padding: 40px; }
        .contract-header { border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 30px; }
        .section-title { background: #f0f0f0; padding: 5px 10px; font-weight: bold; margin-top: 30px; margin-bottom: 15px; border-left: 4px solid #000; }
        table th { background-color: #f9f9f9; }
        .signature-box { border: 1px solid #ccc; height: 150px; text-align: center; margin-top: 20px; }
        .signature-img { max-height: 140px; max-width: 100%; object-fit: contain; }
        @media print {
            body { padding: 0; }
            button { display: none !important; }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="btn btn-dark float-end mb-4"><i class="fa-solid fa-print"></i> Imprimer le Contrat</button>
    
    <div class="contract-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-0">AutoRent Agency</h2>
            <p class="mb-0 text-muted">123 Avenue des Affaires, Centre Ville<br>contact@autorent.com | +33 1 23 45 67 89</p>
        </div>
        <div class="text-end">
            <h3 class="mb-1">CONTRAT DE LOCATION</h3>
            <p class="mb-0 fw-bold">Numéro : AR-<?= date('Y') ?>-<?= str_pad($reservation['id'], 5, "0", STR_PAD_LEFT) ?></p>
            <p class="mb-0">Date : <?= date('d/m/Y') ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="section-title">INFORMATIONS DU LOCATAIRE</div>
            <p><strong>Nom :</strong> <?= htmlspecialchars($reservation['nom'].' '.$reservation['prenom']) ?><br>
            <strong>Email :</strong> <?= htmlspecialchars($reservation['email']) ?><br>
            <strong>Téléphone :</strong> <?= htmlspecialchars($reservation['telephone'] ?? 'N/A') ?></p>
        </div>
        <div class="col-6">
            <div class="section-title">INFORMATIONS DU VÉHICULE</div>
            <p><strong>Marque & Modèle :</strong> <?= htmlspecialchars($reservation['marque'].' '.$reservation['modele']) ?><br>
            <strong>Immatriculation :</strong> <?= htmlspecialchars($reservation['immatriculation']) ?><br>
            <strong>N° Châssis (VIN) :</strong> <?= htmlspecialchars($car['vin'] ?? 'N/A') ?></p>
        </div>
    </div>

    <div class="section-title">PÉRIODE DE LOCATION ET ÉTAT</div>
    <table class="table table-bordered">
        <tr>
            <th width="25%">Date de Départ</th><td><?= date('d/m/Y', strtotime($reservation['date_debut'])) ?></td>
            <th width="25%">Date de Retour</th><td><?= date('d/m/Y', strtotime($reservation['date_fin'])) ?></td>
        </tr>
        <tr>
            <th>Kilométrage Départ</th><td><?= $reservation['kilometrage_depart'] ?> km</td>
            <th>Niveau Carburant</th><td><?= htmlspecialchars($reservation['niveau_carburant_depart']) ?></td>
        </tr>
    </table>

    <div class="section-title">RÉSUMÉ FINANCIER</div>
    <table class="table table-bordered">
        <tr><th>Montant Total de la Location</th><td class="text-end fw-bold"><?= number_format($reservation['prix_total'], 2, ',', ' ') ?> €</td></tr>
        <tr><th>Dépôt de Garantie (Autorisé)</th><td class="text-end"><?= number_format($car['caution'], 2, ',', ' ') ?> €</td></tr>
    </table>

    <div class="section-title">CONDITIONS ET SIGNATURES</div>
    <p class="small text-muted">Le locataire reconnaît avoir reçu le véhicule dans l'état décrit ci-dessus et accepte les conditions générales de location d'AutoRent. Le véhicule doit être restitué à la date et l'heure convenues. Toute infraction au code de la route ou dommage survenu pendant la période de location relève de la seule responsabilité du locataire.</p>

    <div class="row mt-5">
        <div class="col-6">
            <p class="fw-bold text-center">Représentant AutoRent</p>
            <div class="signature-box d-flex align-items-center justify-content-center text-muted">Agence AutoRent</div>
        </div>
        <div class="col-6">
            <p class="fw-bold text-center">Signature du Locataire</p>
            <div class="signature-box border-0">
                <?php if($reservation['signature_base64']): ?>
                    <img src="<?= $reservation['signature_base64'] ?>" class="signature-img">
                <?php else: ?>
                    <div class="text-muted mt-5">Non signé</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
PHP
];

foreach ($files as $filepath => $content) {
    file_put_contents($filepath, $content);
}
echo "Fichiers de l'Admin traduits en français.";
?>
