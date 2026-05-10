<?php $title = "Mon Profil"; ob_start(); ?>
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                <div class="text-center mb-5">
                    <?php if(!empty($user['photo_profil'])): ?>
                        <img src="<?= htmlspecialchars($user['photo_profil']) ?>" class="rounded-circle shadow-sm mb-3" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #f4c053;">
                    <?php else: ?>
                        <img src="https://ui-avatars.com/api/?name=<?= urlencode($user['prenom']) ?>&background=random&size=128" class="rounded-circle shadow-sm mb-3" style="width: 100px;">
                    <?php endif; ?>
                    <h3 class="fw-bold mb-1"><?= htmlspecialchars($user['prenom'] . ' ' . $user['nom']) ?></h3>
                    <p class="text-muted small">Membre depuis <?= date('M Y', strtotime($user['date_inscription'])) ?></p>
                </div>

                <form action="index.php?action=profile_save" method="POST" enctype="multipart/form-data">
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small ms-2">Changer ma photo de profil</label>
                        <input type="file" name="photo_profil" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" accept="image/*">
                    </div>
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
                            <label class="form-label fw-bold text-muted small ms-2">Email</label>
                            <input type="email" name="email" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= htmlspecialchars($user['email']) ?>" required>
                        </div>
                        <div class="col-md-12">
                            <label class="form-label fw-bold text-muted small ms-2">Téléphone</label>
                            <input type="text" name="telephone" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= htmlspecialchars($user['telephone']) ?>" placeholder="Ex: +221 77 ...">
                        </div>
                        <div class="col-md-12 mt-5">
                            <h5 class="fw-bold mb-4">Sécurité (Laisser vide pour ne pas changer)</h5>
                            <label class="form-label fw-bold text-muted small ms-2">Nouveau Mot de passe</label>
                            <input type="password" name="password" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" placeholder="••••••••">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center">
                        <a href="index.php?action=logout" class="btn btn-outline-danger rounded-pill px-4 fw-bold"><i class="fa-solid fa-power-off me-2"></i> Déconnexion</a>
                        <button type="submit" class="btn btn-dark btn-lg rounded-pill px-5 fw-bold">Mettre à jour mon profil</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/front.php'; ?>
