<?php
require_once 'app/config.php';
$pdo = get_pdo();
$users = $pdo->query("SELECT email, prenom, nom, role FROM users")->fetchAll();
foreach($users as $u) {
    echo $u['prenom']." ".$u['nom']." (".$u['email'].") : ".$u['role']."\n";
}
?>
