<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-end mb-4">
    <div>
        <h2 class="fw-bold mb-1">Gestion du Personnel</h2>
        <p class="text-muted">Gérez les accès administrateurs et employés de la plateforme.</p>
    </div>
    <a href="index.php?action=admin_staff_form" class="btn btn-dark rounded-pill px-4"><i class="fa-solid fa-user-plus me-2"></i> Ajouter un membre</a>
</div>

<?php if(isset($_SESSION['success'])): ?>
    <div class="alert alert-success rounded-4 border-0 mb-4"><?= $_SESSION['success']; unset($_SESSION['success']); ?></div>
<?php endif; ?>
<?php if(isset($_SESSION['error'])): ?>
    <div class="alert alert-danger rounded-4 border-0 mb-4"><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
<?php endif; ?>

<div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead class="text-muted small text-uppercase" style="letter-spacing: 1px;">
                    <tr>
                        <th>Membre</th>
                        <th>Rôle</th>
                        <th>Email & Tel</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody class="border-top-0">
                    <?php foreach($staff as $s): ?>
                    <tr>
                        <td>
                            <div class="d-flex align-items-center">
                                <img src="https://ui-avatars.com/api/?name=<?= urlencode($s['prenom'].' '.$s['nom']) ?>&background=random" class="rounded-circle me-3" style="width:40px;">
                                <div>
                                    <h6 class="fw-bold mb-0"><?= htmlspecialchars($s['prenom'].' '.$s['nom']) ?></h6>
                                    <small class="text-muted">Inscrit le <?= date('d/m/Y', strtotime($s['date_inscription'])) ?></small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <?php if($s['role'] === 'admin'): ?>
                                <span class="badge bg-danger bg-opacity-10 text-danger rounded-pill px-3">Administrateur</span>
                            <?php else: ?>
                                <span class="badge bg-primary bg-opacity-10 text-primary rounded-pill px-3">Employé</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="small fw-medium"><?= htmlspecialchars($s['email']) ?></div>
                            <div class="small text-muted"><?= htmlspecialchars($s['telephone'] ?: 'N/A') ?></div>
                        </td>
                        <td class="text-end">
                            <a href="index.php?action=admin_staff_form&id=<?= $s['id'] ?>" class="btn btn-light btn-sm rounded-circle me-2"><i class="fa-solid fa-pen"></i></a>
                            <?php if($s['id'] != $_SESSION['user_id']): ?>
                                <a href="index.php?action=admin_staff_delete&id=<?= $s['id'] ?>&csrf_token=<?= generate_csrf_token() ?>" class="btn btn-light btn-sm rounded-circle text-danger" onclick="return confirm('Supprimer ce membre ?')"><i class="fa-solid fa-trash"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>
