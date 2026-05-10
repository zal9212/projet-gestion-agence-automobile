<?php
require_once 'app/config.php';
$pdo = get_pdo();
$stmt = $pdo->query("SHOW COLUMNS FROM cars");
foreach($stmt as $row) {
    echo $row['Field']." | Null: ".$row['Null']." | Default: ".$row['Default']."\n";
}
?>
