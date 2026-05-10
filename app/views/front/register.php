<?php $title = "Inscription"; ob_start(); ?>
<div class="container mt-5 mb-5 pb-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-lg border-0 rounded-4 p-5 text-center">
                <div class="mb-4">
                    <i class="fa-solid fa-car-side fa-3x" style="color: var(--accent-yellow);"></i>
                    <h2 class="fw-bold mt-3">Créer un compte</h2>
                    <p class="text-muted">Rejoignez Teranga Auto en quelques clics</p>
                </div>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger rounded-4 border-0 text-start"><i class="fa-solid fa-circle-exclamation me-2"></i><?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
                <?php endif; ?>

                <form action="index.php?action=do_register" method="POST" class="text-start">
                    <?= csrf_field() ?>
                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small ms-2">Prénom</label>
                            <input type="text" name="prenom" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small ms-2">Nom</label>
                            <input type="text" name="nom" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" required>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-bold text-muted small ms-2">Adresse Email</label>
                        <input type="email" name="email" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" required>
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-bold text-muted small ms-2">Mot de passe</label>
                        <input type="password" name="password" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" required>
                    </div>
                    <button type="submit" class="btn btn-warning btn-lg w-100 fw-bold rounded-pill mb-4 shadow-sm" style="height: 55px;">S'inscrire</button>
                    <div class="text-center">
                        <span class="text-muted">Vous avez déjà un compte ?</span> <a href="index.php?action=login" class="text-dark fw-bold text-decoration-none">Se connecter</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/front.php'; ?>