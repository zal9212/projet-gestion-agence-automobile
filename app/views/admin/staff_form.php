<?php ob_start(); 
$isEdit = isset($member) && $member;
?>
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <a href="index.php?action=admin_staff" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="fa-solid fa-arrow-left me-2"></i> Retour à la liste</a>
        <h2 class="fw-bold mb-1"><?= $isEdit ? 'Modifier le membre' : 'Ajouter un membre' ?></h2>
        <p class="text-muted">Définissez les accès et informations du personnel.</p>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm rounded-4 p-5">
            <form action="index.php?action=admin_staff_save" method="POST">
                <?= csrf_field() ?>
                <?php if($isEdit): ?>
                    <input type="hidden" name="id" value="<?= $member['id'] ?>">
                <?php endif; ?>

                <div class="row g-4 mb-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Prénom</label>
                        <input type="text" name="prenom" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $isEdit ? htmlspecialchars($member['prenom']) : '' ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Nom</label>
                        <input type="text" name="nom" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $isEdit ? htmlspecialchars($member['nom']) : '' ?>" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold text-muted small ms-2">Email</label>
                        <input type="email" name="email" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $isEdit ? htmlspecialchars($member['email']) : '' ?>" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold text-muted small ms-2">Téléphone</label>
                        <input type="text" name="telephone" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $isEdit ? htmlspecialchars($member['telephone']) : '' ?>">
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold text-muted small ms-2">Rôle</label>
                        <select name="role" class="form-select form-select-lg bg-light border-0 rounded-pill px-4" required>
                            <option value="employee" <?= ($isEdit && $member['role'] == 'employee') ? 'selected' : '' ?>>Employé (Gestion standard)</option>
                            <option value="admin" <?= ($isEdit && $member['role'] == 'admin') ? 'selected' : '' ?>>Administrateur (Accès total)</option>
                        </select>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold text-muted small ms-2">Mot de passe <?= $isEdit ? '(Laissez vide pour ne pas changer)' : '' ?></label>
                        <input type="password" name="password" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" <?= $isEdit ? '' : 'required' ?> placeholder="<?= $isEdit ? '••••••••' : 'Minimum 6 caractères' ?>">
                    </div>
                </div>

                <div class="text-end pt-3">
                    <button type="submit" class="btn btn-dark btn-lg rounded-pill px-5 fw-bold w-100">
                        <?= $isEdit ? 'Enregistrer les modifications' : 'Créer le compte personnel' ?>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>
