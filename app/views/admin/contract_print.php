<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contrat de Location #<?= $reservation['id'] ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Outfit', sans-serif; background: #f0f0f0; color: #1a1a1a; padding: 40px 0; margin: 0; font-size: 13px; }
        .contract-wrapper { width: 850px; background: #fff; padding: 50px; margin: 0 auto; position: relative; box-shadow: 0 10px 30px rgba(0,0,0,0.1); border-radius: 8px; }
        .contract-header { border-bottom: 2px solid #f4c053; padding-bottom: 20px; margin-bottom: 30px; }
        .section-title { background: #1a1a1a; color: #f4c053; padding: 8px 15px; font-weight: 700; margin-top: 25px; margin-bottom: 15px; text-transform: uppercase; letter-spacing: 1px; border-radius: 6px; font-size: 0.85rem; }
        .table { border-color: #eee; margin-bottom: 15px; }
        .table th, .table td { padding: 12px 15px; border-bottom: 1px solid #f8f8f8; }
        .table th { background-color: #fcfcfc; color: #888; font-weight: 600; font-size: 0.7rem; text-transform: uppercase; border: none; }
        .signature-box { border: 2px dashed #eee; height: 160px; text-align: center; margin-top: 15px; border-radius: 16px; background: #fafafa; overflow: hidden; padding: 15px; display: flex; flex-direction: column; align-items: center; justify-content: center; }
        .signature-img { max-height: 130px; max-width: 100%; object-fit: contain; }
        .brand-accent { color: #f4c053; }
        .watermark { position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%) rotate(-30deg); opacity: 0.04; font-size: 110px; font-weight: 900; pointer-events: none; z-index: -1; white-space: nowrap; color: #000; }
        @media print {
            body { background: #fff; padding: 0; }
            .contract-wrapper { box-shadow: none; border: none; width: 100%; padding: 20px; }
            .no-print { display: none !important; }
        }
    </style>
</head>
<body>
    <div class="contract-wrapper">
        <div class="watermark">TERANGA AUTO</div>
    <div class="text-center mt-3 mb-4 no-print d-flex justify-content-center flex-wrap gap-2">
        <a href="index.php?action=admin_dashboard" class="btn btn-outline-dark btn-lg rounded-pill px-4 fw-bold shadow-sm">
            <i class="fa-solid fa-arrow-left me-2"></i> Retour
        </a>
        <button onclick="window.print()" class="btn btn-dark btn-lg rounded-pill px-5 fw-bold shadow">
            <i class="fa-solid fa-print me-2"></i> Imprimer le Contrat
        </button>
        <?php 
            $currentUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";
            $waMessage = "Bonjour, voici votre contrat de location Teranga Auto : " . $currentUrl;
            $waLink = "https://wa.me/?text=" . urlencode($waMessage);
        ?>
        <a href="<?= $waLink ?>" target="_blank" class="btn btn-success btn-lg rounded-pill px-4 fw-bold shadow-sm" style="background-color: #25D366; border-color: #25D366;">
            <i class="fa-brands fa-whatsapp me-2"></i> Partager
        </a>
    </div>
    
    <div class="contract-header d-flex justify-content-between align-items-center">
        <div>
            <img src="logo.png" alt="Teranga Auto Logo" style="height: 70px;" class="mb-1">
            <p class="mb-0 text-muted" style="font-size: 0.75rem;">Avenue Cheikh Anta Diop, Dakar<br>contact@teranga-auto.sn | +221 33 800 00 00</p>
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
    <p class="small text-muted">Le locataire reconnaît avoir reçu le véhicule dans l'état décrit ci-dessus et accepte les conditions générales de location de Teranga Auto. Le véhicule doit être restitué à la date et l'heure convenues. Toute infraction au code de la route ou dommage survenu pendant la période de location relève de la seule responsabilité du locataire.</p>

    <div class="row mt-5">
        <div class="col-6">
            <p class="fw-bold text-center">Représentant Teranga Auto</p>
            <div class="signature-box d-flex flex-column align-items-center justify-content-center">
                <?php 
                // URL de vérification d'authenticité (Teranga Auto Digital Certificate)
                $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http");
                $host = $_SERVER['HTTP_HOST'];
                // On récupère le chemin du script actuel et on enlève le nom du fichier pour avoir le dossier de base
                $scriptPath = $_SERVER['SCRIPT_NAME'];
                $basePath = str_replace('\\', '/', dirname($scriptPath));
                if ($basePath === '/') $basePath = '';
                
                $verifyUrl = "$protocol://$host$basePath/index.php?action=verify_contract&id=" . $reservation['id'];
                
                $qrUrl = "https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=" . urlencode($verifyUrl);
                ?>
                <img src="<?= $qrUrl ?>" alt="Vérification Authenticité" style="width: 100px; height: 100px;" class="mb-1">
                <div class="small fw-bold text-uppercase"><?= htmlspecialchars(($reservation['agent_prenom'] ?? '') . ' ' . ($reservation['agent_nom'] ?? 'Agence Teranga Auto')) ?></div>
            </div>
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
    </div> <!-- Fin contract-wrapper -->

    <script>
        // Utilitaire pour l'impression automatique si besoin
    </script>
</body>
</html>