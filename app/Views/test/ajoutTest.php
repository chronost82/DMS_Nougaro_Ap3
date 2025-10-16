<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
    <section>
        <div class="form-contact">
            <form action=<?=url_to('test-ajout')?> method="post">
                <label for="nom">Libéllé :</label>
                <input type="text" name="libelle" id="libelle">
                <input type="submit" value="Valider">
            </form>
        </div>
    </section>
<?= $this->endSection() ?>