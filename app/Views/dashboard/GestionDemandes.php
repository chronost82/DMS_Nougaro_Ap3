<?php $this->extend('layout') ?>

<?= $this->section('meta') ?>
<link rel="stylesheet" href="<?= base_url('css/gestiondemandes.css') ?>">
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
                        <th>Heure</th>
                        <th>Téléphone</th>
                        <th>Marque</th>
                        <th>Modèle</th>
                        <!-- Pour afficher/masquer cette colonne selon le statut, utilisez data-visible-for sur le TH. -->
                        <th data-visible-for="validee">Immatriculation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($clients) && is_iterable($clients)) : ?>
                        <?php foreach ($clients as $c):
                            // Statut de la ligne
                            $statut = $mapStatus[$c['etat']] ?? 'en-attente';
                            $badgeClass = $statusBadgeClass[$statut] ?? '';
                            $label = $statusLabels[$statut] ?? '';
                            $email = $c['email'];
                        ?>
                            <tr data-status="<?= esc($statut) ?>"
                                data-id="<?= esc($c['id']) ?>"
                                data-nom="<?= esc($c['nom']) ?>"
                                data-prenom="<?= esc($c['prenom']) ?>"
                                data-email="<?= esc($c['email']) ?>"
                                data-telephone="<?= esc($c['telephone']) ?>"
                                data-marque="<?= esc($c['marque']) ?>"
                                data-modele="<?= esc($c['modele']) ?>"
                                data-immatriculation="<?= esc($c['immatriculation']) ?? "" ?>"
                                data-heure="<?= esc($c['heure']) ?>">
                                <td><?= esc($c['nom']) ?></td>
                                <td><?= esc($c['prenom']) ?></td>
                                <td>
                                    <span class="email-cell">
                                        <span class="email-text"><?= esc($email) ?></span>
                                        <span class="badge <?= esc($badgeClass) ?>"><?= esc($label) ?></span>
                                    </span>
                                </td>
                                <td><?= $valOrPlaceholder($c['heure'] ?? null) ?></td>
                                <td><?= $valOrPlaceholder($c['telephone'] ?? null) ?></td>
                                <td><?= $valOrPlaceholder($c['marque'] ?? null) ?></td>
                                <td><?= $valOrPlaceholder($c['modele'] ?? null) ?></td>
                                <td data-visible-for="validee"><?= $valOrPlaceholder($c['immatriculation'] ?? null) ?></td>
                                <td class="actions">
                                    <div class="action-buttons">
                                        <div class="action-set" data-actions-for="en-attente" style="display:none">
                                            <button type="button" class="btn-small btn-edit" title="Modifier" data-role="edit">Modifier</button>
                                            <form method="post" action="<?= site_url('demandes/valider') ?>" style="display:inline">
                                                <input type="hidden" name="id" value="<?= esc($c['id']) ?>">
                                                <button type="submit" class="btn" title="Valider">Valider</button>
                                            </form>
                                        </div>
                                        <div class="action-set" data-actions-for="validee" style="display:none">
                                            <a href="#" target="_blank" rel="noopener noreferrer" class="btn">Faire CT</a>
                                            <a href="" class="btn">Supprimer</a>
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

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Éléments principaux
        const radios = document.querySelectorAll('input[name="statusFilter"]');
        const table = document.getElementById('contactsTable');
        const rows = table ? table.querySelectorAll('tbody tr') : [];
        const headerCells = table ? table.querySelectorAll('thead th[data-visible-for]') : [];
        const counter = document.getElementById('counter');

        // Aide: colonnes visibles selon le statut
        function parseVisibleFor(value) {
            if (!value) return new Set();
            return new Set(String(value).split(',').map(s => s.trim()).filter(Boolean));
        }

        function headerVisible(selected, visibleStatuses, visibleFor) {
            if (!visibleFor || visibleFor.size === 0) return true;
            if (visibleFor.has('*') || visibleFor.has('tous')) return true;
            if (selected === 'tous') {
                for (const st of visibleFor)
                    if (visibleStatuses.has(st)) return true;
                return false;
            }
            return visibleFor.has(selected);
        }

        // Applique le filtre
        function applyFilter() {
            const selected = (document.querySelector('input[name="statusFilter"]:checked')?.dataset.status) || 'tous';
            let visibleCount = 0;
            const visibleStatuses = new Set();

            rows.forEach(row => {
                const status = row.getAttribute('data-status');
                const show = (selected === 'tous') || (status === selected);
                row.style.display = show ? '' : 'none';

                // Boutons d'action selon le statut
                row.querySelectorAll('.action-set').forEach(set => {
                    const target = set.getAttribute('data-actions-for');
                    const showSet = (selected === 'tous' && target === status) || (selected !== 'tous' && target === selected);
                    set.style.display = showSet ? '' : 'none';
                });

                if (show) {
                    visibleCount++;
                    visibleStatuses.add(status);
                }
            });

            // Colonnes qui dépendent du statut (entête + lignes)
            headerCells.forEach(th => {
                const vfor = parseVisibleFor(th.getAttribute('data-visible-for'));
                const mustShow = headerVisible(selected, visibleStatuses, vfor);
                th.style.display = mustShow ? '' : 'none';
                const colIdx = th.cellIndex;
                rows.forEach(r => {
                    if (r.cells && r.cells.length > colIdx) r.cells[colIdx].style.display = mustShow ? '' : 'none';
                });
            });

            if (counter) counter.textContent = `${visibleCount} ${visibleCount > 1 ? 'éléments' : 'élément'}`;
        }

        radios.forEach(r => r.addEventListener('change', applyFilter));
        applyFilter();

        // Fenêtre de modification (modal)
        const modal = document.getElementById('editModal');
        const form = document.getElementById('editForm');
        const fields = {
            id: document.getElementById('f-id'),
            nom: document.getElementById('f-nom'),
            prenom: document.getElementById('f-prenom'),
            email: document.getElementById('f-email'),
            telephone: document.getElementById('f-telephone'),
            marque: document.getElementById('f-marque'),
            modele: document.getElementById('f-modele'),
            immatriculation: document.getElementById('f-immatriculation'),
            heure: document.getElementById('f-heure')
        };

        function openModal(tr) {
            if (!tr) return;
            const map = {
                id: 'id',
                nom: 'nom',
                prenom: 'prenom',
                email: 'email',
                telephone: 'telephone',
                marque: 'marque',
                modele: 'modele',
                immatriculation: 'immatriculation',
                heure: 'heure'
            };
            for (const [field, key] of Object.entries(map)) {
                if (fields[field]) fields[field].value = tr.dataset[key] || '';
            }
            modal.removeAttribute('hidden');
            modal.classList.add('open');
            fields.nom?.focus();
        }

        function closeModal() {
            modal.classList.remove('open');
            modal.setAttribute('hidden', 'hidden');
        }

        document.addEventListener('click', (e) => {
            const editBtn = e.target.closest('.btn-edit');
            if (editBtn) openModal(editBtn.closest('tr'));
            if (e.target.matches('[data-close-modal]') || e.target === modal) closeModal();
        });
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape' && modal.classList.contains('open')) closeModal();
        });
    });
</script>

<!-- Modal d'édition -->
<div class="modal-overlay" id="editModal" hidden aria-modal="true" role="dialog" aria-labelledby="editModalTitle">
    <div class="modal-window">
        <div class="modal-header">
            <h2 id="editModalTitle">Modifier la demande</h2>
            <button type="button" class="modal-close" data-close-modal aria-label="Fermer">×</button>
        </div>
        <form method="post" action="<?= site_url('demandes/modifier') ?>" id="editForm" class="modal-body">
            <input type="hidden" name="id" id="f-id">
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
                    <input type="text" name="telephone" id="f-telephone">
                </label>
            </div>
            <div class="grid-3">
                <label>Marque
                    <input type="text" name="marque" id="f-marque">
                </label>
                <label>Modèle
                    <input type="text" name="modele" id="f-modele">
                </label>
                <label>Immatriculation
                    <input type="text" name="immatriculation" id="f-immatriculation">
                </label>
            </div>
            <div>
                <label>Heure
                    <input type="text" name="heure" id="f-heure" placeholder="HH:MM">
                </label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn-secondary" data-close-modal>Annuler</button>
                <button type="submit" class="btn-primary">Enregistrer</button>
            </div>
        </form>
    </div>
</div>
<?= $this->endSection() ?>