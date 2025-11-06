"use strict";

function isValidEmail(email) {
    let regex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    return regex.test(email);
}

function isValidPhone(tel) {
    let regex = /^((\+|00)33\s?|0)[567](\s?\d{2}){4}$/;
    return regex.test(tel);
}

window.onload = function () {
    var nameEl = document.getElementById("name");
    if (nameEl && typeof nameEl.focus === 'function') {
        nameEl.focus();
    }
};

var validBtn = document.getElementById('valid');
if (validBtn) {
    validBtn.onclick = function () {
        var emailEl = document.getElementById('email');
        var telEl = document.getElementById('tel');
        var errorEmailEl = document.getElementById('errorEmail');
        var errorTelEl = document.getElementById('errorTel');

        var formValide = true;

        var emailVal = emailEl ? emailEl.value : '';
        if (!isValidEmail(emailVal)) {
            formValide = false;
            errorEmailEl.textContent = "Email incorrect";
            errorEmailEl.style.color = 'red';
        } else {
            errorEmailEl.textContent = "Email";
            errorEmailEl.style.color = 'black';
        }

        var telVal = telEl ? telEl.value : '';
        if (!isValidPhone(telVal)) {
            formValide = false;
            errorTelEl.textContent = "Téléphone incorrect";
            errorTelEl.style.color = 'red';
        } else {
            errorTelEl.textContent = "Téléphone";
            errorTelEl.style.color = 'black';
        }

        return formValide;
    };
}

// Filtre les modèles selon la marque choisie (sans requête serveur)
document.addEventListener('DOMContentLoaded', function () {
    var selectMarque = document.getElementById('marque');
    var selectModele = document.getElementById('modele');
    if (!selectMarque || !selectModele) return;

    var data = [];
    var dataEl = document.getElementById('vehicules-data');
    if (dataEl) {
        try {
            data = JSON.parse(dataEl.textContent || '[]');
        } catch (e) {
            data = [];
        }
    }

    // Au chargement, si aucune marque n'est choisie, griser le modèle par défaut
    if (!selectMarque.value) {
        selectModele.disabled = true;
    }

    // Active le filtrage uniquement si on a des paires (MARQUE, MODELE)
    if (Array.isArray(data) && data.length > 0) {
        function rebuildModeles(marque) {
            // Reset
            selectModele.innerHTML = '';
            var optPlaceholder = document.createElement('option');
            optPlaceholder.value = '';
            optPlaceholder.disabled = true;
            optPlaceholder.selected = true;
            optPlaceholder.textContent = 'Modèle';
            selectModele.appendChild(optPlaceholder);

            if (!marque) {
                selectModele.disabled = true;
                return;
            }

            var seen = Object.create(null);
            for (var i = 0; i < data.length; i++) {
                var item = data[i];
                if (item && item.MARQUE === marque && item.MODELE && !seen[item.MODELE]) {
                    seen[item.MODELE] = true;
                    var opt = document.createElement('option');
                    opt.value = item.MODELE;
                    opt.textContent = item.MODELE;
                    selectModele.appendChild(opt);
                }
            }
            selectModele.disabled = selectModele.options.length <= 1;
        }

        selectMarque.addEventListener('change', function () { rebuildModeles(selectMarque.value); });
        // Si une valeur est préremplie (avec withInput), reconstruire
        if (selectMarque.value) {
            rebuildModeles(selectMarque.value);
        } else {
            // Au chargement, griser le modèle tant qu'aucune marque n'est choisie
            selectModele.disabled = true;
        }
    }
});