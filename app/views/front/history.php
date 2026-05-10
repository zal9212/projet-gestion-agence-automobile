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
            <a href="index.php?action=search" class="btn btn-dark rounded-pill px-5 mx-auto mt-2" style="width:fit-content;">Explorer les véhicules</a>
        </div>
    <?php else: ?>
        <div class="row g-4">
            <?php foreach($reservations as $r): ?>
            <div class="col-md-6 col-xl-4">
                <div class="card border-0 shadow-sm rounded-4 h-100 overflow-hidden">
                    <!-- Image plein cadre en haut -->
                    <div style="height: 150px; overflow: hidden; flex-shrink: 0;">
                        <img src="<?= htmlspecialchars($r['image_principale']) ?>" style="width:100%; height:100%; object-fit: cover;">
                    </div>
                    <div class="card-body p-4">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <h5 class="fw-bold mb-0"><?= htmlspecialchars($r['marque'].' '.$r['modele']) ?></h5>
                            <?php 
                            $badge = match($r['status_reservation']) {
                                'en_attente' => 'bg-warning text-dark',
                                'validee'    => 'bg-success',
                                'en_cours'   => 'bg-primary',
                                'terminee'   => 'bg-secondary',
                                'annulee'    => 'bg-danger',
                                default      => 'bg-light text-dark'
                            };
                            $label = match($r['status_reservation']) {
                                'en_attente' => 'En attente',
                                'validee'    => 'Validée',
                                'en_cours'   => 'En cours',
                                'terminee'   => 'Terminée',
                                'annulee'    => 'Annulée',
                                default      => $r['status_reservation']
                            };
                            ?>
                            <span class="badge <?= $badge ?> rounded-pill px-3 py-2"><?= $label ?></span>
                        </div>
                        <p class="text-muted small mb-3"><i class="fa-regular fa-calendar me-2"></i><?= date('d/m/Y', strtotime($r['date_debut'])) ?> &rarr; <?= date('d/m/Y', strtotime($r['date_fin'])) ?></p>
                        <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                            <div>
                                <small class="text-muted d-block" style="line-height:1;">Coût Total</small>
                                <span class="fw-bold fs-5 text-success"><?= number_format($r['prix_total'], 0, ',', ' ') ?> FCFA</span>
                            </div>
                            <!-- Bouton qui ouvre la modale de détails -->
                            <button class="btn btn-dark btn-sm rounded-pill px-4"
                                data-bs-toggle="modal"
                                data-bs-target="#modalRes<?= $r['id'] ?>">
                                <i class="fa-solid fa-circle-info me-1"></i> Détails
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Modale de détails pour cette réservation -->
            <div class="modal fade" id="modalRes<?= $r['id'] ?>" tabindex="-1">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content border-0 rounded-4 shadow-lg">
                        <div class="modal-header border-0 pb-0">
                            <h5 class="modal-title fw-bold">Détails de la Réservation #<?= $r['id'] ?></h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body px-4 pb-4">
                            <div style="height: 180px; overflow: hidden; border-radius: 15px; margin-bottom: 20px;">
                                <img src="<?= htmlspecialchars($r['image_principale']) ?>" style="width:100%; height:100%; object-fit: cover;">
                            </div>

                            <h5 class="fw-bold text-dark mb-1"><?= htmlspecialchars($r['marque'].' '.$r['modele']) ?></h5>
                            <span class="badge bg-dark rounded-pill mb-3"><?= htmlspecialchars($r['immatriculation'] ?? '') ?></span>

                            <div class="row g-3 mt-1">
                                <div class="col-6">
                                    <div class="bg-light rounded-3 p-3 text-center">
                                        <small class="text-muted d-block fw-bold">Date de départ</small>
                                        <span class="fw-bold text-dark"><?= date('d/m/Y', strtotime($r['date_debut'])) ?></span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded-3 p-3 text-center">
                                        <small class="text-muted d-block fw-bold">Date de retour</small>
                                        <span class="fw-bold text-dark"><?= date('d/m/Y', strtotime($r['date_fin'])) ?></span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded-3 p-3 text-center">
                                        <small class="text-muted d-block fw-bold">Statut</small>
                                        <span class="badge <?= $badge ?> px-3"><?= $label ?></span>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <div class="bg-light rounded-3 p-3 text-center">
                                        <small class="text-muted d-block fw-bold">Carburant</small>
                                        <span class="fw-bold text-dark"><?= htmlspecialchars($r['type_carburant'] ?? 'N/A') ?></span>
                                    </div>
                                </div>
                            </div>

                            <div class="mt-4 p-3 rounded-4 d-flex justify-content-between align-items-center" style="background: linear-gradient(135deg, #1a1a1a, #333); color: white;">
                                <div>
                                    <small class="text-white-50 d-block">Montant Total</small>
                                    <h4 class="fw-bold mb-0 text-warning"><?= number_format($r['prix_total'], 0, ',', ' ') ?> FCFA</h4>
                                </div>
                                <i class="fa-solid fa-money-bill-wave fa-2x text-white-50"></i>
                            </div>
                        </div>
                        <div class="modal-footer border-0 pt-0">
                            <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Fermer</button>
                            <?php if($r['status_reservation'] == 'en_attente'): ?>
                                <a href="index.php?action=cancel_reservation&id=<?= $r['id'] ?>" class="btn btn-danger rounded-pill px-4" onclick="return confirm('Annuler cette réservation ?');">
                                    <i class="fa-solid fa-xmark me-2"></i> Annuler
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/front.php'; ?>