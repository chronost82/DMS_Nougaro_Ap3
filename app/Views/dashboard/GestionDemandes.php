<?php $this->extend('layout') ?>

<?= $this->section('meta') ?>
<link rel="stylesheet" href="<?= base_url('css/gestiondemandes.css') ?>">
<?php $this->endSection() ?>

<?php
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
                <label class="check"><input type="radio" name="statusFilter" data-status="tous" <?= $status==='tous' ? 'checked' : '' ?>> <span>Tous</span></label>
                <label class="check"><input type="radio" name="statusFilter" data-status="en-attente" <?= $status==='attente' ? 'checked' : '' ?>> <span>En attente</span></label>
                <label class="check"><input type="radio" name="statusFilter" data-status="validee" <?= $status==='validee' ? 'checked' : '' ?>> <span>Validé</span></label>
                <label class="check"><input type="radio" name="statusFilter" data-status="terminee" <?= $status==='terminee' ? 'checked' : '' ?>> <span>Terminé</span></label>
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
                        <th>Immatriculation</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($clients) && is_iterable($clients)) : ?>
                        <?php foreach ($clients as $c):
                            switch ($c['etat']) {
                                case 'attente':
                                    $statut = 'en-attente';
                                    break;
                                case 'validee':
                                    $statut = 'validee';
                                    break;
                                case 'terminee':
                                    $statut = 'terminee';
                                    break;
                                default:
                                    $statut = 'en-attente';
                            }
                            $badgeClass = $statusBadgeClass[$statut];
                            $label = $statusLabels[$statut];
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
                                data-immatriculation="<?= esc($c['immatriculation']) ?>"
                                data-heure="<?= esc($c['heure']) ?>"
                            >
                                <td><?= $c['nom'] ?></td>
                                <td><?= $c['prenom'] ?></td>
                                <td>
                                    <span class="email-cell">
                                        <span class="email-text"><?= esc($email) ?></span>
                                        <span class="badge <?= esc($badgeClass) ?>"><?= esc($label) ?></span>
                                    </span>
                                </td>
                                <td><?= $c['heure'] ?></td>
                                <td><?= $c['telephone'] ?></td>
                                <td><?= $c['marque'] ?></td>
                                <td><?= $c['modele'] ?></td>
                                <td><?= $c['immatriculation'] ?></td>
                                <td class="actions">
                                    <div class="action-buttons">
                                        <?php if ($statut === 'en-attente'): ?>
                                            <button type="button" class="btn-small btn-edit" title="Modifier" data-role="edit">Modifier</button>
                                            <form method="post" action="<?= site_url('demandes/valider') ?>" style="display:inline">
                                                <input type="hidden" name="id" value="<?= esc($c['id']) ?>">
                                                <button type="submit" class="btn-small" title="Valider">Valider</button>
                                            </form>
                                        <?php endif; ?>
                                        <?php if ($statut !== 'terminee'): ?>
                                            <form method="post" action="<?= site_url('demandes/terminer') ?>" style="display:inline">
                                                <input type="hidden" name="id" value="<?= esc($c['id']) ?>">
                                                <button type="submit" class="btn-small" title="Terminer">Terminer</button>
                                            </form>
                                        <?php endif; ?>
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
    // Filtrage + modal édition
    document.addEventListener('DOMContentLoaded', function() {
        const radios = document.querySelectorAll('input[name="statusFilter"]');
        const rows = document.querySelectorAll('#contactsTable tbody tr');
        const counter = document.getElementById('counter');

        function applyFilter() {
            const selected = document.querySelector('input[name="statusFilter"]:checked').dataset.status;
            let visible = 0;
            rows.forEach(r => {
                const status = r.getAttribute('data-status');
                const show = (selected === 'tous') || (status === selected);
                r.style.display = show ? '' : 'none';
                if (show) visible++;
            });
            counter.textContent = visible + (visible > 1 ? ' éléments' : ' élément');
        }

        radios.forEach(r => r.addEventListener('change', applyFilter));
        applyFilter();

        // Modal
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

        function openModal(tr){
            if (!tr) return;
            fields.id.value = tr.dataset.id || '';
            fields.nom.value = tr.dataset.nom || '';
            fields.prenom.value = tr.dataset.prenom || '';
            fields.email.value = tr.dataset.email || '';
            fields.telephone.value = tr.dataset.telephone || '';
            fields.marque.value = tr.dataset.marque || '';
            fields.modele.value = tr.dataset.modele || '';
            fields.immatriculation.value = tr.dataset.immatriculation || '';
            fields.heure.value = tr.dataset.heure || '';
            modal.removeAttribute('hidden');
            modal.classList.add('open');
            fields.nom.focus();
        }
        function closeModal(){
            modal.classList.remove('open');
            modal.setAttribute('hidden','hidden');
        }
        document.addEventListener('click', e => {
            if (e.target.closest('.btn-edit')) {
                openModal(e.target.closest('tr'));
            }
            if (e.target.matches('[data-close-modal]')) {
                closeModal();
            }
            if (e.target === modal) closeModal();
        });
        document.addEventListener('keydown', e => { if (e.key === 'Escape' && modal.classList.contains('open')) closeModal(); });
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