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
    <div class="ct-wrapper" data-idct="<?= esc(($ct[0]['IDCT'] ?? null) ?? ($ct['IDCT'] ?? '')) ?>">
        <div class="ct-header">
            <h1>Contrôle technique</h1>
            <?php $ct = $ct[0]; ?>
            <div class="ct-vehicle-infos">
                <div class="ct-vehicle-box" aria-label="Controlleur">
                    <span>Nom Controlleur :</span>
                    <select id="select-controleur">
                        <option value="">-- Sélectionner un élève --</option>
                        <?php if (!empty($eleves)) : ?>
                            <?php foreach ($eleves as $eleve) : ?>
                                <option value="<?= esc($eleve['IDELEVE']) ?>" <?= (isset($ct['IDELEVE']) && $ct['IDELEVE'] == $eleve['IDELEVE']) ? 'selected' : '' ?>>
                                    <?= esc($eleve['NOM'] . ' ' . $eleve['PRENOM']) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </select>
                </div>
                <div class="ct-vehicle-box" aria-label="Immatriculation">
                    <span>Immatriculation :</span> <?= esc($ct['IMAT'] ?? '') ?>
                </div>
                <div class="ct-vehicle-box" aria-label="Modèle">
                    <span>Marque - Modèle :</span>
                    <?= esc(($ct['MARQUE'] ?? '') . ' - ' . ($ct['MODELE'] ?? '')) ?>
                </div>
                <div class="ct-vehicle-box" aria-label="Chassis">
                    <span>Numéro Chassis : <?= esc($ct['NUMCHASSIS'] ?? '') ?></span>
                </div>
            </div>
        </div>

        <div class="ct-main-card" role="form" aria-labelledby="ctFormTitle">
            <ul class="ct-checklist" id="ctFormTitle">
                <?php if (!empty($tests)) : ?>
                    <?php foreach ($tests as $test) : ?>
                        <li class="ct-check-item" data-idtest="<?= esc($test['IDTESTTECHNIQUE']) ?>">
                            <div class="ct-choice-group" role="radiogroup" aria-label="<?= esc($test['LIBELLE']) ?>">
                                <label>
                                    <input type="radio" name="test_<?= esc($test['IDTESTTECHNIQUE']) ?>" value="ok" checked>
                                    <span class="ct-square" data-color="green"></span>
                                </label>
                                <label>
                                    <input type="radio" name="test_<?= esc($test['IDTESTTECHNIQUE']) ?>" value="surv">
                                    <span class="ct-square" data-color="amber"></span>
                                </label>
                                <label>
                                    <input type="radio" name="test_<?= esc($test['IDTESTTECHNIQUE']) ?>" value="def">
                                    <span class="ct-square" data-color="red"></span>
                                </label>
                            </div>
                            <span class="ct-check-label"><?= esc($test['LIBELLE']) ?></span>
                        </li>
                    <?php endforeach; ?>
                <?php else : ?>
                    <li>Aucun test technique défini.</li>
                <?php endif; ?>
            </ul>

            <div class="ct-actions-panel">
                <button class="btn-primary ct-btn-finish" type="button" title="Terminer le contrôle">Terminer</button>
                <div class="ct-legend">
                    <div class="ct-legend-item"><span class="ct-legend-swatch ok"></span>OK</div>
                    <div class="ct-legend-item"><span class="ct-legend-swatch warn"></span>A surveiller</div>
                    <div class="ct-legend-item"><span class="ct-legend-swatch ko"></span>Défaillant</div>
                </div>
            </div>
        </div>

        <div class="ct-comment-section">
            <label for="commentaire-ct" class="ct-comment-label">Commentaires :</label>
            <textarea id="commentaire-ct" class="ct-comment-textarea" rows="4" placeholder="Ajoutez vos observations ou remarques sur le contrôle..."><?= esc($ct['COMMENTAIRE'] ?? '') ?></textarea>
        </div>
    </div>

    <script>
        // Script de coloration des lignes + enregistrement immédiat des états
        (function() {
            const valueLabels = {
                ok: 'OK',
                surv: 'À surveiller',
                def: 'Défaillant'
            };

            function setItemState(input) {
                const item = input.closest('.ct-check-item');
                if (!item) return;
                item.setAttribute('data-state', input.value);
            }

            // Initialiser les valeurs initiales
            document.querySelectorAll('.ct-choice-group').forEach(group => {
                const checked = group.querySelector('input[type="radio"]:checked');
                if (checked) setItemState(checked);
            });

            // À chaque changement de radio : colorer + envoyer immédiatement en AJAX
            document.addEventListener('change', e => {
                const input = e.target;
                if (!(input instanceof HTMLInputElement)) return;
                if (input.type !== 'radio') return;
                if (!valueLabels[input.value]) return;

                setItemState(input);

                const item = input.closest('.ct-check-item');
                const idTest = item ? item.getAttribute('data-idtest') : null;
                const wrapper = document.querySelector('.ct-wrapper');
                const idCt = wrapper ? wrapper.getAttribute('data-idct') : null;

                if (!idCt || !idTest) {
                    console.warn('IDCT ou IDTEST manquant', {
                        idCt,
                        idTest
                    });
                    return;
                }

                // Envoi en POST x-www-form-urlencoded pour correspondre à saveEtat()
                const params = new URLSearchParams();
                params.append('idCt', idCt);
                params.append(`etat[${idTest}]`, input.value); // $_POST['etat'][idTest]

                fetch('<?= base_url('controletechnique/save-etat') ?>', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                            'X-Requested-With': 'XMLHttpRequest'
                        },
                        body: params.toString()
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.status !== 'success') {
                            alert('Erreur lors de l’enregistrement de ce test.');
                        }
                    })
                    .catch(() => {
                        alert('Erreur réseau lors de l\'enregistrement de ce test.');
                    });
            });

            // Changement de contrôleur : mise à jour de IDELEVE dans CT
            const selectControleur = document.getElementById('select-controleur');
            const wrapper = document.querySelector('.ct-wrapper');
            const idCt = wrapper ? wrapper.getAttribute('data-idct') : null;

            if (selectControleur && idCt) {
                    selectControleur.addEventListener('change', () => {
                        const idEleve = selectControleur.value;
                        if (!idEleve) {
                            return;
                        }

                        const params = new URLSearchParams();
                        params.append('idCt', idCt);
                        params.append('idEleve', idEleve);

                        fetch('<?= base_url('controletechnique/save-controleur') ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: params.toString()
                            })
                            .then(r => r.json())
                            .then(data => {
                                if (data.status !== 'success') {
                                    alert('Erreur lors de la mise à jour du contrôleur.');
                                }
                            })
                        .catch(() => {
                            alert('Erreur réseau lors de la mise à jour du contrôleur.');
                        });
                });
            }

            // Sauvegarde automatique du commentaire au changement (debounced)
            const commentaireTextarea = document.getElementById('commentaire-ct');
            let timeoutCommentaire = null;

            if (commentaireTextarea && idCt) {
                commentaireTextarea.addEventListener('input', () => {
                    clearTimeout(timeoutCommentaire);
                    timeoutCommentaire = setTimeout(() => {
                        const commentaire = commentaireTextarea.value;

                        const params = new URLSearchParams();
                        params.append('idCt', idCt);
                        params.append('commentaire', commentaire);

                        fetch('<?= base_url('controletechnique/save-commentaire') ?>', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8',
                                    'X-Requested-With': 'XMLHttpRequest'
                                },
                                body: params.toString()
                            })
                            .then(r => r.json())
                            .then(data => {
                                if (data.status !== 'success') {
                                    console.error('Erreur lors de la sauvegarde du commentaire.');
                                }
                            })
                            .catch(() => {
                                console.error('Erreur réseau lors de la sauvegarde du commentaire.');
                            });
                    }, 1000); // Attendre 1 seconde après la dernière frappe
                });
            }
        })();
    </script>

</body>

</html>