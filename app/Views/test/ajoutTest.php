<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<section class="container">
    <h1>Création d'un test</h1>
    <form class="form-panel" action="<?= url_to('test-ajout') ?>" method="post" autocomplete="on">
        <?= csrf_field() ?>

        <div class="form-row">
            <div>
                <label for="libelle">Libellé <span class="muted" aria-hidden="true">*</span></label>
                <input type="text" name="libelle" id="libelle" required>
            </div>
        </div>

        <div class="form-footer">
            <a href="<?= url_to('test-liste') ?>" class="btn">Annuler</a>
            <button type="submit" class="btn-primary">Valider</button>
        </div>
    </form>
</section>

<?= $this->endSection() ?>