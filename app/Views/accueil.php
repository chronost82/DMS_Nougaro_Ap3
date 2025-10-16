<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Welcome to CodeIgniter 4!</title>
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <link rel="stylesheet" href="css/accueil.css">

    <!-- STYLES -->

    <style {csp-style-nonce}>

    </style>
</head>

<body>

    <div class="form-contact">
        <img src="img/bandeau_lycee.png">
        <H1>Demande de rendez-vous</H1>
        <form action="<?= url_to('admin-ajout-demande') ?>" method="post">
            <div class="inputbox">
                <input type="text" required="required" name="nom">
                <span>Nom</span>
            </div>
            <div class="inputbox">
                <input type="text" required="required" name="prenom">
                <span>Prenom</span>
            </div>
            <div class="inputbox">
                <input type="email" required="required" name="email">
                <span>Email</span>
            </div>
            <div class="inputbox">
                <input type="tel" required="required" name="tel" pattern="^0[1-9]( ?\d{2}){4}$">
                <span>Téléphone</span>
            </div>
            <div class="inputbox">
                <input type="text" required="required" name="marque">
                <span>Marque</span>
            </div>
            <div class="inputbox">
                <input type="text" required="required" name="modele">
                <span>Modèle</span>
            </div>
            <div class="inputbox">
                <input type="submit" value="Envoyer">
            </div>
            <a href="">Vous avez déjà fait un contrôle technique ?</a>
        </form>
    </div>
</body>
</html>