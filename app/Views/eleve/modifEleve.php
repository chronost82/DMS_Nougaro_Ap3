<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<h1>Modification de l'eleve : <?=$eleveAModif['NOM'].' '. $eleveAModif['PRENOM']?></h1>
    <section>
        <div class="form-contact">
            <form action=<?=url_to('modif-eleve')?> method="post">
                <input type="hidden" name="idEleve" id="id" value="<?=$eleveAModif["IDELEVE"]?>">
                <label for="nom">Nom :</label>
                <input type="text" name="nom" id="nom" value="<?=$eleveAModif["NOM"]?>">
                <label for="prenom">Prénom :</label>
                <input type="text" name="prenom" id="prenom" value="<?=$eleveAModif["PRENOM"]?>">
                <label for="text">Année de promotion  :</label>
                <input type="text" name="annee" id="annee" value="<?=$eleveAModif["ANNEE"]?>">
                <input type="submit" value="Valider">
            </form>
        </div>
    </section>
<?= $this->endSection() ?>