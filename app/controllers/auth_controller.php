<?php
/**
 * CONTROLEUR AUTH
 */

function auth_login() {
    if (isset($_SESSION['user_id'])) redirect('index.php');
    render_view('front/login');
}

function auth_do_login() {
    verify_csrf_token('POST');
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
    $_SESSION['user_photo'] = $user['photo_profil'];

    redirect($user['role'] === 'admin' ? 'index.php?action=admin_dashboard' : 'index.php');
}

function auth_register() {
    if (isset($_SESSION['user_id'])) redirect('index.php');
    render_view('front/register');
}

function auth_do_register() {
    verify_csrf_token('POST');
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
    verify_csrf_token('POST');
    $pdo = get_pdo();
    $id = $_SESSION['user_id'];
    
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    
   //verifie si le mail existe
    $check_email = $pdo->prepare("SELECT id FROM users WHERE email = ? AND id != ?");
    $check_email->execute([$email, $id]);
    if ($check_email->fetch()) {
        $_SESSION['error'] = "Cette adresse email est déjà utilisée par un autre compte.";
        redirect($_SERVER['HTTP_REFERER']);
    }

    // Gestion de la photo de profil
    if (isset($_FILES['photo_profil']) && $_FILES['photo_profil']['error'] == UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/profiles/';
        if (!is_dir($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                $_SESSION['error'] = "Erreur système : Impossible de créer le dossier de destination.";
                redirect($_SERVER['HTTP_REFERER']);
            }
        }
        
        if (!is_writable($uploadDir)) {
            $_SESSION['error'] = "Erreur système : Le dossier de destination n'est pas accessible en écriture.";
            redirect($_SERVER['HTTP_REFERER']);
        }

        $extension = strtolower(pathinfo($_FILES['photo_profil']['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, ['jpg', 'jpeg', 'png', 'webp'])) {
            $_SESSION['error'] = "Format d'image non supporté (JPG, PNG, WEBP uniquement).";
            redirect($_SERVER['HTTP_REFERER']);
        }

        $fileName = 'profile_' . $id . '_' . time() . '.' . $extension;
        $photoPath = $uploadDir . $fileName;

        if (move_uploaded_file($_FILES['photo_profil']['tmp_name'], $photoPath)) {
            // Supprimer l'ancienne photo physique
            if (!empty($_SESSION['user_photo']) && file_exists($_SESSION['user_photo'])) {
                @unlink($_SESSION['user_photo']);
            }
            
            $stmt = $pdo->prepare("UPDATE users SET photo_profil = ? WHERE id = ?");
            $stmt->execute([$photoPath, $id]);
            $_SESSION['user_photo'] = $photoPath;
        } else {
            $_SESSION['error'] = "Erreur lors du transfert de la photo.";
            redirect($_SERVER['HTTP_REFERER']);
        }
    }

    // Mise à jour de base
    $stmt = $pdo->prepare("UPDATE users SET nom = ?, prenom = ?, email = ?, telephone = ? WHERE id = ?");
    $stmt->execute([$nom, $prenom, $email, $telephone, $id]);
    
    // Si mot de passe fourni
    if (!empty($_POST['password'])) {
        $stmt = $pdo->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->execute([password_hash($_POST['password'], PASSWORD_BCRYPT), $id]);
    }
    
    $_SESSION['user_nom'] = $nom;
    $_SESSION['user_prenom'] = $prenom;
    $_SESSION['success'] = "Profil mis à jour avec succès.";
    
    redirect(in_array($_SESSION['user_role'], ['admin', 'employee']) ? 'index.php?action=admin_profile' : 'index.php?action=profile');
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
