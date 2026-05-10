<?php
require_once 'app/config.php';
$pdo = get_pdo();
$stmt = $pdo->query("DESCRIBE reservations");
echo "Colonnes dans 'reservations' :\n";
foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $col) {
    echo "- $col\n";
}
