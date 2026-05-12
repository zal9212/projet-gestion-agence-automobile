<?php ob_start(); ?>
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <a href="index.php?action=admin_checkin" class="text-decoration-none text-muted mb-2 d-inline-block"><i class="fa-solid fa-arrow-left me-2"></i> Retour au Comptoir</a>
        <h2 class="fw-bold mb-1">Remise du Véhicule (Départ)</h2>
        <p class="text-muted">Remplissez l'état de départ et demandez au client de signer.</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-4">
        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
            <h5 class="fw-bold mb-3">Réservation #<?= $reservation['id'] ?></h5>
            <div class="d-flex align-items-center mb-3 pb-3 border-bottom">
                <div class="bg-light rounded p-3 me-3"><i class="fa-solid fa-car text-dark fa-2x"></i></div>
                <div>
                    <h6 class="fw-bold mb-0"><?= htmlspecialchars($reservation['marque'] . ' ' . $reservation['modele']) ?></h6>
                    <span class="badge bg-dark text-white rounded-pill"><?= htmlspecialchars($reservation['immatriculation']) ?></span>
                </div>
            </div>
            <p class="text-muted small mb-1">Client</p>
            <h6 class="fw-bold mb-3"><?= htmlspecialchars($reservation['nom'] . ' ' . $reservation['prenom']) ?></h6>
            <p class="text-muted small mb-1">Période</p>
            <h6 class="fw-bold mb-0"><?= date('d/m/Y', strtotime($reservation['date_debut'])) ?> &rarr; <?= date('d/m/Y', strtotime($reservation['date_fin'])) ?></h6>
        </div>
    </div>

    <div class="col-lg-8">
        <form action="index.php?action=admin_save_checkout" method="POST" enctype="multipart/form-data" id="checkoutForm">
            <?= csrf_field() ?>
            <input type="hidden" name="id" value="<?= $reservation['id'] ?>">
            <input type="hidden" name="signature_base64" id="signature_data">
            
            <div class="card border-0 shadow-sm rounded-4 p-3 p-md-5 mb-4">
                <h5 class="fw-bold mb-4">1. État au Départ</h5>
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Kilométrage de Départ (km)</label>
                        <input type="number" name="kilometrage_depart" class="form-control form-control-lg bg-light border-0 rounded-pill px-4" value="<?= $reservation['kilometrage'] ?? 0 ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold text-muted small ms-2">Niveau de Carburant</label>
                        <select name="niveau_carburant_depart" class="form-select form-select-lg bg-light border-0 rounded-pill px-4" required>
                            <option value="1/4">1/4 (Basse)</option>
                            <option value="1/2">1/2 (Moitié)</option>
                            <option value="3/4">3/4</option>
                            <option value="Plein" selected>Plein</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-3 p-md-5 mb-4">
                <h5 class="fw-bold mb-4">2. Inspection Photos (Facultatif)</h5>
                <div class="bg-light p-4 rounded-4 text-center border border-2 border-dashed">
                    <i class="fa-solid fa-camera fa-2x text-muted mb-3"></i>
                    <p class="text-muted small mb-3">Prenez des photos du véhicule (4 côtés) pour prouver l'état initial.</p>
                    <input type="file" name="photos_depart[]" class="form-control form-control-sm border-0 bg-white rounded-pill" multiple accept="image/*" capture="environment">
                </div>
            </div>

            <div class="card border-0 shadow-sm rounded-4 p-5 mb-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h5 class="fw-bold mb-0">3. Signature du Client</h5>
                    <button type="button" class="btn btn-light btn-sm rounded-pill px-3" onclick="clearSignature()"><i class="fa-solid fa-eraser me-2"></i> Effacer</button>
                </div>
                <div class="border rounded-4 bg-light p-2 mb-3" style="touch-action: none;">
                    <canvas id="signaturePad" width="600" height="350" style="width: 100%; height: 350px; border-radius: 15px; background: white; cursor: crosshair;"></canvas>
                </div>
                <p class="text-muted small text-center mb-0">Je confirme avoir pris connaissance de l'état du véhicule détaillé ci-dessus.</p>
            </div>

            <button type="submit" class="btn btn-success btn-lg w-100 fw-bold rounded-pill shadow" onclick="return saveSignature()"><i class="fa-solid fa-check-circle me-2"></i> Confirmer Départ & Générer le Contrat</button>
        </form>
    </div>
</div>

<script>
    const canvas = document.getElementById('signaturePad');
    const ctx = canvas.getContext('2d');
    let isDrawing = false;
    let hasSigned = false;

    // Ajuster la résolution du canvas pour éviter le flou et les problèmes de capture
    function resizeCanvas() {
        const ratio = Math.max(window.devicePixelRatio || 1, 1);
        canvas.width = canvas.offsetWidth * ratio;
        canvas.height = canvas.offsetHeight * ratio;
        ctx.scale(ratio, ratio);
        ctx.lineWidth = 3;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#1a1a1a';
    }

    window.addEventListener('resize', resizeCanvas);
    resizeCanvas();

    function getMousePos(e) {
        const rect = canvas.getBoundingClientRect();
        const clientX = e.clientX || (e.touches && e.touches[0].clientX);
        const clientY = e.clientY || (e.touches && e.touches[0].clientY);
        return {
            x: clientX - rect.left,
            y: clientY - rect.top
        };
    }

    function startDrawing(e) {
        isDrawing = true;
        const pos = getMousePos(e);
        ctx.beginPath();
        ctx.moveTo(pos.x, pos.y);
    }

    function draw(e) {
        if (!isDrawing) return;
        e.preventDefault();
        const pos = getMousePos(e);
        ctx.lineTo(pos.x, pos.y);
        ctx.stroke();
        hasSigned = true;
    }

    function stopDrawing() {
        isDrawing = false;
    }

    canvas.addEventListener('mousedown', startDrawing);
    canvas.addEventListener('mousemove', draw);
    window.addEventListener('mouseup', stopDrawing);

    canvas.addEventListener('touchstart', startDrawing, {passive: false});
    canvas.addEventListener('touchmove', draw, {passive: false});
    canvas.addEventListener('touchend', stopDrawing);

    function clearSignature() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        hasSigned = false;
        document.getElementById('signature_data').value = '';
    }

    function saveSignature() {
        if (!hasSigned) {
            alert("Veuillez demander au client de signer avant de confirmer.");
            return false;
        }
        // Exporter en PNG base64
        const dataURL = canvas.toDataURL('image/png');
        document.getElementById('signature_data').value = dataURL;
        return true;
    }
</script>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>