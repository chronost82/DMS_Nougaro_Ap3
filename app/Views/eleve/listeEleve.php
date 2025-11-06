<?php $this->extend('layout') ?>
<?= $this->section('content') ?>
<h1>Liste des élèves</h1>
<a class="bouton" href="<?= url_to('ajout-eleve') ?>">Ajouter un eleve</a>
<?php
$tableauEleves = new \CodeIgniter\View\Table();
$tableauEleves->setHeading('Nom', 'Prénom', 'Année de Promotion', 'Actions', ' ');
foreach ($listeEleves as $eleve) {
    $tableauEleves->addRow(
        $eleve['NOM'],
        $eleve['PRENOM'],
        $eleve['ANNEE'],
        '<a class="bouton" href="' . url_to('eleve-modif', $eleve['IDELEVE']) . '">Modifier</a>',
        '<a class="bouton" href="' . url_to('suppr-eleve', $eleve['IDELEVE']) . '">Supprimer</a>'
    );
}
echo $tableauEleves->generate();
?>
<?php $this->endSection() ?>