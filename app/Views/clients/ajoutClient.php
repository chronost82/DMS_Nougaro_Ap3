<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajout d'un client</title>
    <meta name="description" content="The small framework with powerful features">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" type="image/png" href="/favicon.ico">
    <link rel="stylesheet" href="">

    <!-- STYLES -->

    <style {csp-style-nonce}>

    </style>
</head>

<body>

    <section>
        <div class="form-contact">
            <form action=<?=url_to('ajout-client')?> method="post">
                <label for="nom">Nom :</label>
                <input type="text" name="nom" id="nom" placeholder="Nom">

                <label for="prenom">Prénom :</label>
                <input type="text" name="prenom" id="prenom" placeholder="Prénom">

                <label for="email">Email :</label>
                <input type="email" name="email" placeholder="Email">

                <label for="telephone">Téléphone :</label>
                <input type="tel" name="telephone" id="telephone" placeholder="Téléphone">
            </form>
        </div>
    </section>

</body>

</html>