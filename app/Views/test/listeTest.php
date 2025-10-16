<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
    <section>
        <h1>Liste des tests</h1>
        <a class="bouton" href="<?= url_to('ajout-test') ?>">Nouveaux test</a>
        <?php
        $tableauTest = new \CodeIgniter\View\Table();
        $tableauTest->setHeading('libelle du test', 'Actions', ' ');
        foreach ($listeTest as $test) {
            $tableauTest->addRow(
                $test['LIBELLE'],
                '<a class="bouton" href="' . url_to('modif-test',$test['IDTESTTECHNIQUE']) . '">Modifier</a>',
                '<a class="bouton" href="' . url_to('test-suppr',$test['IDTESTTECHNIQUE']) . '">Supprimer</a>'
            );
        }
        echo $tableauTest->generate();
        ?>
    </section>
<?= $this->endSection() ?>