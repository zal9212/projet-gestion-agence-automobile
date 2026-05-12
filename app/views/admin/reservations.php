<?php ob_start(); ?>
<div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-5 gap-3">
    <div>
        <h2 class="fw-bold mb-1">Toutes les Réservations</h2>
        <p class="text-muted mb-0">Gérez les demandes et changez les statuts.</p>
    </div>
    <div class="d-flex gap-2">
        <div class="dropdown flex-fill flex-md-initial">
            <button class="btn btn-outline-dark rounded-pill px-4 dropdown-toggle w-100" type="button" data-bs-toggle="dropdown">
                <i class="fa-solid fa-filter me-2"></i> <?= isset($current_status) ? ucfirst(str_replace('_', ' ', $current_status)) : 'Filtres' ?>
            </button>
            <ul class="dropdown-menu dropdown-menu-end border-0 shadow-lg rounded-4">
                <li><a class="dropdown-item" href="index.php?action=admin_reservations">Tous les statuts</a></li>
                <li><hr class="dropdown-divider"></li>
                <li><a class="dropdown-item" href="index.php?action=admin_reservations&status=en_attente">En attente</a></li>
                <li><a class="dropdown-item" href="index.php?action=admin_reservations&status=validee">Validée</a></li>
                <li><a class="dropdown-item" href="index.php?action=admin_reservations&status=en_cours">En cours</a></li>
                <li><a class="dropdown-item" href="index.php?action=admin_reservations&status=terminee">Terminée</a></li>
                <li><a class="dropdown-item" href="index.php?action=admin_reservations&status=annulee">Annulée</a></li>
            </ul>
        </div>
        <?php if($is_admin): ?>
            <a href="index.php?action=admin_reservations_export" class="btn btn-dark rounded-pill px-4 flex-fill flex-md-initial text-nowrap"><i class="fa-solid fa-download me-2"></i> Exporter</a>
        <?php endif; ?>
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
                        <?php if($is_admin): ?><th class="py-3">Montant</th><?php endif; ?>
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
                        <?php if($is_admin): ?>
                            <td class="fw-bold text-success"><?= number_format($r['prix_total'], 0, ',', ' ') ?> FCFA</td>
                        <?php endif; ?>
                        <td>
                            <?php if(isset($r['avec_chauffeur']) && $r['avec_chauffeur']): ?>
                                <span class="badge bg-dark rounded-pill"><i class="fa-solid fa-user-tie me-1"></i> Oui</span>
                            <?php else: ?>
                                <span class="text-muted small">Non</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if(in_array($r['status_reservation'], ['en_attente', 'validee'])): ?>
                                <form action="index.php?action=admin_res_update" method="POST" class="d-inline">
                                    <?= csrf_field() ?>
                                    <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                    <?php 
                                    $status_color = match($r['status_reservation']) {
                                        'en_attente' => 'text-warning',
                                        'validee'    => 'text-success',
                                        default      => 'text-dark'
                                    };
                                    ?>
                                    <select name="status" class="form-select form-select-sm rounded-pill border-0 bg-light fw-bold <?= $status_color ?>" style="width: 140px; cursor:pointer;" onchange="this.form.submit()">
                                        <option value="en_attente" <?= $r['status_reservation']=='en_attente'?'selected':'' ?>>En attente</option>
                                        <option value="validee"    <?= $r['status_reservation']=='validee'?'selected':'' ?>>Validée</option>
                                        <option value="annulee"    <?= $r['status_reservation']=='annulee'?'selected':'' ?>>Annulée</option>
                                    </select>
                                </form>
                            <?php else: ?>
                                <?php 
                                $badge_class = match($r['status_reservation']) {
                                    'en_cours' => 'bg-primary text-white',
                                    'terminee' => 'bg-secondary text-white',
                                    'annulee'  => 'bg-danger text-white',
                                    default    => 'bg-light text-dark'
                                };
                                ?>
                                <span class="badge <?= $badge_class ?> rounded-pill px-3 py-2" style="width: 140px; font-size: 0.8rem;">
                                    <?= ucfirst(str_replace('_', ' ', $r['status_reservation'])) ?>
                                </span>
                            <?php endif; ?>
                        </td>
                        <td class="pe-4 text-end">
                            <div class="d-flex justify-content-end gap-2">
                                <!-- Bouton de Validation Rapide -->
                                <?php if($r['status_reservation'] == 'en_attente'): ?>
                                    <form action="index.php?action=admin_res_update" method="POST" class="m-0 p-0">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="id" value="<?= $r['id'] ?>">
                                        <input type="hidden" name="status" value="validee">
                                        <button type="submit" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm fw-bold" title="Confirmer la réservation">
                                            <i class="fa-solid fa-check me-1"></i> Valider
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <!-- Actions Opérationnelles -->
                                <?php if($r['status_reservation'] == 'validee'): ?>
                                    <a href="index.php?action=admin_checkout_process&id=<?= $r['id'] ?>" class="btn btn-warning btn-sm rounded-pill px-3 shadow-sm fw-bold text-dark" title="Démarrer le Départ">
                                        <i class="fa-solid fa-key me-1"></i> Départ
                                    </a>
                                <?php endif; ?>

                                <?php if($r['status_reservation'] == 'en_cours'): ?>
                                    <a href="index.php?action=admin_checkin_process&id=<?= $r['id'] ?>" class="btn btn-success btn-sm rounded-pill px-3 shadow-sm fw-bold" title="Effectuer le Retour">
                                        <i class="fa-solid fa-car-on me-1"></i> Retour
                                    </a>
                                <?php endif; ?>

                                <!-- Consultation -->
                                <a href="index.php?action=admin_reservation_detail&id=<?= $r['id'] ?>" class="btn btn-light btn-sm rounded-circle shadow-sm" style="width: 35px; height: 35px;" title="Détails complets">
                                    <i class="fa-solid fa-eye text-primary"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>