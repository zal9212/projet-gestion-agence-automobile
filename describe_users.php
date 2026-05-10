<?php
require_once 'app/config.php';
$pdo = get_pdo();
$stmt = $pdo->query("DESCRIBE users");
foreach($stmt as $row) {
    echo $row['Field']." | ".$row['Type']."\n";
}
?>
