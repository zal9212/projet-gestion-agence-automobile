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