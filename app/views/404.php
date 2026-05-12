<?php $title = "Page non trouvée"; ob_start(); ?>
<div class="container d-flex align-items-center justify-content-center" style="min-height: 80vh;">
    <div class="text-center">
        <div class="position-relative mb-5">
            <h1 class="fw-bold display-1 text-light" style="font-size: 10rem; letter-spacing: -5px; opacity: 0.5;">404</h1>
            <div class="position-absolute top-50 start-50 translate-middle w-100">
                <i class="fa-solid fa-car-side fa-4x text-warning mb-3"></i>
                <h2 class="fw-bold text-dark">Oups ! Route introuvable</h2>
            </div>
        </div>
        
        <p class="text-muted fs-5 mb-5 mx-auto" style="max-width: 500px;">
            Il semble que vous ayez pris un mauvais virage. La page que vous recherchez n'existe pas ou a été déplacée.
        </p>
        
        <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
            <a href="index.php" class="btn btn-dark rounded-pill px-5 py-3 fw-bold shadow-sm">
                <i class="fa-solid fa-house me-2"></i> Retour à l'accueil
            </a>
            <a href="index.php?action=search" class="btn btn-outline-dark rounded-pill px-5 py-3 fw-bold">
                <i class="fa-solid fa-car me-2"></i> Voir notre flotte
            </a>
        </div>
    </div>
</div>

<style>
    body { background-color: #fdfdfd; }
    h1 {
        background: linear-gradient(180deg, #f0f0f0 0%, rgba(240,240,240,0) 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
</style>

<?php $content = ob_get_clean(); require 'app/views/layouts/front.php'; ?>
