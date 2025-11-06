document.addEventListener('DOMContentLoaded', () => {
    // Sélection des éléments UI principaux
    const statusRadios = document.querySelectorAll('input[name="statusFilter"]');
    const table = document.getElementById('contactsTable');
    const tableRows = table ? table.querySelectorAll('tbody tr') : [];
    const headerCells = table ? table.querySelectorAll('thead th[data-visible-for]') : [];
    const counter = document.getElementById('counter');

    // Aide: colonnes visibles selon le statut
    /**
     * Convertit l'attribut data-visible-for en Set de statuts
     */
    function parseVisibleFor(value) {
        if (!value) return new Set();
        return new Set(String(value).split(',').map(s => s.trim()).filter(Boolean));
    }

    /**
     * Détermine si une colonne d'en-tête doit être visible
     */
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

    /**
     * Retourne le statut sélectionné dans les radios
     */
    function getSelectedStatus() {
        return (document.querySelector('input[name="statusFilter"]:checked')?.dataset.status) || 'tous';
    }

    /**
     * Applique le filtre d'affichage des lignes + colonnes dépendant du statut
     */
    function applyFilter() {
        const selected = getSelectedStatus();
        let visibleCount = 0;
        const visibleStatuses = new Set();

        tableRows.forEach(row => {
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
            tableRows.forEach(r => {
                if (r.cells && r.cells.length > colIdx) r.cells[colIdx].style.display = mustShow ? '' : 'none';
            });
        });

        if (counter) counter.textContent = `${visibleCount} ${visibleCount > 1 ? 'éléments' : 'élément'}`;
    }

    statusRadios.forEach(r => r.addEventListener('change', applyFilter));
    applyFilter();

    // Fenêtre de modification (modal)
    const modal = document.getElementById('editModal');
    const fields = {
        id: document.getElementById('f-id'),
        idType: document.getElementById('f-id-type'),
        idDemande: document.getElementById('f-id-demande'),
        nom: document.getElementById('f-nom'),
        prenom: document.getElementById('f-prenom'),
        email: document.getElementById('f-email'),
        telephone: document.getElementById('f-telephone'),
        marque: document.getElementById('f-marque'),
        modele: document.getElementById('f-modele'),
        immatriculation: document.getElementById('f-immatriculation'),
        annee: document.getElementById('f-annee'),
        chassis: document.getElementById('f-chassis'),
        date: document.getElementById('f-date'),
        heure: document.getElementById('f-heure')
    };

    // Construit une map Marque -> Set(Modèles) depuis les données backend
    /**
     * Construit la map Marque -> Set(Modèles) à partir des données backend (vehicules-data)
     */
    function buildMarqueModeleMap() {
        const map = Object.create(null);
        let vehicules = [];
        const vEl = document.getElementById('vehicules-data');
        if (vEl) {
            try { vehicules = JSON.parse(vEl.textContent || '[]'); } catch (e) { vehicules = []; }
        }
        for (let i = 0; i < vehicules.length; i++) {
            const item = vehicules[i] || {};
            const m = (item.MARQUE || item.marque || '').trim();
            const mod = (item.MODELE || item.modele || '').trim();
            if (!m) continue;
            if (!map[m]) map[m] = new Set();
            if (mod) map[m].add(mod);
        }
        return map;
    }

    /**
     * Réinitialise un select avec un placeholder désactivé
     */
    function resetSelectWithPlaceholder(select, placeholderText) {
        if (!select) return;
        select.innerHTML = '';
        const opt = document.createElement('option');
        opt.value = '';
        opt.disabled = true;
        opt.selected = true;
        opt.textContent = placeholderText;
        select.appendChild(opt);
    }

    /**
     * Remplit le select des marques à partir des données backend (marques-data)
     */
    function fillMarquesSelect(map, selectMarque, currentValue) {
        if (!selectMarque) return;
        resetSelectWithPlaceholder(selectMarque, 'Marque');
        // Récupère la liste de marques depuis le backend en priorité
        let marquesList = [];
        const mEl = document.getElementById('marques-data');
        if (mEl) {
            try { marquesList = JSON.parse(mEl.textContent || '[]'); } catch (e) { marquesList = []; }
        }
        // Si le backend ne fournit pas, fallback sur les clés de la map
        let marques = [];
        if (Array.isArray(marquesList) && marquesList.length > 0) {
            const seen = Object.create(null);
            for (let i = 0; i < marquesList.length; i++) {
                const m = (marquesList[i].MARQUE || marquesList[i].marque || '').trim();
                if (!m || seen[m]) continue;
                seen[m] = true;
                marques.push(m);
            }
            marques.sort((a, b) => a.localeCompare(b));
        } else {
            marques = Object.keys(map).sort((a, b) => a.localeCompare(b));
        }
        for (let i = 0; i < marques.length; i++) {
            const m = marques[i];
            const opt = document.createElement('option');
            opt.value = m;
            opt.textContent = m;
            selectMarque.appendChild(opt);
        }
        if (currentValue && marques.includes(currentValue)) {
            selectMarque.value = currentValue;
        }
    }

    /**
     * Remplit le select des modèles pour une marque donnée
     */
    function fillModelesSelect(map, selectModele, marque, currentValue) {
        if (!selectModele) return;
        resetSelectWithPlaceholder(selectModele, 'Modèle');
        if (!marque || !map[marque] || map[marque].size === 0) {
            selectModele.disabled = true;
            return;
        }
        const list = Array.from(map[marque]).sort((a, b) => a.localeCompare(b));
        for (let i = 0; i < list.length; i++) {
            const mod = list[i];
            const opt = document.createElement('option');
            opt.value = mod;
            opt.textContent = mod;
            selectModele.appendChild(opt);
        }
        selectModele.disabled = selectModele.options.length <= 1;
        if (currentValue && list.includes(currentValue)) {
            selectModele.value = currentValue;
        }
    }

    /**
     * Ouvre le modal d'édition et hydrate les champs
     */
    function openModal(tr) {
        if (!tr) return;
        const map = {
            nom: 'nom',
            prenom: 'prenom',
            email: 'email',
            telephone: 'telephone',
            marque: 'marque',
            modele: 'modele',
            immatriculation: 'immatriculation',
            annee: 'annee',
            chassis: 'chassis',
            date: 'date',
            heure: 'heure'
        };
        // Choix de l'identifiant selon le radio sélectionné
        const selectedFilter = getSelectedStatus();
        let idValue = '';
        let idType = '';
        if (selectedFilter === 'en-attente') {
            idValue = tr.dataset.iddemande || '';
            idType = 'demande';
        } else if (selectedFilter === 'validee') {
            idValue = tr.dataset.idclient || '';
            idType = 'client';
        } else {
            // fallback: selon le statut de la ligne
            const status = tr.getAttribute('data-status');
            if (status === 'en-attente') {
                idValue = tr.dataset.iddemande || '';
                idType = 'demande';
            } else if (status === 'validee') {
                idValue = tr.dataset.idclient || '';
                idType = 'client';
            } else {
                idValue = tr.dataset.idclient || tr.dataset.iddemande || '';
                idType = tr.dataset.idclient ? 'client' : (tr.dataset.iddemande ? 'demande' : '');
            }
        }
        if (fields.id) fields.id.value = idValue;
        if (fields.idType) fields.idType.value = idType;
        if (fields.idDemande) fields.idDemande.value = tr.dataset.iddemande || '';
        for (const [field, key] of Object.entries(map)) {
            if (fields[field]) fields[field].value = tr.dataset[key] || '';
        }

        // Prépare les listes Marque/Modèle au moment d'ouvrir le modal
        const mm = buildMarqueModeleMap();
        const currentMarque = (tr.dataset.marque || '').trim();
        const currentModele = (tr.dataset.modele || '').trim();
        fillMarquesSelect(mm, fields.marque, currentMarque);
        // Rebuild modèles à chaque changement de marque
        if (fields.marque) {
            fields.marque.onchange = function () {
                fillModelesSelect(mm, fields.modele, fields.marque.value, '');
            };
        }
        fillModelesSelect(mm, fields.modele, fields.marque ? fields.marque.value : '', currentModele);

        modal.removeAttribute('hidden');
        modal.classList.add('open');
        fields.nom?.focus();
    }

    function closeModal() {
        modal.classList.remove('open');
        modal.setAttribute('hidden', 'hidden');
    }

    document.addEventListener('click', (e) => {
        const editBtn = e.target.closest('[data-role="edit"]');
        if (editBtn) openModal(editBtn.closest('tr'));
        if (e.target.matches('[data-close-modal]') || e.target === modal) closeModal();
    });
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modal.classList.contains('open')) closeModal();
    });
    // Le remplissage des listes Marque/Modèle est réalisé au moment d'ouvrir le modal.
});