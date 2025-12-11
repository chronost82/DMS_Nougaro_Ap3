<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contrôle technique</title>
    <link rel="stylesheet" href="<?= base_url('css/layout.css') ?>">
    <link rel="stylesheet" href="<?= base_url('css/controleTechnique.css') ?>">
</head>

<body>
    <div class="ct-main-card" role="form" aria-labelledby="ctFormTitle">
        <ul class="ct-checklist " id="ctFormTitle">
    </div>
</body>

<script>
    let updateView = async function() {
        let response = fetch("<?= url_to('ecran-controle-technique-ajax', $ct['IDCT']) ?>");
        let ctDetails = await response;
        ctDetails = await ctDetails.json();
        ctDetails.forEach(function(element){
            console.log(element);
            liTest = document.createElement('li');
            divTest = document.createElement('div');
            spanTest = document.createElement('span');
            liTest.className = "ct-checklist-item";
            liTest.setAttribute("data-ct-detail-id", element.IDCTDETAIL);
            divTest.className = "ct-choice-group";
            spanTest.className = "ct-check-label";

            spanTest.innerHTML = element.LIBELLE;
            ctFormTitle.appendChild(liTest);
            liTest.appendChild(divTest);
            divTest.appendChild(spanTest);
        });

    };

    updateView();
</script>