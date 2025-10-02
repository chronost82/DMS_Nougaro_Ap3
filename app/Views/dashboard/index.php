<?php $this->extend('layout') ?>


<?= $this->section('meta') ?>
<link rel="stylesheet" href="css/dashboard.css">
<?php $this->endSection() ?>



<?= $this->section('content') ?>

<main>
    <nav class="nav-grid" aria-label="Navigation principale">
        <a href="<?= url_to('admin-liste-demandes-en-attentes')?>">Liste des demandes</a>
        <a href="<?= url_to('admin-liste-clients')?>">Liste des clients</a>
        <a href="<?= url_to('liste-test') ?>">Liste des tests</a>
        <a href="<?= url_to('suppr-test') ?>">Demandes validées</a>
        <a href="<?= url_to('admin-liste-eleves') ?>">Liste des élèves</a>
    </nav>
</main>

<?= $this->endSection() ?>