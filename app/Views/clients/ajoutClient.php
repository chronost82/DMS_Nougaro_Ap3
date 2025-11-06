<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<section class="container">
    <h1>Ajouter un client</h1>
    <form class="form-panel" action="<?= url_to('ajout-client') ?>" method="post" autocomplete="on">
        <?= csrf_field() ?>

        <fieldset style="border:0; padding:0; margin:0 0 1rem 0;">
            <legend class="muted" style="font-size:.8rem; margin-bottom:.5rem;">Informations client</legend>
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
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email" placeholder="jean.dupont@example.com" autocomplete="email">
                </div>
                <div>
                    <label for="telephone">Téléphone</label>
                    <input type="tel" name="telephone" id="telephone" placeholder="06 12 34 56 78" autocomplete="tel">
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