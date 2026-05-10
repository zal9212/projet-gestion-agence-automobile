<?php $title = "Réserver ".$car['marque']; ob_start(); ?>
<div class="container-fluid px-4 px-md-5 mt-4 mt-md-5 mb-5 pb-5">
    <div class="row g-5">
        <div class="col-lg-4 order-lg-2">
            <div class="card border-0 shadow-lg rounded-4 overflow-hidden position-sticky" style="top: 100px;">
                <div style="height: 200px; overflow:hidden; flex-shrink:0;">
                    <img src="<?= htmlspecialchars($car['image_principale']) ?>" style="width:100%; height:100%; object-fit:cover;">
                </div>
                <div class="card-body p-4 bg-white">
                    <span class="badge bg-dark text-white mb-2 px-3 py-2 rounded-pill"><?= htmlspecialchars($car['categorie_nom']) ?></span>
                    <div class="d-flex align-items-center mb-4">
                        <img src="<?= $car['brand_logo'] ?: 'https://raw.githubusercontent.com/fawazahmed0/car-logos/master/logos/' . strtolower($car['marque']) . '.png' ?>" 
                             style="height: 40px; width: 40px; object-fit: contain;" class="me-3"
                             onerror="this.style.display='none'">
                        <h3 class="fw-bold mb-0"><?= htmlspecialchars($car['marque'] . ' ' . $car['modele']) ?></h3>
                    </div>
                    
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                        <span class="text-muted">Tarif Journalier</span>
                        <span class="fw-bold text-success fs-5"><?= number_format($car['prix_journalier'], 0, ',', ' ') ?> FCFA</span>
                    </div>
                    <div class="d-flex justify-content-between mb-3 border-bottom pb-3">
                        <span class="text-muted">Dépôt de Garantie (Caution) <i class="fa-solid fa-circle-info small"></i></span>
                        <span class="fw-bold"><?= number_format($car['caution'], 0, ',', ' ') ?> FCFA</span>
                    </div>
                    
                    <div class="bg-light p-3 rounded-4 mt-4">
                        <h6 class="fw-bold mb-3"><i class="fa-solid fa-shield-halved text-warning me-2"></i> Protections Incluses</h6>
                        <ul class="list-unstyled mb-0 small text-muted">
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Protection contre le vol</li>
                            <li class="mb-2"><i class="fa-solid fa-check text-success me-2"></i> Assurance Responsabilité Civile</li>
                            <li><i class="fa-solid fa-check text-success me-2"></i> Annulation Gratuite</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-8 order-lg-1">
            <a href="javascript:history.back()" class="text-decoration-none text-muted mb-4 d-inline-block"><i class="fa-solid fa-arrow-left me-2"></i> Retour aux résultats</a>
            
            <h2 class="fw-bold mb-4">Finalisez votre réservation</h2>
            
            <?php if (isset($_SESSION['error'])): ?>
                <div class="alert alert-danger rounded-4 border-0 shadow-sm"><i class="fa-solid fa-triangle-exclamation me-2"></i> <?= $_SESSION['error']; unset($_SESSION['error']); ?></div>
            <?php endif; ?>

            <form action="index.php?action=confirm_reserve" method="POST">
                <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                
                <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold mb-0"><span class="badge bg-warning text-dark rounded-circle me-2" style="width: 25px; height: 25px; display: inline-flex; align-items: center; justify-content: center;">1</span> Période de Location</h5>
                        <?php if (!empty($bookings)): ?>
                            <button type="button" class="btn btn-light btn-sm rounded-pill px-3" data-bs-toggle="collapse" data-bs-target="#bookedDates">Voir indisponibilités</button>
                        <?php endif; ?>
                    </div>

                    <?php if (!empty($bookings)): ?>
                    <div class="collapse mb-4" id="bookedDates">
                        <div class="p-3 bg-light rounded-4">
                            <h6 class="small fw-bold mb-2"><i class="fa-solid fa-calendar-xmark text-danger me-2"></i> Déjà réservé aux dates suivantes :</h6>
                            <ul class="list-unstyled mb-0 small row g-1">
                                <?php foreach($bookings as $b): ?>
                                    <li class="col-md-6"><span class="badge bg-white text-dark border fw-medium"><?= date('d/m/Y', strtotime($b['date_debut'])) ?> au <?= date('d/m/Y', strtotime($b['date_fin'])) ?></span></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Date de départ</label>
                            <input type="date" name="date_debut" class="form-control form-control-lg bg-light border-0 rounded-4" value="<?= $_GET['date_debut'] ?? '' ?>" required min="<?= date('Y-m-d') ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Date de retour</label>
                            <input type="date" name="date_fin" class="form-control form-control-lg bg-light border-0 rounded-4" value="<?= $_GET['date_fin'] ?? '' ?>" required min="<?= date('Y-m-d') ?>">
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4 rounded-4 mb-4">
                    <h5 class="fw-bold mb-4"><span class="badge bg-warning text-dark rounded-circle me-2" style="width: 25px; height: 25px; display: inline-flex; align-items: center; justify-content: center;">2</span> Lieux de prise en charge</h5>
                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Lieu de départ</label>
                            <select name="lieu_prise_id" class="form-select form-select-lg bg-light border-0 rounded-4">
                                <option value="">Choisir un lieu...</option>
                                <?php foreach($locations as $loc): ?>
                                    <option value="<?= $loc['id'] ?>"><?= htmlspecialchars($loc['nom']) ?> <?= $loc['frais_supplementaire'] > 0 ? '(+'.number_format($loc['frais_supplementaire'],0,',',' ').' FCFA)' : '(Inclus)' ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Lieu de restitution</label>
                            <select name="lieu_retour_id" class="form-select form-select-lg bg-light border-0 rounded-4">
                                <option value="">Choisir un lieu...</option>
                                <?php foreach($locations as $loc): ?>
                                    <option value="<?= $loc['id'] ?>"><?= htmlspecialchars($loc['nom']) ?> <?= $loc['frais_supplementaire'] > 0 ? '(+'.number_format($loc['frais_supplementaire'],0,',',' ').' FCFA)' : '(Inclus)' ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm p-4 rounded-4 mb-5">
                    <h5 class="fw-bold mb-4"><span class="badge bg-warning text-dark rounded-circle me-2" style="width: 25px; height: 25px; display: inline-flex; align-items: center; justify-content: center;">3</span> Options & Extras</h5>
                    
                    <div class="form-check form-switch d-flex align-items-center p-3 border rounded-4">
                        <input class="form-check-input ms-0 mt-0" type="checkbox" role="switch" id="chauffeur" name="avec_chauffeur" value="1" style="width: 40px; height: 20px; cursor: pointer;">
                        <label class="form-check-label ms-3 w-100 d-flex justify-content-between align-items-center" for="chauffeur" style="cursor: pointer;">
                            <div>
                                <strong class="d-block">Chauffeur Privé</strong>
                                <small class="text-muted">Détendez-vous et laissez un professionnel vous conduire.</small>
                            </div>
                            <span class="badge bg-dark rounded-pill px-3 py-2">+15 000 FCFA / jour</span>
                        </label>
                    </div>
                </div>

                <button type="submit" class="btn btn-dark btn-lg w-100 fw-bold rounded-pill shadow" style="height: 60px;">Confirmer & Payer plus tard <i class="fa-solid fa-arrow-right ms-2"></i></button>
            </form>
        </div>
    </div>
</div>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    const debut = new Date(document.querySelector('input[name="date_debut"]').value);
    const fin = new Date(document.querySelector('input[name="date_fin"]').value);
    
    if (fin < debut) {
        e.preventDefault();
        alert("La date de retour ne peut pas être avant la date de départ !");
    }
});
</script>
<?php $content = ob_get_clean(); require 'app/views/layouts/front.php'; ?>