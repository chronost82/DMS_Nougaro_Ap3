<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Listes des Tests</title>
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
        <h1>Liste des clients</h1>
        <a class="bouton" href="<?= url_to('ajout-client') ?>">Nouveaux client</a>
        <?php
        $tableauClient = new \CodeIgniter\View\Table();
        $tableauClient->setHeading('Nom','Prenom','N°Téléphone','Adresse Mail','Modifier', 'Supprimer');
        foreach ($listeClient as $client) {
            $tableauClient->addRow(
                $client['nom'],
                $client['prenom'],
                $client['tel'],
                $client['mail'],
                '<a class="bouton" href="' . url_to('modif-client', $client['idclient']) . '">Modifier</a>',
                '<a class="bouton" href="' . url_to('suppr-client', $client['idclient']) . '">Supprimer</a>'
            );
        }
        echo $tableauClient->generate();
        ?>



    </section>

</body>

</html>