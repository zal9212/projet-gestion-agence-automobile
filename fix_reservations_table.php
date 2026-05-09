<?php
require_once 'app/config.php';
$pdo = getPDO();

try {
    $pdo->exec("ALTER TABLE reservations ADD COLUMN avec_chauffeur BOOLEAN DEFAULT FALSE;");
} catch(PDOException $e) {}

try {
    $pdo->exec("ALTER TABLE reservations ADD COLUMN lieu_prise_id INT NULL;");
} catch(PDOException $e) {}

try {
    $pdo->exec("ALTER TABLE reservations ADD COLUMN lieu_retour_id INT NULL;");
} catch(PDOException $e) {}

echo "Colonnes ajoutées à la table reservations.";
?>
