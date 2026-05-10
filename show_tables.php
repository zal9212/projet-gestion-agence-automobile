<?php
require_once 'app/config.php';
$pdo = get_pdo();
$stmt = $pdo->query("SHOW TABLES");
foreach($stmt as $row) {
    echo $row[0]."\n";
}
?>
