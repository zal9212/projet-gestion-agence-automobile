<?php
require_once 'app/config.php';
$pdo = get_pdo();
$users = $pdo->query("SELECT id, email, role, prenom, nom FROM users ORDER BY id DESC LIMIT 10")->fetchAll();
foreach($users as $u) {
    echo $u['id']." | ".$u['prenom']." ".$u['nom']." (".$u['email'].") : ".$u['role']."\n";
}
?>
