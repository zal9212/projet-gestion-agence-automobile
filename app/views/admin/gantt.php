<?php 
ob_start(); 
$days_in_month = date('t');
$month_name = date('F Y');
?>
<div class="d-flex justify-content-between align-items-center mb-5">
    <div>
        <h2 class="fw-bold mb-1">Interactive Planning (Gantt)</h2>
        <p class="text-muted">Global view of fleet occupation for <?= $month_name ?>.</p>
    </div>
    <div class="d-flex gap-2">
        <button class="btn btn-outline-dark rounded-pill px-3"><i class="fa-solid fa-chevron-left"></i></button>
        <button class="btn btn-outline-dark rounded-pill px-3"><i class="fa-solid fa-chevron-right"></i></button>
    </div>
</div>

<style>
    .gantt-container { overflow-x: auto; background: white; border-radius: 20px; box-shadow: 0 4px 20px rgba(0,0,0,0.02); border: 1px solid #f0f0f0; }
    .gantt-header { display: flex; border-bottom: 2px solid #eee; background: #fdfdfd; }
    .gantt-car-col { min-width: 250px; padding: 15px; font-weight: bold; border-right: 1px solid #eee; position: sticky; left: 0; background: white; z-index: 10; }
    .gantt-day { flex: 1; min-width: 45px; text-align: center; padding: 15px 0; border-right: 1px solid #f9f9f9; font-size: 0.85rem; font-weight: 500; color: #666; }
    .gantt-row { display: flex; border-bottom: 1px solid #f5f5f5; position: relative; }
    .gantt-cell { flex: 1; min-width: 45px; border-right: 1px solid #f9f9f9; height: 60px; }
    .gantt-bar { 
        position: absolute; height: 40px; top: 10px; border-radius: 8px; 
        display: flex; align-items: center; justify-content: center; 
        color: white; font-size: 0.75rem; font-weight: bold; padding: 0 10px;
        white-space: nowrap; overflow: hidden; text-overflow: ellipsis; cursor: pointer;
        box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    }
</style>

<div class="gantt-container pb-2 mb-5">
    <div class="gantt-header">
        <div class="gantt-car-col text-muted text-uppercase small">Vehicle</div>
        <?php for($i=1; $i<=$days_in_month; $i++): ?>
            <div class="gantt-day <?= ($i==date('j')) ? 'bg-warning text-dark rounded-bottom' : '' ?>">
                <?= str_pad($i, 2, '0', STR_PAD_LEFT) ?>
            </div>
        <?php endfor; ?>
    </div>

    <?php foreach($cars as $car): ?>
    <div class="gantt-row">
        <!-- Colonne Voiture (Fixe) -->
        <div class="gantt-car-col d-flex flex-column justify-content-center">
            <span class="fw-bold text-dark mb-1" style="line-height: 1;"><?= htmlspecialchars($car['immatriculation']) ?></span>
            <small class="text-muted" style="font-size: 0.75rem;"><?= htmlspecialchars($car['marque'].' '.$car['modele']) ?></small>
        </div>
        
        <!-- Cellules Vides (Jours) -->
        <?php for($i=1; $i<=$days_in_month; $i++): ?>
            <div class="gantt-cell <?= ($i==date('j')) ? 'bg-warning bg-opacity-10' : '' ?>"></div>
        <?php endfor; ?>

        <!-- Barres de Réservation -->
        <?php 
        foreach($reservations as $r) {
            if($r['car_id'] == $car['id']) {
                $start_date = strtotime($r['date_debut']);
                $end_date = strtotime($r['date_fin']);
                $current_month_start = strtotime(date('Y-m-01'));
                $current_month_end = strtotime(date('Y-m-t'));
                
                // Ignorer si ça ne touche pas ce mois-ci du tout
                if($end_date < $current_month_start || $start_date > $current_month_end) continue;
                
                // Calculer les bornes visibles dans le mois courant
                $visible_start_day = max(1, (int)date('j', $start_date));
                if ($start_date < $current_month_start) $visible_start_day = 1;
                
                $visible_end_day = min($days_in_month, (int)date('j', $end_date));
                if ($end_date > $current_month_end) $visible_end_day = $days_in_month;
                
                $duration = $visible_end_day - $visible_start_day + 1;
                if ($duration <= 0) continue;

                // 250px est la largeur de la col voiture. 45px est la min-width d'une cellule.
                // Note: En responsive CSS Flex, la largeur exacte dépend de l'écran. 
                // Pour un vrai Gantt robuste, on utilise des pourcentages.
                $left_percent = (($visible_start_day - 1) / $days_in_month) * 100;
                $width_percent = ($duration / $days_in_month) * 100;
                
                $bg_color = match($r['status_reservation']) {
                    'en_attente' => 'background: linear-gradient(45deg, #f4c053, #f5d17a); color: #000;',
                    'validee' => 'background: linear-gradient(45deg, #2ecc71, #27ae60);',
                    'en_cours' => 'background: linear-gradient(45deg, #3498db, #2980b9);',
                    default => 'background: #95a5a6;'
                };
                ?>
                <!-- Utilisation de calc() pour positionner correctement après la colonne fixe -->
                <div class="gantt-bar" style="left: calc(250px + <?= $left_percent ?>% - (<?= $left_percent ?>% * 250px / 100vw)); width: calc(<?= $width_percent ?>% - (<?= $width_percent ?>% * 250px / 100vw)); <?= $bg_color ?>" title="<?= htmlspecialchars($r['nom']) ?> (<?= ucfirst($r['status_reservation']) ?>)">
                    <?= htmlspecialchars($r['nom']) ?>
                </div>
                <?php
            }
        }
        ?>
    </div>
    <?php endforeach; ?>
</div>

<!-- LÉGENDE -->
<div class="d-flex gap-4">
    <div class="d-flex align-items-center"><span style="width:15px; height:15px; background:#f4c053; border-radius:3px; margin-right:8px;"></span> <small>En attente</small></div>
    <div class="d-flex align-items-center"><span style="width:15px; height:15px; background:#2ecc71; border-radius:3px; margin-right:8px;"></span> <small>Option Bloquée / Validée</small></div>
    <div class="d-flex align-items-center"><span style="width:15px; height:15px; background:#3498db; border-radius:3px; margin-right:8px;"></span> <small>En cours (Check-out fait)</small></div>
    <div class="d-flex align-items-center"><span style="width:15px; height:15px; background:#95a5a6; border-radius:3px; margin-right:8px;"></span> <small>Annulée</small></div>
</div>
<?php $content = ob_get_clean(); require 'app/views/layouts/admin.php'; ?>