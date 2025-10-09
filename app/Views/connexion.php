<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion</title>
</head>
<body>
    <div class="login-container">
        <h2>Connexion</h2>
        <form action="/connexion" method="post">
            <label for="login">Login</label>
            <input type="text" id="login" name="login" required>

            <label for="mdp">Mot de passe</label>
            <input type="password" id="mdp" name="mdp" required>

            <input type="submit" value="Se connecter">
        </form>
    </div>
</body>
</html>