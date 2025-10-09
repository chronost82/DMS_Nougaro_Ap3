<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Ajout d'un test</title>
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
            <form action=<?=url_to('ajout-test')?> method="post">
                <label for="nom">Libéllé :</label>
                <input type="text" name="libelle" id="libelle">
            </form>
        </div>
    </section>

</body>
</html>