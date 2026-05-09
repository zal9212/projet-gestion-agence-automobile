<?php
require_once 'app/config.php';
$pdo = getPDO();

try {
    $pdo->exec("ALTER TABLE cars ADD COLUMN caution DECIMAL(10,2) DEFAULT 1000.00;");
    echo "Colonne 'caution' ajoutée avec succès à la table 'cars'.";
} catch(PDOException $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "La colonne 'caution' existe déjà.";
    } else {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
