<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contrat de Location #<?= $reservation['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background: #fff; color: #000; padding: 40px; }
        .contract-header { border-bottom: 2px solid #000; padding-bottom: 20px; margin-bottom: 30px; }
        .section-title { background: #f0f0f0; padding: 5px 10px; font-weight: bold; margin-top: 30px; margin-bottom: 15px; border-left: 4px solid #000; }
        table th { background-color: #f9f9f9; }
        .signature-box { border: 1px solid #ccc; height: 150px; text-align: center; margin-top: 20px; }
        .signature-img { max-height: 140px; max-width: 100%; object-fit: contain; }
        @media print {
            body { padding: 0; }
            button { display: none !important; }
        }
    </style>
</head>
<body>
    <button onclick="window.print()" class="btn btn-dark float-end mb-4"><i class="fa-solid fa-print"></i> Imprimer le Contrat</button>
    
    <div class="contract-header d-flex justify-content-between align-items-center">
        <div>
            <h2 class="fw-bold mb-0">AutoRent Agency</h2>
            <p class="mb-0 text-muted">15 Avenue Cheikh Anta Diop, Plateau<br>contact@autorent.sn | +221 33 800 00 00</p>
        </div>
        <div class="text-end">
            <h3 class="mb-1">CONTRAT DE LOCATION</h3>
            <p class="mb-0 fw-bold">Numéro : AR-<?= date('Y') ?>-<?= str_pad($reservation['id'], 5, "0", STR_PAD_LEFT) ?></p>
            <p class="mb-0">Date : <?= date('d/m/Y') ?></p>
        </div>
    </div>

    <div class="row">
        <div class="col-6">
            <div class="section-title">INFORMATIONS DU LOCATAIRE</div>
            <p><strong>Nom :</strong> <?= htmlspecialchars($reservation['nom'].' '.$reservation['prenom']) ?><br>
            <strong>Email :</strong> <?= htmlspecialchars($reservation['email']) ?><br>
            <strong>Téléphone :</strong> <?= htmlspecialchars($reservation['telephone'] ?? 'N/A') ?></p>
        </div>
        <div class="col-6">
            <div class="section-title">INFORMATIONS DU VÉHICULE</div>
            <p><strong>Marque & Modèle :</strong> <?= htmlspecialchars($reservation['marque'].' '.$reservation['modele']) ?><br>
            <strong>Immatriculation :</strong> <?= htmlspecialchars($reservation['immatriculation']) ?><br>
            <strong>N° Châssis (VIN) :</strong> <?= htmlspecialchars($car['vin'] ?? 'N/A') ?></p>
        </div>
    </div>

    <div class="section-title">PÉRIODE DE LOCATION ET ÉTAT</div>
    <table class="table table-bordered">
        <tr>
            <th width="25%">Date de Départ</th><td><?= date('d/m/Y', strtotime($reservation['date_debut'])) ?></td>
            <th width="25%">Date de Retour</th><td><?= date('d/m/Y', strtotime($reservation['date_fin'])) ?></td>
        </tr>
        <tr>
            <th>Kilométrage Départ</th><td><?= $reservation['kilometrage_depart'] ?> km</td>
            <th>Niveau Carburant</th><td><?= htmlspecialchars($reservation['niveau_carburant_depart']) ?></td>
        </tr>
    </table>

    <div class="section-title">RÉSUMÉ FINANCIER</div>
    <table class="table table-bordered">
        <tr><th>Montant Total de la Location</th><td class="text-end fw-bold"><?= number_format($reservation['prix_total'], 0, ',', ' ') ?> FCFA</td></tr>
        <tr><th>Dépôt de Garantie (Autorisé)</th><td class="text-end"><?= number_format($car['caution'], 0, ',', ' ') ?> FCFA</td></tr>
    </table>

    <div class="section-title">CONDITIONS ET SIGNATURES</div>
    <p class="small text-muted">Le locataire reconnaît avoir reçu le véhicule dans l'état décrit ci-dessus et accepte les conditions générales de location d'AutoRent. Le véhicule doit être restitué à la date et l'heure convenues. Toute infraction au code de la route ou dommage survenu pendant la période de location relève de la seule responsabilité du locataire.</p>

    <div class="row mt-5">
        <div class="col-6">
            <p class="fw-bold text-center">Représentant AutoRent</p>
            <div class="signature-box d-flex align-items-center justify-content-center text-muted">Agence AutoRent</div>
        </div>
        <div class="col-6">
            <p class="fw-bold text-center">Signature du Locataire</p>
            <div class="signature-box border-0">
                <?php if($reservation['signature_base64']): ?>
                    <img src="<?= $reservation['signature_base64'] ?>" class="signature-img">
                <?php else: ?>
                    <div class="text-muted mt-5">Non signé</div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>