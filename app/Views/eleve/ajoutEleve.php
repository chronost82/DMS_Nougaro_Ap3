<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<section>
    <div class="form-contact">
        <form action=<?= url_to('eleve-ajout') ?> method="post">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" placeholder="Nom">

            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" id="prenom" placeholder="Prénom">

            <label for="text">Année de Promotion :</label>
            <input type="text" name="annee" placeholder="Annee">
            <input type="submit" value="Valider">
        </form>
    </div>
</section>
<?= $this->endSection() ?>