<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<section class="container">
    <div class="card">
        <div style="padding:16px; border-bottom:1px solid var(--border); background:#fafafa; display:flex; align-items:center; justify-content:space-between; gap:12px;">
            <h1>Liste des tests</h1>
            <?php
            if (session()->getFlashdata('confirm') !== null) {
                echo '<p class ="warning">' . session()->getFlashdata('confirm') . '<p>';
                echo '<a class="btn-danger" href="' . url_to('test-suppr', session()->getFlashdata('idTestToDelete')) . '">Oui</a>';
                echo'   '; 
                echo '<a class="btn-primary"href="' . url_to('test-liste') . '">Non</a>';
            }
            ?>
            <a class="btn-primary" href="<?= url_to('ajout-test') ?>">Nouveau test</a>
        </div>
        <div class="table-wrap" style="padding: 8px 12px 16px;">
            <?php
            $tableauTest = new \CodeIgniter\View\Table();
            $tableauTest->setTemplate([
                'table_open' => '<table id="testsTable">'
            ]);
            $tableauTest->setHeading('Libellé du test', 'Actions', ' ');
            foreach ($listeTest as $test) {
                $tableauTest->addRow(
                    esc($test['LIBELLE']),
                    '<a class="btn-primary" href="' . url_to('modif-test', $test['IDTESTTECHNIQUE']) . '">Modifier</a>',
                    '<a class="btn-danger" href="' . url_to('test-confirm-suppr', $test['IDTESTTECHNIQUE']) . '">Supprimer</a>'
                );
            }
            echo $tableauTest->generate();
            ?>
        </div>
    </div>
</section>
<?= $this->endSection() ?>