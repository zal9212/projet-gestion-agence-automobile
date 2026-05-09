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
                                <p class="text-muted small mb-3"><i class="fa-regular fa-calendar me-2"></i> <?= date('d/m/Y', strtotime($r['date_debut'])) ?> &rarr; <?= date('d/m/Y', strtotime($r['date_fin'])) ?></p>
                                <div class="d-flex justify-content-between align-items-center border-top pt-3 mt-3">
                                    <div>
                                        <small class="text-muted d-block" style="line-height:1;">Coût Total</small>
                                        <span class="fw-bold fs-5 text-success"><?= number_format($r['prix_total'], 0, ',', ' ') ?> €</span>
                                    </div>
                                    <button class="btn btn-outline-dark btn-sm rounded-pill px-3">Détails</button>
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