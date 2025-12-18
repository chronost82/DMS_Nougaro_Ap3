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
    <div id="divInfo">
    </div>
    <div class="ct-main-card" role="form" aria-labelledby="ctFormTitle">
        <ul class="ct-checklist " id="ctFormTitle">
        </ul>
    </div>
</body>
<script>
    let infoEleve = document.createElement('p');
    let infoVehicule = document.createElement('p');
    let immatriculation = document.createElement('p');
    let updateView = async function() {
        let response = await fetch("<?= url_to('ecran-controle-technique-ajax', $ct['IDCT']) ?>");
        let ctDetails = await response.json();
        infoEleve.innerHTML = "Véhicule controllé par : " + ctDetails[0]['PRENOM'] + ' ' + ctDetails[0]['NOM'];
        infoVehicule.innerHTML = "Véhicule : " + ctDetails[0]['MARQUE'] + ' ' + ctDetails[0]['MODELE'];
        immatriculation.innerHTML = "Immatriculation : " + ctDetails[0]['IMAT'];
        ctFormTitle.innerHTML = '';
        ctDetails.forEach(function(element, index2) {
            let liTest = document.createElement('li');
            liTest.className = "ct-check-item";
            liTest.setAttribute("data-idtest", element.IDTEST);
            liTest.setAttribute("data-state", element.ETAT);

            let divGroup = document.createElement('div');
            divGroup.className = "ct-choice-group";
            divGroup.setAttribute("role", "radiogroup");
            divGroup.setAttribute("aria-label", element.LIBELLE);

            ["ok", "surv", "def"].forEach(function(value, index) {
                let label = document.createElement('label');
                let input = document.createElement('input');
                input.type = "radio";
                input.name = index2;
                input.value = value;
                if (value === element.ETAT) {
                    input.checked = true;
                }

                let span = document.createElement('span');
                span.className = "ct-square";
                span.setAttribute("data-color", value === "ok" ? "green" : value === "surv" ? "amber" : "red");

                label.appendChild(input);
                label.appendChild(span);
                divGroup.appendChild(label);
            });

            let spanLabel = document.createElement('span');
            spanLabel.className = "ct-check-label";
            spanLabel.textContent = element.LIBELLE;


            liTest.appendChild(divGroup);
            liTest.appendChild(spanLabel);

            ctFormTitle.appendChild(liTest);
        });

    };

    setInterval(updateView, 2000);
    divInfo.append(infoVehicule);
    divInfo.append(immatriculation);
    divInfo.append(infoEleve);
    
</script>