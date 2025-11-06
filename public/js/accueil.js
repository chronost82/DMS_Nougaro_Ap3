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

    if (!selectMarque.value) {
        selectModele.disabled = true;
    }

    if (Array.isArray(data) && data.length > 0) {
        function rebuildModeles(marque) {
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
        if (selectMarque.value) {
            rebuildModeles(selectMarque.value);
        } else {
            selectModele.disabled = true;
        }
    }

    // Modal + soumission contrôlée
    var form = document.querySelector('.form-contact form') || document.querySelector('form');
    var submitBtn = document.getElementById('valid');
    var emailEl = document.getElementById('email');
    var telEl = document.getElementById('tel');
    var errorEmailEl = document.getElementById('errorEmail');
    var errorTelEl = document.getElementById('errorTel');
    var modal = document.getElementById('modal');
    var btntest = document.getElementById('btntest');
    var modalConfirm = modal ? modal.querySelector('.modal-close') : null;
    var modalOverlay = modal ? modal.querySelector('.modal-overlay') : null;

    function validateForm() {
        var ok = true;
        var emailVal = emailEl ? emailEl.value : '';
        var telVal = telEl ? telEl.value : '';

        if (!isValidEmail(emailVal)) {
            ok = false;
            if (errorEmailEl) { errorEmailEl.textContent = "Email incorrect"; errorEmailEl.style.color = 'red'; }
        } else {
            if (errorEmailEl) { errorEmailEl.textContent = "Email"; errorEmailEl.style.color = 'black'; }
        }

        if (!isValidPhone(telVal)) {
            ok = false;
            if (errorTelEl) { errorTelEl.textContent = "Téléphone incorrect"; errorTelEl.style.color = 'red'; }
        } else {
            if (errorTelEl) { errorTelEl.textContent = "Téléphone"; errorTelEl.style.color = 'black'; }
        }

        return ok;
    }

    function openModal() {
        if (!modal) return;
        modal.classList.add('open');
        modal.setAttribute('aria-hidden', 'false');
        document.body.style.overflow = 'hidden';
        // focus bouton de confirmation si présent
        if (modalConfirm && typeof modalConfirm.focus === 'function') modalConfirm.focus();
    }

    function closeModal() {
        if (!modal) return;
        modal.classList.remove('open');
        modal.setAttribute('aria-hidden', 'true');
        document.body.style.overflow = '';
        if (btntest && typeof btntest.focus === 'function') btntest.focus();
    }

    // Interception de l'envoi : ouvrir la modal avant la soumission effective
    if (form && submitBtn) {
        form.addEventListener('submit', function (e) {
            // On intercepte toujours, on soumetra manuellement après confirmation si tout est OK
            e.preventDefault();

            if (!validateForm()) {
                // erreurs affichées par validateForm(); ne pas ouvrir la modal
                return;
            }

            // Validation OK -> ouvrir la modal
            openModal();

            // un seul handler de confirmation pour soumettre après clic sur "D'accord"
            var onConfirm = function () {
                // fermer modal puis soumettre le formulaire
                closeModal();
                // retirer le listener pour éviter double-soumission
                if (modalConfirm) modalConfirm.removeEventListener('click', onConfirm);
                form.submit();
            };

            if (modalConfirm) {
                // retirer écouteurs précédents pour sécurité puis ajouter
                modalConfirm.removeEventListener('click', onConfirm);
                modalConfirm.addEventListener('click', onConfirm);
            }
        });
    }

    // gestion fermeture modal via overlay et echap
    if (modalOverlay) {
        modalOverlay.addEventListener('click', function () { closeModal(); });
    }

    document.addEventListener('keydown', function (e) {
        if (e.key === 'Escape' && modal && modal.classList.contains('open')) {
            closeModal();
        }
    });

    // petit comportement pour btntest (si présent)
    var bt = document.getElementById('btntest');
    if (bt) {
        bt.onclick = function () {
            if (modal) openModal();
        };
    }
});