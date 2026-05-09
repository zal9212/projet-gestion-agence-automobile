<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head><title>Connexion</title></head>
<body>
    <h1>Connexion</h1>
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color:red;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <form action="actions/login.php" method="POST">
        <label>Email: <input type="email" name="email" required></label><br><br>
        <label>Mot de passe: <input type="password" name="password" required></label><br><br>
        <button type="submit">Se connecter</button>
    </form>
    <a href="register.php">Pas encore de compte ? S'inscrire</a>
</body>
</html>