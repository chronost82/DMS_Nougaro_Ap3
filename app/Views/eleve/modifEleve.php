<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<section class="container">
    <h1>Modification de l'élève : <?= esc($eleveAModif['NOM'] . ' ' . $eleveAModif['PRENOM']) ?></h1>
    <form class="form-panel" action="<?= url_to('modif-eleve') ?>" method="post" autocomplete="on">
        <?= csrf_field() ?>
        <input type="hidden" name="idEleve" id="idEleve" value="<?= esc($eleveAModif['IDELEVE']) ?>">

        <fieldset style="border:0; padding:0; margin:0 0 1rem 0;">
            <legend class="muted" style="font-size:.8rem; margin-bottom:.5rem;">Informations élève</legend>
            <div class="form-row">
                <div>
                    <label for="nom">Nom <span class="muted" aria-hidden="true">*</span></label>
                    <input type="text" name="nom" id="nom" required autocomplete="family-name" value="<?= esc($eleveAModif['NOM']) ?>">
                </div>
                <div>
                    <label for="prenom">Prénom <span class="muted" aria-hidden="true">*</span></label>
                    <input type="text" name="prenom" id="prenom" required autocomplete="given-name" value="<?= esc($eleveAModif['PRENOM']) ?>">
                </div>
            </div>
            <div class="form-row">
                <div>
                    <label for="annee">Année de promotion</label>
                    <input type="text" name="annee" id="annee" value="<?= esc($eleveAModif['ANNEE']) ?>" placeholder="2025">
                </div>
            </div>
        </fieldset>

        <div class="form-footer">
            <a href="<?= url_to('liste-eleve') ?>" class="btn">Annuler</a>
            <button type="submit" class="btn-primary">Valider</button>
        </div>
    </form>
</section>

<?= $this->endSection() ?>