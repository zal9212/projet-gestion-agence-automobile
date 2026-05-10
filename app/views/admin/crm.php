<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-5">
    <div><h2 class="fw-bold mb-1">CRM & Clients</h2><p class="text-muted">Gérez votre base de clients, leurs documents KYC et la liste noire.</p></div>
</div>
<div class="card border-0 shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
    <table class="table table-hover align-middle mb-0" style="min-width: 800px;">
        <thead class="bg-light text-muted small text-uppercase">
            <tr><th class="ps-4 py-3">Client</th><th class="py-3">Contact</th><th class="py-3">Docs KYC</th><th class="py-3">Statut</th><th class="pe-4 py-3 text-end">Action</th></tr>
        </thead>
        <tbody class="border-top-0">
            <?php foreach($users as $u): ?>
            <tr>
                <td class="ps-4 fw-bold text-dark"><?= htmlspecialchars($u['nom'].' '.$u['prenom']) ?></td>
                <td class="text-muted small"><i class="fa-solid fa-envelope me-1"></i> <?= htmlspecialchars($u['email']) ?><br><i class="fa-solid fa-phone me-1"></i> <?= htmlspecialchars($u['telephone'] ?? 'N/A') ?></td>
                <td>
                    <?php if($u['piece_identite_url'] || $u['permis_url']): ?>
                        <span class="badge bg-success text-white"><i class="fa-solid fa-check me-1"></i> Reçus</span>
                    <?php else: ?>
                        <span class="badge bg-warning text-dark"><i class="fa-solid fa-clock me-1"></i> En attente</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php if($u['is_blacklisted']): ?>
                        <span class="badge bg-danger">Liste Noire</span>
                    <?php else: ?>
                        <span class="badge bg-success bg-opacity-10 text-success">Actif</span>
                    <?php endif; ?>
                </td>
                <td class="pe-4 text-end">
                    <a href="index.php?action=admin_crm_blacklist&id=<?= $u['id'] ?>" class="btn btn-light btn-sm rounded-circle" title="<?= $u['is_blacklisted'] ? 'Réhabiliter' : 'Bannir' ?>" onclick="return confirm('Modifier le statut de ce client ?');">
                        <i class="fa-solid <?= $u['is_blacklisted'] ? 'fa-check text-success' : 'fa-ban text-danger' ?>"></i>
                    </a>
                    <a href="index.php?action=admin_crm_view&id=<?= $u['id'] ?>" class="btn btn-light btn-sm rounded-circle"><i class="fa-solid fa-eye text-primary"></i></a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>