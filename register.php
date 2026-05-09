<?php session_start(); ?>
<!DOCTYPE html>
<html>
<head><title>Inscription</title></head>
<body>
    <h1>Inscription</h1>
    <?php if (isset($_SESSION['error'])): ?>
        <p style="color:red;"><?= $_SESSION['error']; unset($_SESSION['error']); ?></p>
    <?php endif; ?>
    <form action="actions/register.php" method="POST">
        <label>Nom: <input type="text" name="nom" required></label><br><br>
        <label>Prénom: <input type="text" name="prenom" required></label><br><br>
        <label>Email: <input type="email" name="email" required></label><br><br>
        <label>Téléphone: <input type="text" name="telephone"></label><br><br>
        <label>Mot de passe: <input type="password" name="password" required></label><br><br>
        <label>Adresse: <textarea name="adresse"></textarea></label><br><br>
        <button type="submit">S'inscrire</button>
    </form>
    <a href="login.php">Déjà un compte ? Connectez-vous</a>
</body>
</html>