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
    <div class="table-responsive">
    <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
        <thead class="bg-light text-muted small text-uppercase">
            <tr><th class="ps-4 py-3">Réservation</th><th class="py-3">Véhicule</th><th class="py-3">Client</th><th class="py-3">Action Requise</th><th class="pe-4 py-3 text-end">Action</th></tr>
        </thead>
        <tbody class="border-top-0">
            <?php if(empty($reservations)): ?>
            <tr>
                <td colspan="5" class="text-center py-5">
                    <i class="fa-solid fa-mug-hot fa-3x text-muted mb-3"></i>
                    <h5 class="fw-bold text-dark">Le comptoir est vide !</h5>
                    <p class="text-muted">Il n'y a pas de véhicules à remettre (Check-out) ou à réceptionner (Check-in) pour le moment.<br>Allez dans le menu <strong>Réservations</strong> et changez le statut d'une réservation en <strong>"Validée"</strong> pour la voir apparaître ici.</p>
                </td>
            </tr>
            <?php else: ?>
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
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>