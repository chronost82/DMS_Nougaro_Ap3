<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<section class="container">
    <h1>Modification du test : <?= esc($testAModif['LIBELLE']) ?></h1>
    <form class="form-panel" action="<?= url_to('test-modif') ?>" method="post" autocomplete="on">
        <?= csrf_field() ?>
        <input type="hidden" name="idTest" id="idTest" value="<?= esc($testAModif['IDTESTTECHNIQUE']) ?>">

        <div class="form-row">
            <div>
                <label for="libelle">Libellé <span class="muted" aria-hidden="true">*</span></label>
                <input type="text" name="libelle" id="libelle" value="<?= esc($testAModif['LIBELLE']) ?>" required>
            </div>
        </div>

        <div class="form-footer">
            <a href="<?= url_to('test-liste') ?>" class="btn">Annuler</a>
            <button type="submit" class="btn-primary">Valider</button>
        </div>
    </form>
</section>

<?= $this->endSection() ?>