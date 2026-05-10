<?php
/**
 * CONTROLEUR AUTH
 */

function auth_login() {
    if (isset($_SESSION['user_id'])) redirect('index.php');
    render_view('front/login');
}

function auth_do_login() {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    $pdo = get_pdo();
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();

    if (!$user || !password_verify($password, $user['password'])) {
        $_SESSION['error'] = "Email ou mot de passe incorrect.";
        redirect('index.php?action=login');
    }

    $_SESSION['user_id'] = $user['id'];
    $_SESSION['user_nom'] = $user['nom'];
    $_SESSION['user_prenom'] = $user['prenom'];
    $_SESSION['user_role'] = $user['role'];

    redirect($user['role'] === 'admin' ? 'index.php?action=admin_dashboard' : 'index.php');
}

function auth_register() {
    if (isset($_SESSION['user_id'])) redirect('index.php');
    render_view('front/register');
}

function auth_do_register() {
    $pdo = get_pdo();
    $email = trim($_POST['email'] ?? '');
    
    $check = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $check->execute([$email]);
    if ($check->fetch()) {
        $_SESSION['error'] = "Cet email est déjà utilisé.";
        redirect('index.php?action=register');
    }

    $stmt = $pdo->prepare("INSERT INTO users (nom, prenom, email, telephone, password, role) VALUES (?, ?, ?, ?, ?, 'client')");
    $stmt->execute([
        $_POST['nom'],
        $_POST['prenom'],
        $email,
        $_POST['telephone'] ?? null,
        password_hash($_POST['password'], PASSWORD_BCRYPT)
    ]);

    $_SESSION['user_id'] = $pdo->lastInsertId();
    $_SESSION['user_prenom'] = $_POST['prenom'];
    $_SESSION['user_role'] = 'client';

    $_SESSION['success'] = "Bienvenue ! Votre compte a été créé.";
    redirect('index.php');
}

function auth_profile_save() {
    if (!isset($_SESSION['user_id'])) redirect('index.php?action=login');
    $pdo = get_pdo();
    $id = $_SESSION['user_id'];
    
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    
    // Mise à jour de base
    $stmt = $pdo->prepare("UPDATE users SET nom = ?, prenom = ?, email = ?, telephone = ? WHERE id = ?");
    $stmt->execute([$nom, $prenom, $email, $telephone, $id]);
    
    // Si mot de passe fourni
    if (!empty($_POST['password'])) {
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([password_hash($_POST['password'], PASSWORD_BCRYPT), $id]);
    }
    
    $_SESSION['user_prenom'] = $prenom; // Mettre à jour la session
    $_SESSION['success'] = "Profil mis à jour avec succès.";
    
    redirect($_SESSION['user_role'] === 'admin' ? 'index.php?action=admin_profile' : 'index.php?action=profile');
}

function auth_notif_read() {
    if (!isset($_SESSION['user_id'])) die("Accès refusé");
    $pdo = get_pdo();
    $id = (int)$_GET['id'];
    $pdo->prepare("UPDATE notifications SET is_read = TRUE WHERE id = ? AND user_id = ?")->execute([$id, $_SESSION['user_id']]);
    
    // On retourne d'où on vient
    header('Location: ' . $_SERVER['HTTP_REFERER']);
}

function auth_logout() {
    session_destroy();
    redirect('index.php?action=login');
}
