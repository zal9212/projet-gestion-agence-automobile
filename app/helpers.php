<?php
/**
 * FONCTIONS D'AIDE (HELPERS)
 */

/**
 * Envoie une notification à un utilisateur
 */
function send_notification($user_id, $message, $type = 'info') {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("INSERT INTO notifications (user_id, message, type) VALUES (?, ?, ?)");
    $stmt->execute([$user_id, $message, $type]);
}

/**
 * Récupère les notifications non lues d'un utilisateur
 */
function get_unread_notifications($user_id) {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("SELECT * FROM notifications WHERE user_id = ? AND is_read = FALSE ORDER BY created_at DESC");
    $stmt->execute([$user_id]);
    return $stmt->fetchAll();
}

/**
 * Compte les notifications non lues
 */
function count_unread_notifications($user_id) {
    $pdo = get_pdo();
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM notifications WHERE user_id = ? AND is_read = FALSE");
    $stmt->execute([$user_id]);
    return (int)$stmt->fetchColumn();
}
