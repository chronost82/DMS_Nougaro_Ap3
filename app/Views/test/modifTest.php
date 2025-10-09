<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
    <section>
        <div class="form-contact">
            <form action=<?=url_to('modif-test')?> method="post">
                <input type="hidden" name="id" id="id" value="<?=$testAModif["IDTESTTECHNIQUE"]?>">
                <label for="libelle">Libéllé :</label>
                <input type="text" name="libelle" id="libelle" value="<?=$testAModif["LIBELLE"]?>">
            </form>
        </div>
    </section>
<?= $this->endSection() ?>