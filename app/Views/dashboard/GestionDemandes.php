<?php $this->extend('layout') ?>

<?= $this->section('meta') ?>
<link rel="stylesheet" href="<?= base_url('css/gestiondemandes.css') ?>">
<script src="<?= base_url('js/gestiondemandes.js') ?>" defer></script>
<?php $this->endSection() ?>

<?php
// Convertit $status au format utilisé par la page
$mapStatus = [
    'attente'  => 'en-attente',
    'validee'  => 'validee',
    'terminee' => 'terminee',
    'tous'     => 'tous',
];
$status = $mapStatus[$status ?? 'tous'] ?? 'tous';

// Noms affichés et classes de badge
$statusLabels = [
    'en-attente' => 'En attente',
    'validee'     => 'Validé',
    'terminee'    => 'Terminé',
];

$statusBadgeClass = [
    'en-attente' => 'status-attente',
    'validee'     => 'status-valide',
    'terminee'    => 'status-termine',
];
// Affiche la valeur ou "Pas de donnée" en italique
$valOrPlaceholder = static function ($v) {
    return !empty($v) ? esc($v) : '<em class="muted">Pas de donnée</em>';
};
?>

<?= $this->section('content') ?>
<div class="container">
    <div class="card">
        <?php
        $successMsg = session()->getFlashdata('success');
        $errorMsg = session()->getFlashdata('error');
        if ($successMsg || $errorMsg):
        ?>
            <div style="display:flex; justify-content:flex-end; margin: 8px 0;">
                <div class="alert <?= $successMsg ? 'alert-success' : 'alert-error' ?>" style="margin-right: 8px;" role="alert" aria-live="polite">
                    <?= esc($successMsg ?: $errorMsg) ?>
                </div>
            </div>
        <?php endif; ?>
        <div class="header">
            <div>
                <h1>Dashboard</h1>
                <p class="muted">Gestion des demandes</p>
            </div>
            <div class="filters" role="radiogroup" aria-label="Filtrer par statut (un seul à la fois)">
                <span class="muted" aria-hidden="true">Filtrer</span>
                <label class="check"><input type="radio" name="statusFilter" data-status="tous" <?= $status === 'tous' ? 'checked' : '' ?>> <span>Tous</span></label>
                <label class="check"><input type="radio" name="statusFilter" data-status="en-attente" <?= $status === 'en-attente' ? 'checked' : '' ?>> <span>En attente</span></label>
                <label class="check"><input type="radio" name="statusFilter" data-status="validee" <?= $status === 'validee' ? 'checked' : '' ?>> <span>Validé</span></label>
                <label class="check"><input type="radio" name="statusFilter" data-status="terminee" <?= $status === 'terminee' ? 'checked' : '' ?>> <span>Terminé</span></label>
            </div>
        </div>

        <div class="table-wrap">
            <table id="contactsTable">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <!-- Pour afficher/masquer cette colonne selon le statut, utilisez data-visible-for sur le TH. -->
                        <th data-visible-for="validee">Immatriculation</th>
                        <th data-visible-for="en-attente">Date demande</th>
                        <th data-visible-for="validee">Date</th>
                        <th data-visible-for="validee">Heure</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($clients) && is_iterable($clients)) : ?>
                        <?php foreach ($clients as $c):
                            // Statut de la ligne
                            $statut = $mapStatus[$c['ETAT']] ?? 'en-attente';
                            $badgeClass = $statusBadgeClass[$statut] ?? '';
                            $label = $statusLabels[$statut] ?? '';
                            $email = $c['EMAIL'];
                        ?>
                            <tr data-status="<?= esc($statut) ?>"
                                data-iddemande="<?= esc($c['IDDEMANDE'] ?? '') ?>"
                                data-idclient="<?= esc($c['IDCLIENT'] ?? '') ?>"
                                data-nom="<?= esc($c['NOM']) ?>"
                                data-prenom="<?= esc($c['PRENOM']) ?>"
                                data-email="<?= esc($c['EMAIL']) ?>"
                                data-telephone="<?= esc($c['TEL']) ?>"
                                data-marque="<?= esc($c['MARQUE']) ?>"
                                data-modele="<?= esc($c['MODELE']) ?>"
                                data-immatriculation="<?= esc($c['IMMATRICULATION'] ?? "") ?>"
                                data-datedemande="<?= esc($c['DATEDEMANDE'] ?? "") ?>"
                                data-date="<?= esc($c['DATE'] ?? "") ?>"
                                data-heure="<?= esc($c['HEURE'] ?? "") ?>">
                                <td><?= esc($c['NOM']) ?></td>
                                <td><?= esc($c['PRENOM']) ?></td>
                                <td>
                                    <span class="email-cell">
                                        <span class="email-text"><?= esc($email) ?></span>
                                        <span class="badge <?= esc($badgeClass) ?>"><?= esc($label) ?></span>
                                    </span>
                                </td>
                                <td><?= $valOrPlaceholder($c['TEL'] ?? null) ?></td>
                                <td><?= $valOrPlaceholder($c['MARQUE'] ?? null) ?></td>
                                <td><?= $valOrPlaceholder($c['MODELE'] ?? null) ?></td>
                                <td data-visible-for="validee"><?= $valOrPlaceholder($c['IMMATRICULATION'] ?? null) ?></td>
                                <td data-visible-for="en-attente"><?= $valOrPlaceholder($c['DATEDEMANDE'] ?? null) ?></td>
                                <td data-visible-for="validee"><?= $valOrPlaceholder($c['DATE'] ?? null) ?></td>
                                <td><?= $valOrPlaceholder($c['HEURE'] ?? null) ?></td>
                                <td class="actions">
                                    <div class="action-buttons">
                                        <div class="action-set" data-actions-for="en-attente" style="display:none">
                                            <button type="button" data-role="edit" class="btn-primary" title="Modifier" data-role="edit">Modifier</button>
                                            <form method="get" action="<?= url_to('admin-suppr-demande-en-attente', $c['IDDEMANDE']) ?>" style="display:inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ?');">
                                                <?= csrf_field() ?>
                                                <input type="hidden" name="id" value="<?= esc($c['IDDEMANDE'] ?? '') ?>">
                                                <button type="submit" class="btn-secondary" title="Supprimer">Supprimer</button>
                                            </form>
                                        </div>
                                        <div class="action-set" data-actions-for="validee" style="display:none">
                                            <a href="" rel="noopener noreferrer" class="btn-primary">Faire CT</a>
                                            <a href="" class="btn-secondary">Supprimer</a>
                                        </div>
                                        <div class="action-set" data-actions-for="terminee" style="display:none">
                                            <!-- Actions pour terminée -->
                                        </div>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" class="muted" style="text-align:center;">Aucun enregistrement</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="footer" id="counter"></div>
    </div>
</div>

<div class="modal-overlay" id="editModal" hidden aria-modal="true" role="dialog" aria-labelledby="editModalTitle">
    <div class="modal-window">
        <div class="modal-header">
            <h2 id="editModalTitle">Modifier la demande</h2>
            <button type="button" class="modal-close" data-close-modal aria-label="Fermer">×</button>
        </div>
        <form method="post" action="<?= url_to('admin-demande-en-attente-modif') ?>" id="editForm" class="modal-body">
            <?= csrf_field() ?>
            <input type="hidden" name="id" id="f-id">
            <input type="hidden" name="id_type" id="f-id-type">
            <input type="hidden" name="iddemande" id="f-id-demande">
            <div class="grid-2">
                <label>Nom
                    <input type="text" name="nom" id="f-nom" required>
                </label>
                <label>Prénom
                    <input type="text" name="prenom" id="f-prenom" required>
                </label>
            </div>
            <div class="grid-2">
                <label>Email
                    <input type="email" name="email" id="f-email" required>
                </label>
                <label>Téléphone
                    <input type="text" name="telephone" id="f-telephone" required>
                </label>
            </div>
            <div class="grid-3">
                <label>Marque
                    <select name="marque" id="f-marque" required="required">
                        <option value="" disabled selected>Marque</option>
                    </select>
                </label>
                <label>Modèle
                    <select name="modele" id="f-modele" required="required">
                        <option value="" disabled selected>Modèle</option>
                    </select>
                </label>
                <label>Immatriculation
                    <input type="text" name="immatriculation" id="f-immatriculation"
                        placeholder="AA-123-AA ou 1234 ABC 56"
                        title="SIV: AA-123-AA — Ancien: 2-4 chiffres, 2-3 lettres, 2 chiffres (département)"
                        oninput="this.value = this.value.toUpperCase()" maxlength="11">
                </label>
            </div>
            <div class="grid-2">
                <label>Année
                    <input type="number" name="annee" id="f-annee" placeholder="0000" min="1900" max="2100">
                </label>
                <label>Numéro chassis
                    <input type="text" name="chassis" id="f-chassis" placeholder="17 caractères"
                        pattern="^[A-HJ-NPR-Z0-9]{17}$" minlength="17" maxlength="17"
                        title="17 caractères alphanumériques sans I, O, Q"
                        oninput="this.value = this.value.toUpperCase()">
                </label>
            </div>
            <div class="grid-1 ct-field">
                <label for="f-heure">Date et heure du contrôle technique</label>
                <div id="ct-scheduler" class="ct-scheduler" aria-label="Calendrier de rendez-vous CT" data-step="30" data-api-ct="<?= site_url('api/ct/availability') ?>"></div>
                <!-- Champs utilisés pour la soumission, alimentés par le calendrier -->
                <input type="hidden" name="date" id="f-date">
                <input type="hidden" name="heure" id="f-heure">
                <small class="muted">Créneaux 08:00–18:00, au plus 2 par créneau, hors week-ends.</small>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" data-close-modal>Annuler</button>
                <button type="submit" class="btn-primary">Valider</button>
            </div>
        </form>
    </div>
</div>

<script id="marques-data" type="application/json">
    <?= json_encode($marques ?? [], JSON_UNESCAPED_UNICODE) ?>
</script>
<script id="vehicules-data" type="application/json">
    <?= json_encode($vehicules ?? [], JSON_UNESCAPED_UNICODE) ?>
</script>
<?= $this->endSection() ?>