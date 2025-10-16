<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h1>Création d'un test</h1>
<section>
    <div class="form-contact">
        <form action=<?= url_to('test-ajout') ?> method="post">
            <label for="nom">Libéllé :</label>
            <input type="text" name="libelle" id="libelle">
            <input type="submit" value="Valider">
        </form>
    </div>
</section>
<?= $this->endSection() ?>