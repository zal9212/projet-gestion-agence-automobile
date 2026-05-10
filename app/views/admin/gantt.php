<?php 
ob_start(); 
$days_in_month = cal_days_in_month(CAL_GREGORIAN, $view_month, $view_year);
$months_fr = [
    1 => 'Janvier', 2 => 'Février', 3 => 'Mars', 4 => 'Avril',
    5 => 'Mai', 6 => 'Juin', 7 => 'Juillet', 8 => 'Août',
    9 => 'Septembre', 10 => 'Octobre', 11 => 'Novembre', 12 => 'Décembre'
];
$month_name_fr = $months_fr[$view_month] . ' ' . $view_year;

// Calculer les mois précédent/suivant
$prev_month = $view_month - 1; $prev_year = $view_year;
if ($prev_month == 0) { $prev_month = 12; $prev_year--; }
$next_month = $view_month + 1; $next_year = $view_year;
if ($next_month == 13) { $next_month = 1; $next_year++; }
?>

<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h2 class="fw-bold mb-1">Planning Interactif (Gantt)</h2>
        <p class="text-muted">Vue globale de l'occupation de la flotte pour <?= $month_name_fr ?>.</p>
    </div>
    <div class="d-flex gap-2">
        <a href="index.php?action=admin_gantt&month=<?= $prev_month ?>&year=<?= $prev_year ?>" class="btn btn-outline-dark rounded-pill px-3 shadow-sm"><i class="fa-solid fa-chevron-left me-1"></i> Précédent</a>
        <a href="index.php?action=admin_gantt&month=<?= date('m') ?>&year=<?= date('Y') ?>" class="btn btn-light rounded-pill px-3 border shadow-sm">Aujourd'hui</a>
        <a href="index.php?action=admin_gantt&month=<?= $next_month ?>&year=<?= $next_year ?>" class="btn btn-outline-dark rounded-pill px-3 shadow-sm">Suivant <i class="fa-solid fa-chevron-right ms-1"></i></a>
    </div>
</div>

<style>
    .gantt-wrapper { position: relative; border-radius: 20px; overflow: hidden; background: white; border: 1px solid #f0f0f0; box-shadow: 0 10px 30px rgba(0,0,0,0.05); }
    .gantt-container { overflow-x: auto; scroll-behavior: smooth; }
    .gantt-header { display: flex; border-bottom: 2px solid #eee; background: #fdfdfd; position: sticky; top: 0; z-index: 20; }
    .gantt-car-col { min-width: 280px; padding: 15px 20px; font-weight: bold; border-right: 1px solid #eee; position: sticky; left: 0; background: white; z-index: 30; }
    .gantt-day { flex: 1; min-width: 50px; text-align: center; padding: 15px 0; border-right: 1px solid #f9f9f9; font-size: 0.85rem; font-weight: 500; color: #666; }
    .gantt-row { display: flex; border-bottom: 1px solid #f5f5f5; position: relative; transition: background 0.2s; }
    .gantt-row:hover { background: #fafafa; }
    .gantt-cell { flex: 1; min-width: 50px; border-right: 1px solid #f9f9f9; height: 65px; }
    .gantt-bar { 
        position: absolute; height: 45px; top: 10px; border-radius: 10px; 
        display: flex; align-items: center; justify-content: center; 
        color: white; font-size: 0.75rem; font-weight: bold; padding: 0 12px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        transition: transform 0.2s, box-shadow 0.2s;
        border: 1px solid rgba(255,255,255,0.2);
    }
    .gantt-bar:hover { transform: translateY(-2px) scale(1.02); box-shadow: 0 8px 20px rgba(0,0,0,0.15); z-index: 50; }
    .today-highlight { background: rgba(255, 193, 7, 0.1) !important; }
    .today-header { background: #ffc107 !important; color: #000 !important; font-weight: 800 !important; }
</style>

<div class="gantt-wrapper mb-5">
    <div class="gantt-container pb-2">
        <div class="gantt-header">
            <div class="gantt-car-col text-muted text-uppercase small" style="letter-spacing: 1px;">Véhicule</div>
            <?php for($i=1; $i<=$days_in_month; $i++): ?>
                <?php $is_today = ($i == (int)date('j') && $view_month == (int)date('m') && $view_year == (int)date('Y')); ?>
                <div class="gantt-day <?= $is_today ? 'today-header' : '' ?>">
                    <?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>
                </div>
            <?php endfor; ?>
        </div>

        <?php foreach($cars as $car): ?>
        <div class="gantt-row">
            <div class="gantt-car-col d-flex align-items-center">
                <div class="bg-light rounded-circle me-3 d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                    <i class="fa-solid fa-car text-muted"></i>
                </div>
                <div>
                    <div class="fw-bold text-dark small mb-0"><?= htmlspecialchars($car['immatriculation']) ?></div>
                    <div class="text-muted" style="font-size: 0.7rem;"><?= htmlspecialchars($car['marque'].' '.$car['modele']) ?></div>
                </div>
            </div>
            
            <?php for($i=1; $i<=$days_in_month; $i++): ?>
                <?php $is_today = ($i == (int)date('j') && $view_month == (int)date('m') && $view_year == (int)date('Y')); ?>
                <div class="gantt-cell <?= $is_today ? 'today-highlight' : '' ?>"></div>
            <?php endfor; ?>

            <?php 
            foreach($reservations as $r) {
                if($r['car_id'] == $car['id']) {
                    $start_ts = strtotime($r['date_debut']);
                    $end_ts = strtotime($r['date_fin']);
                    $month_start_ts = strtotime("$view_year-$view_month-01");
                    $month_end_ts = strtotime("$view_year-$view_month-$days_in_month 23:59:59");
                    
                    if($end_ts < $month_start_ts || $start_ts > $month_end_ts) continue;
                    
                    $v_start = max(1, (int)date('j', max($start_ts, $month_start_ts)));
                    $v_end = min($days_in_month, (int)date('j', min($end_ts, $month_end_ts)));
                    $duration = $v_end - $v_start + 1;
                    
                    if ($duration <= 0) continue;

                    $left_px = 280 + (($v_start - 1) * 50);
                    $width_px = ($duration * 50) - 10;
                    
                    $bg = match($r['status_reservation']) {
                        'en_attente' => 'background: linear-gradient(135deg, #f1c40f, #f39c12); color: #000;',
                        'validee' => 'background: linear-gradient(135deg, #2ecc71, #27ae60);',
                        'en_cours' => 'background: linear-gradient(135deg, #3498db, #2980b9);',
                        default => 'background: #95a5a6;'
                    };
                    
                    $tooltip = "<b>".htmlspecialchars($r['prenom']." ".$r['nom'])."</b><br>";
                    $tooltip .= date('d/m', $start_ts)." au ".date('d/m', $end_ts)."<br>";
                    $tooltip .= "<span class='badge bg-white text-dark'>".ucfirst($r['status_reservation'])."</span>";
                    ?>
                    <div class="gantt-bar" 
                         style="left: <?= $left_px ?>px; width: <?= $width_px ?>px; <?= $bg ?>" 
                         data-bs-toggle="tooltip" 
                         data-bs-html="true"
                         title="<?= $tooltip ?>">
                        <?= htmlspecialchars($r['prenom']) ?>
                    </div>
                    <?php
                }
            }
            ?>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="card border-0 shadow-sm rounded-4 p-4">
    <div class="d-flex align-items-center justify-content-between">
        <h6 class="fw-bold mb-0">Légende des statuts</h6>
        <div class="d-flex gap-4">
            <div class="d-flex align-items-center"><span class="rounded-circle me-2" style="width:12px; height:12px; background:linear-gradient(135deg, #f1c40f, #f39c12);"></span> <small class="text-muted">En attente</small></div>
            <div class="d-flex align-items-center"><span class="rounded-circle me-2" style="width:12px; height:12px; background:linear-gradient(135deg, #2ecc71, #27ae60);"></span> <small class="text-muted">Option Validée</small></div>
            <div class="d-flex align-items-center"><span class="rounded-circle me-2" style="width:12px; height:12px; background:linear-gradient(135deg, #3498db, #2980b9);"></span> <small class="text-muted">En location</small></div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl)
    });
});
</script>

<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>