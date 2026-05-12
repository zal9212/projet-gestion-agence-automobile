<?php
/**
 * FONCTIONS D'AIDE (HELPERS)
 */

/**
 * Shorthand pour htmlspecialchars() - Protection XSS universelle
 */
function h($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES | ENT_HTML5, 'UTF-8');
}

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

/**
 * --- SÉCURITÉ CSRF ---
 */

/**
 * Génère un token CSRF et le stocke en session
 */
function generate_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Affiche un champ caché contenant le token CSRF pour les formulaires
 */
function csrf_field() {
    $token = generate_csrf_token();
    return '<input type="hidden" name="csrf_token" value="' . $token . '">';
}

/**
 * Vérifie le token CSRF reçu (POST ou GET)
 */
function verify_csrf_token($method = 'POST') {
    $token = ($method === 'POST') ? ($_POST['csrf_token'] ?? '') : ($_GET['csrf_token'] ?? '');
    if (!isset($_SESSION['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $token)) {
        $_SESSION['error'] = "Erreur de sécurité (CSRF). Veuillez réessayer.";
        redirect($_SERVER['HTTP_REFERER'] ?? 'index.php');
        exit;
    }
}



