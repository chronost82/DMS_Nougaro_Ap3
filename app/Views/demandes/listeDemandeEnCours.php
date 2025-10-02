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
        <h1>Liste des demandes en cours</h1>
        <a class="bouton" href="<?= url_to('admin-ajout-demande') ?>">Ajouter une demande</a>
        <?php
        $tableauDemandes = new \CodeIgniter\View\Table();
        $tableauDemandes->setHeading('Nom','Prenom','N°Téléphone','Adresse Mail','Marque','Modèle','Etat','Modifier', 'Supprimer');
        foreach ($listeDemandes as $demande) {
            $tableauDemandes->addRow(
                $demande['nom'],
                $demande['prenom'],
                $demande['tel'],
                $demande['email'],
                $demande['tel'],
                $demande['marque'],
                $demande['model'],
                $demande['etat'],
                '<a class="bouton" href="' . url_to('admin-demande-en-attente-modif', $demande['iddemande']) . '">Modifier</a>',
                '<a class="bouton" href="' . url_to('admin-suppr-demande-en-attente', $demande['iddemande']) . '">Supprimer</a>'
            );
        }
        echo $tableauDemandes->generate();
        ?>



    </section>

</body>

</html>