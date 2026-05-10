<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h2 class="fw-bold mb-1">Toutes les Réservations</h2>
        <p class="text-muted">Gérez les demandes et changez les statuts.</p>
    </div>
    <div class="d-flex gap-2">
        <div class="dropdown">
            <button class="btn btn-outline-dark rounded-pill px-4 dropdown-toggle" type="button" data-bs-toggle="dropdown">
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
            <a href="index.php?action=admin_reservations_export" class="btn btn-dark rounded-pill px-4"><i class="fa-solid fa-download me-2"></i> Exporter</a>
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