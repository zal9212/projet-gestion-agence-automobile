<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Facture #<?= $reservation['id'] ?> - Teranga Auto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', sans-serif; }
        .invoice-card { background: white; border-radius: 0; box-shadow: 0 0 20px rgba(0,0,0,0.05); padding: 50px; max-width: 900px; margin: 30px auto; position: relative; border-top: 10px solid #f4c053; }
        .logo-area img { height: 60px; }
        .invoice-title { font-size: 2.5rem; font-weight: 900; color: #1a1a1a; letter-spacing: -1px; }
        .invoice-meta { color: #6c757d; }
        .table thead th { background-color: #1a1a1a; color: white; border: none; padding: 15px; }
        .table tbody td { padding: 15px; vertical-align: middle; border-bottom: 1px solid #eee; }
        .total-box { background-color: #f8f9fa; border-radius: 15px; padding: 25px; }
        .stamp-container { position: absolute; bottom: 100px; right: 50px; transform: rotate(-15deg); opacity: 0.15; pointer-events: none; }
        .stamp { border: 4px double #1a1a1a; border-radius: 50%; width: 150px; height: 150px; display: flex; flex-direction: column; align-items: center; justify-content: center; text-align: center; font-weight: bold; font-size: 0.8rem; line-height: 1.2; text-transform: uppercase; }
        .btn-print-nav { position: fixed; top: 20px; right: 20px; z-index: 1000; }
        
        @media print {
            .btn-print-nav, .alert { display: none !important; }
            body { background: white; margin: 0; }
            .invoice-card { box-shadow: none; margin: 0; max-width: 100%; border-top: none; }
            .stamp-container { opacity: 0.8; }
        }
    </style>
</head>
<body>

<div class="btn-print-nav d-flex gap-2">
    <a href="index.php?action=admin_reservation_detail&id=<?= $reservation['id'] ?>" class="btn btn-light rounded-pill shadow-sm px-4">
        <i class="fa-solid fa-arrow-left me-2"></i> Retour
    </a>
    <button onclick="window.print()" class="btn btn-dark rounded-pill shadow-sm px-4">
        <i class="fa-solid fa-print me-2"></i> Imprimer la Facture
    </button>
</div>

<div class="invoice-card">
    <!-- Cachet Teranga Auto -->
    <div class="stamp-container">
        <div class="stamp">
            <span>Teranga Auto</span><br>
            <i class="fa-solid fa-check-circle fa-2x my-1"></i><br>
            <span>PAYÉ</span><br>
            <small>Dakar, SN</small>
        </div>
    </div>

    <div class="row mb-5">
        <div class="col-6">
            <div class="logo-area mb-4">
                <img src="logo.png" alt="Teranga Auto">
            </div>
            <h6 class="fw-bold mb-1">Teranga Auto ERP Services</h6>
            <p class="text-muted small mb-0">Avenue Cheikh Anta Diop, Dakar</p>
            <p class="text-muted small mb-0">+221 77 000 00 00 | contact@teranga-auto.sn</p>
            <p class="text-muted small">NINEA: 006543212 | RCCM: SN-DKR-2026-B-123</p>
        </div>
        <div class="col-6 text-end">
            <h1 class="invoice-title mb-1">FACTURE</h1>
            <p class="invoice-meta mb-0">Référence : #INV-<?= date('Y', strtotime($reservation['date_creation'])) ?>-<?= str_pad($reservation['id'], 5, '0', STR_PAD_LEFT) ?></p>
            <p class="invoice-meta">Date d'émission : <?= date('d/m/Y') ?></p>
            
            <div class="mt-4">
                <h6 class="fw-bold mb-1">FACTURÉ À :</h6>
                <p class="mb-0 fw-bold"><?= htmlspecialchars($reservation['prenom'].' '.$reservation['nom']) ?></p>
                <p class="text-muted small mb-0"><?= htmlspecialchars($reservation['client_adresse'] ?? 'Dakar, Sénégal') ?></p>
                <p class="text-muted small mb-0"><?= htmlspecialchars($reservation['telephone']) ?></p>
            </div>
        </div>
    </div>

    <div class="table-responsive mb-5">
        <table class="table">
            <thead>
                <tr>
                    <th>Description de la prestation</th>
                    <th class="text-center">Qté (Jours)</th>
                    <th class="text-end">Prix Unitaire</th>
                    <th class="text-end">Total HT</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $d1 = new DateTime($reservation['date_debut']);
                $d2 = new DateTime($reservation['date_fin']);
                $diff = $d1->diff($d2);
                $days = $diff->days ?: 1;
                $pu_ht = ($reservation['prix_total'] / 1.18) / $days; // Simulation HT si TVA 18%
                ?>
                <tr>
                    <td>
                        <h6 class="fw-bold mb-0">Location Véhicule : <?= htmlspecialchars($reservation['marque'].' '.$reservation['modele']) ?></h6>
                        <small class="text-muted">Immatriculation : <?= htmlspecialchars($reservation['immatriculation']) ?></small><br>
                        <small class="text-muted">Période : <?= date('d/m/Y', strtotime($reservation['date_debut'])) ?> au <?= date('d/m/Y', strtotime($reservation['date_fin'])) ?></small>
                    </td>
                    <td class="text-center"><?= $days ?></td>
                    <td class="text-end"><?= number_format($pu_ht, 0, ',', ' ') ?> F</td>
                    <td class="text-end"><?= number_format($pu_ht * $days, 0, ',', ' ') ?> F</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="row justify-content-end">
        <div class="col-md-5">
            <div class="total-box">
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">Sous-total HT</span>
                    <span class="fw-bold"><?= number_format($reservation['prix_total'] / 1.18, 0, ',', ' ') ?> FCFA</span>
                </div>
                <div class="d-flex justify-content-between mb-2">
                    <span class="text-muted">TVA (18%)</span>
                    <span class="fw-bold"><?= number_format($reservation['prix_total'] - ($reservation['prix_total'] / 1.18), 0, ',', ' ') ?> FCFA</span>
                </div>
                <hr>
                <div class="d-flex justify-content-between">
                    <h5 class="fw-bold mb-0">TOTAL TTC</h5>
                    <h5 class="fw-bold text-success mb-0"><?= number_format($reservation['prix_total'], 0, ',', ' ') ?> FCFA</h5>
                </div>
            </div>
            <p class="x-small text-muted text-end mt-2">Arrêté la présente facture à la somme de : <br><strong class="text-dark"><?= number_format($reservation['prix_total'], 0, ',', ' ') ?> Francs CFA TTC</strong></p>
        </div>
    </div>

    <div class="mt-5 pt-5">
        <div class="row">
            <div class="col-6 text-center">
                <p class="small text-muted mb-5">Signature Client</p>
                <div style="height: 60px;"></div>
                <hr class="mx-auto w-50">
            </div>
            <div class="col-6 text-center">
                <p class="small text-muted mb-5">Cachet & Signature Agence</p>
                <div style="height: 60px;"></div>
                <hr class="mx-auto w-50">
                <p class="small fw-bold">Direction Teranga Auto</p>
            </div>
        </div>
    </div>

    <footer class="mt-5 pt-5 text-center text-muted x-small border-top">
        <p>Merci de votre confiance. Teranga Auto - L'excellence au service de votre mobilité.</p>
    </footer>
</div>

</body>
</html>
