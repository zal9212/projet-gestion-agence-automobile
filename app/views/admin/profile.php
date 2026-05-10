<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h2 class="fw-bold mb-1">Mon Profil Administrateur</h2>
        <p class="text-muted">Gérez vos informations personnelles et votre sécurité.</p>
    </div>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 p-5">
            <div class="d-flex align-items-center mb-5">
                <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['prenom']) ?>&background=1a1a1a&color=fff&size=128" class="rounded-circle shadow-sm me-4" style="width: 80px;">
                <div>
                    <h4 class="fw-bold mb-0"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h4>
                    <span class="badge bg-dark text-warning rounded-pill mt-1">Administrateur AutoRent</span>
                </div>
            </div>

            <form action="index.php?action=profile_save" method="POST">
                <div class="row g-4 mb-5">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Prénom</label>
                        <input type="text" name="prenom" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= htmlspecialchars($user['prenom']) ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Nom</label>
                        <input type="text" name="nom" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= htmlspecialchars($user['nom']) ?>" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold text-muted small ms-2">Email Professionnel</label>
                        <input type="email" name="email" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    <div class="col-md-12">
                        <label class="form-label fw-bold text-muted small ms-2">Téléphone</label>
                        <input type="text" name="telephone" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= htmlspecialchars($user['telephone']) ?>">
                    </div>
                    
                    <div class="col-md-12 mt-5 pt-3 border-top">
                        <h5 class="fw-bold mb-4">Modifier le mot de passe</h5>
                        <p class="text-muted small mb-4">Laissez vide pour conserver votre mot de passe actuel.</p>
                        <label class="form-label fw-bold text-muted small ms-2">Nouveau Mot de passe</label>
                        <input type="password" name="password" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" placeholder="••••••••">
                    </div>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn btn-dark btn-lg rounded-pill px-5 fw-bold shadow">Enregistrer les modifications</button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>
