<?php
require_once 'app/config.php';
$pdo = get_pdo();

try {
    $pdo->exec("ALTER TABLE reservations ADD COLUMN validated_by INT NULL;");
    $pdo->exec("ALTER TABLE reservations ADD CONSTRAINT fk_validated_by FOREIGN KEY (validated_by) REFERENCES users(id) ON DELETE SET NULL;");
    echo "Migration réussie : Colonne 'validated_by' ajoutée.";
} catch (Exception $e) {
    echo "Erreur ou déjà existant : " . $e->getMessage();
}
