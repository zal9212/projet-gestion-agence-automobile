<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <a href="index.php?action=admin_crm" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="fa-solid fa-arrow-left me-2"></i> Retour au CRM</a>
        <h2 class="fw-bold mb-1">Dossier Client : <?= htmlspecialchars($client['nom'].' '.$client['prenom']) ?></h2>
    </div>
    <div>
        <a href="index.php?action=admin_crm_blacklist&id=<?= $client['id'] ?>&csrf_token=<?= generate_csrf_token() ?>" class="btn btn-<?= $client['is_blacklisted'] ? 'success' : 'danger' ?> rounded-pill px-4" onclick="return confirm('Modifier le statut de ce client ?');">
            <i class="fa-solid <?= $client['is_blacklisted'] ? 'fa-check' : 'fa-ban' ?> me-2"></i>
            <?= $client['is_blacklisted'] ? 'Réhabiliter le client' : 'Mettre sur Liste Noire' ?>
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Profil Client -->
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h5 class="fw-bold mb-4">Informations Personnelles</h5>
            <div class="text-center mb-4">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($client['nom'].' '.$client['prenom']) ?>&background=random&color=fff&size=100" class="rounded-circle shadow-sm mb-3">
                <h5 class="fw-bold mb-0"><?= htmlspecialchars($client['nom'].' '.$client['prenom']) ?></h5>
                <span class="badge <?= $client['is_blacklisted'] ? 'bg-danger' : 'bg-success' ?> mt-2"><?= $client['is_blacklisted'] ? 'Blacklisté' : 'Actif' ?></span>
            </div>
            <hr class="text-muted">
            <div class="mb-3">
                <small class="text-muted d-block fw-bold">Email</small>
                <span><i class="fa-solid fa-envelope text-muted me-2"></i> <?= htmlspecialchars($client['email']) ?></span>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block fw-bold">Téléphone</small>
                <span><i class="fa-solid fa-phone text-muted me-2"></i> <?= htmlspecialchars($client['telephone'] ?? 'Non renseigné') ?></span>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block fw-bold">Adresse</small>
                <span><i class="fa-solid fa-location-dot text-muted me-2"></i> <?= htmlspecialchars($client['adresse'] ?? 'Non renseignée') ?></span>
            </div>
            <div class="mb-3">
                <small class="text-muted d-block fw-bold">Date d'inscription</small>
                <span><i class="fa-solid fa-calendar text-muted me-2"></i> <?= date('d/m/Y', strtotime($client['date_inscription'])) ?></span>
            </div>
        </div>
    </div>

    <!-- Historique et Documents -->
    <div class="col-lg-8">
        <!-- Documents KYC -->
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h5 class="fw-bold mb-4">Documents d'Identité (KYC)</h5>
            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-4 border d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-bold mb-1"><i class="fa-solid fa-id-card text-primary me-2"></i> Permis de Conduire</h6>
                            <?php if($client['permis_url']): ?>
                                <span class="badge bg-success">Document fourni</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Manquant</span>
                            <?php endif; ?>
                        </div>
                        <?php if($client['permis_url']): ?>
                            <a href="<?= htmlspecialchars($client['permis_url']) ?>" target="_blank" class="btn btn-dark btn-sm rounded-circle"><i class="fa-solid fa-download"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="p-3 bg-light rounded-4 border d-flex align-items-center justify-content-between">
                        <div>
                            <h6 class="fw-bold mb-1"><i class="fa-solid fa-passport text-primary me-2"></i> Pièce d'Identité</h6>
                            <?php if($client['piece_identite_url']): ?>
                                <span class="badge bg-success">Document fourni</span>
                            <?php else: ?>
                                <span class="badge bg-warning text-dark">Manquant</span>
                            <?php endif; ?>
                        </div>
                        <?php if($client['piece_identite_url']): ?>
                            <a href="<?= htmlspecialchars($client['piece_identite_url']) ?>" target="_blank" class="btn btn-dark btn-sm rounded-circle"><i class="fa-solid fa-download"></i></a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Historique Réservations -->
        <div class="card border-0 shadow-sm rounded-4 p-4">
            <h5 class="fw-bold mb-4">Historique des Réservations (<?= count($reservations) ?>)</h5>
            <?php if(empty($reservations)): ?>
                <div class="text-center py-5">
                    <i class="fa-solid fa-folder-open fa-3x text-muted mb-3"></i>
                    <h6 class="fw-bold text-dark">Aucune réservation</h6>
                    <p class="text-muted">Ce client n'a pas encore loué de véhicule.</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="bg-light text-muted small text-uppercase">
                            <tr>
                                <th>Véhicule</th>
                                <th>Période</th>
                                <th>Statut</th>
                                <th class="text-end">Montant</th>
                            </tr>
                        </thead>
                        <tbody class="border-top-0">
                            <?php foreach($reservations as $r): ?>
                                <tr>
                                    <td>
                                        <div class="fw-bold text-dark"><?= htmlspecialchars($r['marque'].' '.$r['modele']) ?></div>
                                        <span class="badge bg-dark rounded-pill"><?= htmlspecialchars($r['immatriculation']) ?></span>
                                    </td>
                                    <td>
                                        <small class="fw-bold text-dark d-block"><?= date('d/m/Y', strtotime($r['date_debut'])) ?></small>
                                        <small class="text-muted">&rarr; <?= date('d/m/Y', strtotime($r['date_fin'])) ?></small>
                                    </td>
                                    <td>
                                        <?php 
                                        $status_color = match($r['status_reservation']) {
                                            'en_attente' => 'bg-warning text-dark',
                                            'validee' => 'bg-success',
                                            'en_cours' => 'bg-primary',
                                            'terminee' => 'bg-secondary',
                                            'annulee' => 'bg-danger',
                                            default => 'bg-light text-dark'
                                        };
                                        ?>
                                        <span class="badge <?= $status_color ?> rounded-pill px-3"><?= ucfirst($r['status_reservation']) ?></span>
                                    </td>
                                    <td class="text-end fw-bold text-success"><?= number_format($r['prix_total'], 0, ',', ' ') ?> FCFA</td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>
