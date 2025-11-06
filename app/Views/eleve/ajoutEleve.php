<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<section class="container">
    <h1>Ajouter un élève</h1>
    <form class="form-panel" action="<?= url_to('eleve-ajout') ?>" method="post" autocomplete="on">
        <?= csrf_field() ?>

        <fieldset style="border:0; padding:0; margin:0 0 1rem 0;">
            <legend class="muted" style="font-size:.8rem; margin-bottom:.5rem;">Informations élève</legend>
            <div class="form-row">
                <div>
                    <label for="nom">Nom <span class="muted" aria-hidden="true">*</span></label>
                    <input type="text" name="nom" id="nom" placeholder="Dupont" required autocomplete="family-name">
                </div>
                <div>
                    <label for="prenom">Prénom <span class="muted" aria-hidden="true">*</span></label>
                    <input type="text" name="prenom" id="prenom" placeholder="Jean" required autocomplete="given-name">
                </div>
            </div>
            <div class="form-row">
                <div>
                    <label for="annee">Année de promotion</label>
                    <input type="text" name="annee" id="annee" placeholder="2025">
                </div>
            </div>
        </fieldset>

        <div class="form-footer">
            <button type="reset" class="btn-secondary">Réinitialiser</button>
            <button type="submit" class="btn-primary">Valider</button>
        </div>
    </form>
</section>

<?= $this->endSection() ?>