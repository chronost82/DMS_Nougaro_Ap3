<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<section class="container">
    <h1>Modification du client : <?= esc($clientAModif['NOM'] . ' ' . $clientAModif['PRENOM']) ?></h1>
    <form class="form-panel" action="<?= url_to('client-modif') ?>" method="post" autocomplete="on">
        <?= csrf_field() ?>
        <input type="hidden" name="idclient" id="idclient" value="<?= esc($clientAModif['IDCLIENT']) ?>">

        <fieldset style="border:0; padding:0; margin:0 0 1rem 0;">
            <legend class="muted" style="font-size:.8rem; margin-bottom:.5rem;">Informations client</legend>
            <div class="form-row">
                <div>
                    <label for="nom">Nom <span class="muted" aria-hidden="true">*</span></label>
                    <input type="text" name="nom" id="nom" value="<?= esc($clientAModif['NOM']) ?>" required autocomplete="family-name">
                </div>
                <div>
                    <label for="prenom">Prénom <span class="muted" aria-hidden="true">*</span></label>
                    <input type="text" name="prenom" id="prenom" value="<?= esc($clientAModif['PRENOM']) ?>" required autocomplete="given-name">
                </div>
            </div>
            <div class="form-row">
                <div>
                    <label for="tel">Téléphone</label>
                    <input type="tel" name="tel" id="tel" value="<?= esc($clientAModif['TEL']) ?>" autocomplete="tel">
                </div>
                <div>
                    <label for="email">Adresse mail</label>
                    <input type="email" name="email" id="email" value="<?= esc($clientAModif['EMAIL']) ?>" autocomplete="email">
                </div>
            </div>
        </fieldset>

        <div class="form-footer">
            <a href="<?= url_to('liste-clients') ?>" class="btn">Annuler</a>
            <button type="submit" class="btn-primary">Valider</button>
        </div>
    </form>
</section>

<?= $this->endSection() ?>