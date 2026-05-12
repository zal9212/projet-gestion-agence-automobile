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
                    </div>

                    <!-- Calendrier Interactif -->
                    <div class="calendar-container mb-4">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="fw-bold mb-0 text-muted small" id="calendarMonthName">Chargement...</h6>
                            <div class="d-flex gap-1">
                                <button type="button" class="btn btn-light btn-sm rounded-circle" onclick="changeMonth(-1)"><i class="fa-solid fa-chevron-left"></i></button>
                                <button type="button" class="btn btn-light btn-sm rounded-circle" onclick="changeMonth(1)"><i class="fa-solid fa-chevron-right"></i></button>
                            </div>
                        </div>
                        <div class="calendar-grid">
                            <div class="calendar-day-header">Lun</div>
                            <div class="calendar-day-header">Mar</div>
                            <div class="calendar-day-header">Mer</div>
                            <div class="calendar-day-header">Jeu</div>
                            <div class="calendar-day-header">Ven</div>
                            <div class="calendar-day-header">Sam</div>
                            <div class="calendar-day-header">Dim</div>
                        </div>
                        <div id="calendarDays" class="calendar-grid">
                            <!-- Les jours seront générés par JS -->
                        </div>
                        
                        <div class="d-flex gap-4 mt-3 small justify-content-center">
                            <div class="d-flex align-items-center"><span class="dot bg-danger me-2"></span> Indisponible</div>
                            <div class="d-flex align-items-center"><span class="dot bg-warning me-2"></span> Sélectionné</div>
                            <div class="d-flex align-items-center"><span class="dot bg-light border me-2"></span> Libre</div>
                        </div>
                    </div>

                    <style>
                        .calendar-grid { display: grid; grid-template-columns: repeat(7, 1fr); gap: 5px; }
                        .calendar-day-header { text-align: center; font-size: 0.75rem; font-weight: bold; color: #aaa; padding: 5px 0; }
                        .calendar-day { 
                            aspect-ratio: 1/1; display: flex; align-items: center; justify-content: center; 
                            border-radius: 12px; font-size: 0.9rem; cursor: pointer; transition: all 0.2s;
                            position: relative;
                        }
                        .calendar-day:hover:not(.reserved) { background: #f0f0f0; }
                        .calendar-day.reserved { background: #fee2e2; color: #ef4444; cursor: not-allowed; font-weight: bold; }
                        .calendar-day.selected { background: var(--accent-yellow) !important; color: #000; font-weight: bold; box-shadow: 0 4px 10px rgba(255, 193, 7, 0.3); }
                        .calendar-day.today { border: 2px solid #eee; }
                        .dot { width: 10px; height: 10px; border-radius: 50%; display: inline-block; }
                    </style>

                    <div class="row g-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Date de départ</label>
                            <input type="date" id="input_date_debut" name="date_debut" class="form-control form-control-lg bg-light border-0 rounded-4" value="<?= $_GET['date_debut'] ?? '' ?>" required readonly>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold text-muted small">Date de retour</label>
                            <input type="date" id="input_date_fin" name="date_fin" class="form-control form-control-lg bg-light border-0 rounded-4" value="<?= $_GET['date_fin'] ?? '' ?>" required readonly>
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
const bookedPeriods = <?= json_encode($bookings) ?>;
let currentMonth = new Date();
let selection = { start: null, end: null };

// Initialiser les dates si présentes dans l'URL
if (document.getElementById('input_date_debut').value) selection.start = new Date(document.getElementById('input_date_debut').value);
if (document.getElementById('input_date_fin').value) selection.end = new Date(document.getElementById('input_date_fin').value);

function renderCalendar() {
    const year = currentMonth.getFullYear();
    const month = currentMonth.getMonth();
    const monthName = new Intl.DateTimeFormat('fr-FR', { month: 'long', year: 'numeric' }).format(currentMonth);
    document.getElementById('calendarMonthName').innerText = monthName.charAt(0).toUpperCase() + monthName.slice(1);

    const firstDay = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();
    const startOffset = (firstDay === 0 ? 6 : firstDay - 1); // Ajuster pour commencer par Lundi

    let html = '';
    for (let i = 0; i < startOffset; i++) html += '<div></div>';

    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const dateObj = new Date(year, month, day);
        const isReserved = bookedPeriods.some(b => {
            const start = new Date(b.date_debut);
            const end = new Date(b.date_fin);
            return dateObj >= start && dateObj <= end;
        });

        let classes = 'calendar-day';
        if (isReserved) classes += ' reserved';
        if (dateObj.toDateString() === new Date().toDateString()) classes += ' today';
        if (selection.start && dateObj.toDateString() === selection.start.toDateString()) classes += ' selected';
        if (selection.end && dateObj.toDateString() === selection.end.toDateString()) classes += ' selected';
        if (selection.start && selection.end && dateObj > selection.start && dateObj < selection.end) classes += ' selected';

        html += `<div class="${classes}" onclick="selectDate('${dateStr}', ${isReserved})">${day}</div>`;
    }
    document.getElementById('calendarDays').innerHTML = html;
}

function selectDate(dateStr, isReserved) {
    if (isReserved) return;
    const date = new Date(dateStr);
    
    if (!selection.start || (selection.start && selection.end)) {
        selection.start = date;
        selection.end = null;
    } else if (date < selection.start) {
        selection.start = date;
    } else {
        // Vérifier s'il y a une réservation qui intersecte la plage [selection.start, date]
        const conflict = bookedPeriods.some(b => {
            const bStart = new Date(b.date_debut); bStart.setHours(0,0,0,0);
            const bEnd = new Date(b.date_fin); bEnd.setHours(0,0,0,0);
            const sStart = new Date(selection.start); sStart.setHours(0,0,0,0);
            const sEnd = new Date(date); sEnd.setHours(0,0,0,0);
            
            return (bStart <= sEnd && bEnd >= sStart);
        });
        
        if (conflict) {
            alert("Il y a une réservation sur cette période !");
            selection.start = date;
        } else {
            selection.end = date;
        }
    }

    updateInputs();
    renderCalendar();
}

function updateInputs() {
    if (selection.start) document.getElementById('input_date_debut').value = selection.start.toISOString().split('T')[0];
    if (selection.end) document.getElementById('input_date_fin').value = selection.end.toISOString().split('T')[0];
}

function changeMonth(dir) {
    currentMonth.setMonth(currentMonth.getMonth() + dir);
    renderCalendar();
}

renderCalendar();

document.querySelector('form').addEventListener('submit', function(e) {
    if (!selection.start || !selection.end) {
        e.preventDefault();
        alert("Veuillez sélectionner une période complète sur le calendrier.");
    }
});
</script>
<?php $content = ob_get_clean(); require 'app/views/layouts/front.php'; ?>