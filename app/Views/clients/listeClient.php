<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<section>
    <h1>Liste des clients</h1>
    <?php
    $tableauclient = new \CodeIgniter\View\Table();
    $tableauclient->setHeading('Nom', 'Prénom', 'Tel', 'Mail', 'Actions', ' ');
    foreach ($listeClients as $client) {
        $tableauclient->addRow(
            $client['NOM'],
            $client['PRENOM'],
            $client['TEL'],
            $client['EMAIL'],
            '<a class="btn-warning" href="' . url_to('modif-client', $client['IDCLIENT']) . '">Modifier</a>',
            '<a class="btn btn-danger" href="' . url_to('client-suppr', $client['IDCLIENT']) . '">Supprimer</a>'
        );
    }
    echo $tableauclient->generate();
    ?>
</section>
<?= $this->endSection() ?>