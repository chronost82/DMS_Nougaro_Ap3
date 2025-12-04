<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Atelier MV</title>
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <link rel="stylesheet" href="css/accueil.css">

</head>

<body>

    <div class="form-contact">
        <img src="img/bandeau_lycee.png">
        <H1>Pré contrôle Technique Gratuit</H1>
        <form action="<?= url_to('admin-ajout-demande') ?>" method="post">
            <?=  csrf_field() ?>
            <div class="inputbox">
                <input type="text" required="required" name="nom" id="nom">
                <span id="nomSpan">Nom</span>
            </div>
            <div class="inputbox">
                <input type="text" required="required" name="prenom" id="prenom">
                <span id="prenomSpan">Prenom</span>
            </div>
            <div class="inputbox">
                <input type="text" required="required" name="email" id="email">
                <span class="" id="errorEmail">Email</span>
            </div>
            <div class="inputbox">
                <input type="text" required="required" name="tel" id="tel">
                <span class="" id="errorTel">Téléphone</span>
            </div>
            <div class="inputbox" id="selectMarque">
                <select name="marque" id="marque" required="required">
                    <option value="" disabled selected id="labelMarque">Marque</option>
                    <?php foreach ($marques as $marque): ?>
                        <option value="<?= esc($marque['MARQUE']) ?>"><?= esc($marque['MARQUE']) ?></option>
                    <?php endforeach; ?>
                    <option value="Autre">Autre - Ajouter votre marque</option>
                </select>
            </div>
            <div class="" id="addMarque">
            </div>
            <div class="inputbox" id="selectModele">
                <select name="modele" id="modele" required="required">
                    <option value="" disabled selected id="labelModele">Modèle</option>
                </select>
            </div>
            <div class="" id="addModele">
            </div>
            <div class="inputboxsub">
                <input type="submit" id="valid" value="Envoyer">
            </div>
            <div class="lien">
                <a href="">Êtes-vous déja inscrit ?</a><br>
                <a id="infoPreCt" href="">Qu’est-ce qu’un pré contrôle technique ?</a><br>
                <a href="pdf/moto concerne.pdf" target="_blank">Quels véhicules sont concernés?</a>
            </div>
        </form>
        <p class="credit">Ce site a été réalisé par le BTS SIO option SLAM 2ème année du lycée Claude Nougaro</p>
    </div>


    <script src="js/accueil.js"></script>
    <script src="js/switchForm.js"></script>

    <!-- Données véhicules (MARQUE, MODELE) pour le filtrage côté client -->
    <script id="vehicules-data" type="application/json">
        <?= json_encode($vehicules ?? [], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES) ?>
    </script>

    <div id="modal" class="modal" aria-hidden="true" role="dialog" aria-labelledby="modalTitle" aria-modal="true">
        <div class="modalOverlay" data-close></div>
        <div class="modalContent" role="document">
            <h2 id="modalTitle">Merci de votre demande!</h2>
            <p id="modalText">Vos informations ont bien été prises en compte. Vous serez contacté dans les plus brefs délais.</p>
            <button class="modalClose" data-close>D'accord</button>
        </div>
</body>

</html>