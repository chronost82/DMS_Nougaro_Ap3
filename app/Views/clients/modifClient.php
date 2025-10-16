<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h1>Modification du client : <?=$clientAModif['NOM'].' '. $clientAModif['PRENOM']?></h1>
    <section>
        <div class="form-contact">
            <form action=<?=url_to('client-modif')?> method="post">
                <input type="hidden" name="idclient" id="id" value="<?=$clientAModif["IDCLIENT"]?>">
                <label for="nom">Nom :</label>
                <input type="text" name="nom" id="nom" value="<?=$clientAModif["NOM"]?>">
                <label for="prenom">Prénom :</label>
                <input type="text" name="prenom" id="prenom" value="<?=$clientAModif["PRENOM"]?>">
                <label for="tel">Téléphone :</label>
                <input type="text" name="tel" id="tel" value="<?=$clientAModif["TEL"]?>">
                <label for="mail">Adresse mail :</label>
                <input type="text" name="mail" id="mail" value="<?=$clientAModif["MAIL"]?>">
                <input type="submit" value="Valider">
            </form>
        </div>
    </section>
<?= $this->endSection() ?>