<?php $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php
if (session()->getFlashdata('confirm') !== null) {
    echo '<p>' . session()->getFlashdata('confirm') . '<p>';
    echo '<a class="btn-edit" href="' . url_to('suppr-eleve', session()->getFlashdata('idEleveToDelete')) . '">Oui</a>';
    echo '<a class="btn-edit"href="' . url_to('liste-eleve') . '">Non</a>';
}
?>
?>
<section class="container">
    <div class="card">
        <div style="padding:16px; border-bottom:1px solid var(--border); background:#fafafa; display:flex; align-items:center; justify-content:space-between; gap:12px;">
            <h1>Liste des élèves</h1>
            <a class="btn-primary" href="<?= url_to('ajout-eleve') ?>">Ajouter un élève</a>
        </div>
        <div class="table-wrap" style="padding: 8px 12px 16px;">
            <?php
            $tableauEleves = new \CodeIgniter\View\Table();
            $tableauEleves->setTemplate([
                'table_open' => '<table id="elevesTable">'
            ]);
            $tableauEleves->setHeading('Nom', 'Prénom', 'Année de Scolarité', 'Actions', ' ');
            foreach ($listeEleves as $eleve) {
                $tableauEleves->addRow(
                    esc($eleve['NOM']),
                    esc($eleve['PRENOM']),
                    esc($eleve['ANNEE'] . '-' . $eleve['ANNEE'] + 1),
                    '<a class="btn" href="' . url_to('annee-modif', $eleve['IDELEVE']) . '">Rafraîchir l\'année de scolarité</a>',
                    '<a class="btn-warning" href="' . url_to('eleve-modif', $eleve['IDELEVE']) . '">Modifier</a>',
                    '<a class="btn btn-danger" href="' . url_to('eleve-confirm-suppr', $eleve['IDELEVE']) . '">Supprimer</a>'
                );
            }
            echo $tableauEleves->generate();
            ?>
        </div>
    </div>

</section>
<?= $this->endSection() ?>