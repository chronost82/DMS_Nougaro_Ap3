<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Welcome to CodeIgniter 4!</title>
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
        <h1>Liste des test</h1>
        <a class="bouton" href="<?= url_to('ajout-test') ?>">Nouveaux test</a>
        <?php
        $tableauTest = new \CodeIgniter\View\Table();
        $tableauTest->setHeading('Nom du test', 'Modifier', 'Supprimer');
        foreach ($listeTest as $test) {
            $tableauTest->addRow(
                $test['nom'],
                '<a class="bouton" href="' . url_to('modif-test', $test['id']) . '">Modifier</a>',
                '<a class="bouton" href="' . url_to('suppr-test', $test['id']) . '">Supprimer</a>'
            );
        }
        echo $tableauTest->generate();
        ?>



    </section>

</body>

</html>