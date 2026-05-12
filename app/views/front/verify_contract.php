<?php $title = "Vérification d'Authenticité"; ob_start(); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden" style="border: 2px solid #f4c053 !important;">
                <div class="bg-dark p-4 text-center text-white">
                    <img src="logo.png" alt="Teranga Auto Logo" style="height: 70px;" class="mb-3">
                    <h2 class="fw-bold mb-0">CERTIFICAT D'AUTHENTICITÉ</h2>
                    <p class="text-warning mb-0">Teranga Auto ERP - Système de Vérification</p>
                </div>
                
                <div class="card-body p-5 position-relative">
                    <!-- Filigrane -->
                    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-30deg); opacity: 0.05; font-size: 8rem; font-weight: 800; pointer-events: none; white-space: nowrap;">
                        TERANGA AUTO
                    </div>

                    <?php 
                    $isExpired = (strtotime($res['date_fin']) < strtotime(date('Y-m-d')));
                    ?>

                    <div class="text-center mb-5">
                        <?php if($isExpired): ?>
                            <div class="d-inline-block p-3 bg-danger bg-opacity-10 rounded-circle mb-3">
                                <i class="fa-solid fa-clock-rotate-left text-danger fa-3x"></i>
                            </div>
                            <h3 class="fw-bold text-danger">Contrat Expiré</h3>
                            <p class="text-muted">La durée de validité de ce contrat de location est dépassée.</p>
                        <?php else: ?>
                            <div class="d-inline-block p-3 bg-success bg-opacity-10 rounded-circle mb-3">
                                <i class="fa-solid fa-circle-check text-success fa-3x"></i>
                            </div>
                            <h3 class="fw-bold text-success">Contrat Certifié Conforme</h3>
                            <p class="text-muted">Ce document est une copie numérique authentique générée par Teranga Auto.</p>
                        <?php endif; ?>
                    </div>

                    <div class="row g-4 mb-5">
                        <div class="col-6">
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Numéro de Contrat</small>
                            <p class="fw-bold fs-5">#AR-<?= date('Y') ?>-<?= str_pad($res['id'], 5, "0", STR_PAD_LEFT) ?></p>
                        </div>
                        <div class="col-6 text-end">
                            <small class="text-muted text-uppercase fw-bold" style="font-size: 0.7rem;">Date d'Émission</small>
                            <p class="fw-bold fs-5"><?= date('d/m/Y', strtotime($res['date_creation'])) ?></p>
                        </div>
                        
                        <div class="col-12 border-top pt-3">
                            <h6 class="fw-bold mb-3 text-warning">DÉTAILS DE LA LOCATION</h6>
                            <table class="table table-borderless smal">
                                <tr>
                                    <td class="text-muted">Locataire :</td>
                                    <td class="fw-bold text-end"><?= htmlspecialchars($res['nom'] . ' ' . $res['prenom']) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Véhicule :</td>
                                    <td class="fw-bold text-end"><?= htmlspecialchars($res['marque'] . ' ' . $res['modele']) ?> (<?= htmlspecialchars($res['immatriculation']) ?>)</td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Période :</td>
                                    <td class="fw-bold text-end">Du <?= date('d/m/Y', strtotime($res['date_debut'])) ?> au <?= date('d/m/Y', strtotime($res['date_fin'])) ?></td>
                                </tr>
                                <tr>
                                    <td class="text-muted">Agent Validateur :</td>
                                    <td class="fw-bold text-end"><?= htmlspecialchars($res['agent_prenom'] . ' ' . $res['agent_nom']) ?></td>
                                </tr>
                            </table>
                        </div>
                    </div>

                    <div class="alert alert-warning border-0 rounded-4 p-3 mb-0">
                        <div class="d-flex align-items-center">
                            <i class="fa-solid fa-shield-halved fs-3 me-3 text-dark"></i>
                            <div class="small">
                                <strong>Garantie d'Authenticité :</strong> Ce contrat a été validé par signature électronique et est archivé de manière sécurisée dans nos serveurs. Toute modification manuelle du document papier invalide ce certificat.
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-light p-3 text-center small text-muted">
                    © <?= date('Y') ?> Teranga Auto SN - Document à usage officiel
                </div>
            </div>
            
            <div class="text-center mt-4">
                <button onclick="window.print()" class="btn btn-dark rounded-pill px-4"><i class="fa-solid fa-print me-2"></i> Imprimer le certificat</button>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/front.php'; ?>
