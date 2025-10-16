<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
    <section>
        <div class="form-contact">
            <form action=<?=url_to('test-modif')?> method="post">
                <input type="hidden" name="idTest" id="id" value="<?=$testAModif["IDTESTTECHNIQUE"]?>">
                <label for="libelle">Libéllé :</label>
                <input type="text" name="libelle" id="libelle" value="<?=$testAModif["LIBELLE"]?>">
                <input type="submit" value="Valider">
            </form>
        </div>
    </section>
<?= $this->endSection() ?>