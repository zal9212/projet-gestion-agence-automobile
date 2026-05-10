<?php
require_once 'app/config.php';
$pdo = get_pdo();
try {
    $id = 1; // Un ID de test
    $stmt = $pdo->prepare("SELECT r.*, u.nom, u.prenom, v.nom as agent_nom
                          FROM reservations r 
                          JOIN users u ON r.user_id = u.id 
                          LEFT JOIN users v ON r.validated_by = v.id
                          LIMIT 1");
    $stmt->execute();
    echo "Succès !";
} catch (Exception $e) {
    echo "Erreur capturée : " . $e->getMessage();
}
