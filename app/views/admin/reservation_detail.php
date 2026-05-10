<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <a href="index.php?action=admin_reservations" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="fa-solid fa-arrow-left me-2"></i> Retour à la liste</a>
        <h2 class="fw-bold mb-1">Détails de la Réservation #<?= $reservation['id'] ?></h2>
        <p class="text-muted">Historique complet et documents associés.</p>
    </div>
    <div class="d-flex gap-2">
        <?php if(in_array($reservation['status_reservation'], ['en_cours', 'terminee'])): ?>
            <a href="index.php?action=admin_print_contract&id=<?= $reservation['id'] ?>" class="btn btn-outline-dark rounded-pill px-4 shadow-sm">
                <i class="fa-solid fa-file-contract me-2"></i> Contrat
            </a>
            <a href="index.php?action=admin_print_invoice&id=<?= $reservation['id'] ?>" class="btn btn-dark rounded-pill px-4 shadow-sm">
                <i class="fa-solid fa-file-invoice-dollar me-2"></i> Imprimer Facture
            </a>
        <?php endif; ?>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-8">
        <!-- Infos Client & Véhicule -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h5 class="fw-bold mb-4">Informations Générales</h5>
            <div class="row g-4">
                <div class="col-md-6">
                    <p class="text-muted small mb-1">Locataire</p>
                    <h6 class="fw-bold"><?= htmlspecialchars($reservation['prenom'].' '.$reservation['nom']) ?></h6>
                    <p class="mb-0 small text-muted"><i class="fa-solid fa-envelope me-2"></i> <?= htmlspecialchars($reservation['email']) ?></p>
                    <p class="mb-0 small text-muted"><i class="fa-solid fa-phone me-2"></i> <?= htmlspecialchars($reservation['telephone']) ?></p>
                </div>
                <div class="col-md-6">
                    <p class="text-muted small mb-1">Véhicule</p>
                    <h6 class="fw-bold"><?= htmlspecialchars($reservation['marque'].' '.$reservation['modele']) ?></h6>
                    <p class="mb-0 small text-muted"><i class="fa-solid fa-id-card me-2"></i> <?= htmlspecialchars($reservation['immatriculation']) ?></p>
                </div>
            </div>
        </div>

        <!-- État au départ -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h5 class="fw-bold mb-4">État au Départ (Départ)</h5>
            <?php if($reservation['kilometrage_depart'] !== null): ?>
                <div class="row text-center g-3">
                    <div class="col-4">
                        <div class="bg-light p-3 rounded-4">
                            <p class="text-muted small mb-1">Kilométrage</p>
                            <h6 class="fw-bold mb-0"><?= number_format($reservation['kilometrage_depart'], 0, ',', ' ') ?> km</h6>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light p-3 rounded-4">
                            <p class="text-muted small mb-1">Carburant</p>
                            <h6 class="fw-bold mb-0"><?= htmlspecialchars($reservation['niveau_carburant_depart']) ?></h6>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light p-3 rounded-4">
                            <p class="text-muted small mb-1">Agent</p>
                            <h6 class="fw-bold mb-0"><?= htmlspecialchars($reservation['agent_prenom'] ?? 'N/A') ?></h6>
                        </div>
                    </div>
                </div>
                
                <?php if(!empty($reservation['photos_depart'])): 
                    $photos_dep = json_decode($reservation['photos_depart'], true);
                    if(is_array($photos_dep) && count($photos_dep) > 0):
                ?>
                <div class="mt-4 pt-3 border-top">
                    <p class="text-muted small mb-3 fw-bold"><i class="fa-solid fa-camera text-primary me-2"></i>Photos de l'inspection (Départ)</p>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach($photos_dep as $photo): ?>
                            <a href="<?= htmlspecialchars($photo) ?>" target="_blank" class="d-block">
                                <img src="<?= htmlspecialchars($photo) ?>" class="rounded-3 border shadow-sm" style="width: 80px; height: 80px; object-fit: cover; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; endif; ?>

                <div class="mt-4 pt-3 border-top">
                    <p class="text-muted small mb-2 text-center">Signature client enregistrée</p>
                    <div class="text-center">
                        <img src="<?= $reservation['signature_base64'] ?>" style="max-height: 100px; opacity: 0.8;" alt="Signature">
                    </div>
                </div>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fa-solid fa-clock-rotate-left fa-3x text-light mb-3"></i>
                    <p class="text-muted">En attente de la remise des clés.</p>
                    <?php if($reservation['status_reservation'] == 'validee'): ?>
                        <a href="index.php?action=admin_checkout_process&id=<?= $reservation['id'] ?>" class="btn btn-outline-success rounded-pill px-4 mt-2">Démarrer le Départ</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- État au retour -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h5 class="fw-bold mb-4">État au Retour (Restitution)</h5>
            <?php if($reservation['kilometrage_retour'] !== null): ?>
                <div class="row text-center g-3">
                    <div class="col-6">
                        <div class="bg-light p-3 rounded-4">
                            <p class="text-muted small mb-1">Kilométrage Final</p>
                            <h6 class="fw-bold mb-0"><?= number_format($reservation['kilometrage_retour'], 0, ',', ' ') ?> km</h6>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="bg-light p-3 rounded-4">
                            <p class="text-muted small mb-1">Carburant Final</p>
                            <h6 class="fw-bold mb-0"><?= htmlspecialchars($reservation['niveau_carburant_retour']) ?></h6>
                        </div>
                    </div>
                </div>
                
                <?php if(!empty($reservation['photos_retour'])): 
                    $photos_ret = json_decode($reservation['photos_retour'], true);
                    if(is_array($photos_ret) && count($photos_ret) > 0):
                ?>
                <div class="mt-4 pt-3 border-top">
                    <p class="text-muted small mb-3 fw-bold"><i class="fa-solid fa-camera-retro text-primary me-2"></i>Photos de l'inspection (Retour)</p>
                    <div class="d-flex flex-wrap gap-2">
                        <?php foreach($photos_ret as $photo): ?>
                            <a href="<?= htmlspecialchars($photo) ?>" target="_blank" class="d-block">
                                <img src="<?= htmlspecialchars($photo) ?>" class="rounded-3 border shadow-sm" style="width: 80px; height: 80px; object-fit: cover; transition: transform 0.2s;" onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'">
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; endif; ?>

                <?php if($reservation['signature_retour_base64']): ?>
                <div class="mt-4 pt-3 border-top text-center">
                    <p class="text-muted small mb-2">Signature de Restitution</p>
                    <img src="<?= $reservation['signature_retour_base64'] ?>" style="max-height: 100px; opacity: 0.8;" alt="Signature Retour">
                </div>
                <?php endif; ?>
            <?php else: ?>
                <div class="text-center py-4">
                    <i class="fa-solid fa-flag-checkered fa-3x text-light mb-3"></i>
                    <p class="text-muted small">Le véhicule est encore chez le locataire.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Statut & Finances -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 bg-dark text-white">
            <h5 class="fw-bold mb-4 text-warning">Statut Actuel</h5>
            <div class="d-flex align-items-center mb-4">
                <div class="p-3 rounded-circle bg-warning text-dark me-3">
                    <i class="fa-solid fa-circle-check fa-lg"></i>
                </div>
                <div>
                    <h6 class="fw-bold mb-0 text-uppercase letter-spacing-1"><?= str_replace('_', ' ', $reservation['status_reservation']) ?></h6>
                    <small class="text-muted">Dernière mise à jour : <?= date('d/m/Y H:i', strtotime($reservation['date_creation'])) ?></small>
                </div>
            </div>
            
            <div class="border-top border-secondary pt-3 mt-2">
                <p class="text-muted small mb-1">Montant de la location</p>
                <h4 class="fw-bold text-success mb-0"><?= number_format($reservation['prix_total'], 0, ',', ' ') ?> FCFA</h4>
            </div>
        </div>

        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold mb-3">Période</h5>
            <div class="d-flex align-items-center mb-3">
                <div class="bg-light p-2 rounded me-3 text-center" style="width: 50px;">
                    <div class="small fw-bold"><?= date('d', strtotime($reservation['date_debut'])) ?></div>
                    <div class="x-small text-muted"><?= date('M', strtotime($reservation['date_debut'])) ?></div>
                </div>
                <div>
                    <p class="mb-0 small text-muted">Départ</p>
                    <p class="mb-0 fw-bold">Prise en charge</p>
                </div>
            </div>
            <div class="d-flex align-items-center">
                <div class="bg-light p-2 rounded me-3 text-center" style="width: 50px;">
                    <div class="small fw-bold"><?= date('d', strtotime($reservation['date_fin'])) ?></div>
                    <div class="x-small text-muted"><?= date('M', strtotime($reservation['date_fin'])) ?></div>
                </div>
                <div>
                    <p class="mb-0 small text-muted">Retour</p>
                    <p class="mb-0 fw-bold">Restitution prévue</p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>
