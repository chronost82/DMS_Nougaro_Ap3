<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div id="divCt">
</div>
<script>
    let updateView = function() {
        let cts = <?= json_encode($cts)  ?>;
        let ctEnCours = document.createElement('a');
        ctEnCours.innerHTML = cts[0].NOM + " " + cts[0].PRENOM + " " + cts[0].MARQUE + " " + cts[0].MODELE;
        divCt.append(ctEnCours);
    };

    setInterval(updateView(), 2000);
</script>
<?= $this->endsection('content') ?>