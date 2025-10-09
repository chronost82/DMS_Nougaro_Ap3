<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
    <section>
        <h1>Liste des tests</h1>
        <a class="bouton" href="<?= url_to('ajout-test') ?>">Nouveaux test</a>
        <?php
        $tableauTest = new \CodeIgniter\View\Table();
        $tableauTest->setHeading('libelle du test', 'Modification', 'Suppression');
        foreach ($listeTest as $test) {
            $tableauTest->addRow(
                $test['LIBELLE'],
                '<a class="bouton" href="' . url_to('test-modif',$test['IDTESTTECHNIQUE']) . '">Modifier</a>',
                '<a class="bouton" href="' . url_to('suppr-test',$test['IDTESTTECHNIQUE']) . '">Supprimer</a>'
            );
        }
        echo $tableauTest->generate();
        ?>
        <?=var_dump($test)?>
    </section>
<?= $this->endSection() ?>