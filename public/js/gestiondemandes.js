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

        // Gestion globale de data-actions-for en dehors du tableau
        document.querySelectorAll('[data-actions-for]').forEach(el => {
            // Skip éléments déjà traités dans le tableau
            if (el.closest('tbody')) return;
            const target = el.getAttribute('data-actions-for');
            const shouldShow = (selected === 'tous') || (selected === target);
            el.style.display = shouldShow ? '' : 'none';
        });

        if (counter) counter.textContent = `${visibleCount} ${visibleCount > 1 ? 'éléments' : 'élément'}`;
    }

    statusRadios.forEach(r => r.addEventListener('change', applyFilter));
    applyFilter();

    // Helper pour récupérer les disponibilités côté backend (table CT)
    async function fetchAvailability(endpoint, year, month) {
        const url = `${endpoint}?year=${year}&month=${month}`;
        const res = await fetch(url, { headers: { 'Accept': 'application/json' } });
        if (!res.ok) throw new Error(`HTTP ${res.status}`);
        const json = await res.json();
        // Convertit en Map(date -> Map(time -> count))
        const map = new Map();
        Object.keys(json || {}).forEach(d => {
            const inner = new Map();
            const obj = json[d] || {};
            Object.keys(obj).forEach(h => inner.set(h, obj[h] || 0));
            map.set(d, inner);
        });
        return map;
    }

    // Génère la liste des créneaux 30 min entre 08:00 et 18:00
    function generateTimeSlots() {
        const slots = [];
        let hour = 8, minute = 0;
        while (hour < 18 || (hour === 18 && minute === 0)) {
            const hh = String(hour).padStart(2, '0');
            const mm = String(minute).padStart(2, '0');
            slots.push(`${hh}:${mm}`);
            minute += 30;
            if (minute >= 60) { minute = 0; hour += 1; }
            if (hour === 18 && minute > 0) break; // dernier slot 18:00
        }
        return slots;
    }

    // Rendu du calendrier et de la liste des créneaux
    function renderCtScheduler(container, options) {
        if (!container) return;
        const slots = generateTimeSlots();
        const maxPerSlot = 2;
        const endpoint = container.dataset.apiCt || '';

        const state = {
            viewDate: options?.initialDate ? new Date(options.initialDate) : new Date(),
            selectedDate: options?.initialDate || '', // YYYY-MM-DD
            selectedTime: options?.initialTime || '', // HH:MM
            originalDate: options?.initialDate || '',
            originalTime: options?.initialTime || ''
        };

        container.innerHTML = '';
        container.classList.add('scheduler');

        const header = document.createElement('div');
        header.className = 'scheduler-header';
        const prevBtn = document.createElement('button'); prevBtn.type = 'button'; prevBtn.className = 'btn'; prevBtn.textContent = '◀';
        const nextBtn = document.createElement('button'); nextBtn.type = 'button'; nextBtn.className = 'btn'; nextBtn.textContent = '▶';
        const title = document.createElement('div'); title.className = 'scheduler-title';
        header.append(prevBtn, title, nextBtn);

        const body = document.createElement('div');
        body.className = 'scheduler-body';
        const grid = document.createElement('div'); grid.className = 'scheduler-grid';
        const slotsWrap = document.createElement('div'); slotsWrap.className = 'scheduler-slots';

        container.append(header, body);
        body.append(grid, slotsWrap);

        function fmtDateYMD(d) {
            const y = d.getFullYear();
            const m = String(d.getMonth() + 1).padStart(2, '0');
            const day = String(d.getDate()).padStart(2, '0');
            return `${y}-${m}-${day}`;
        }

        let bookedMap = new Map();

        async function renderMonth() {
            const y = state.viewDate.getFullYear();
            const m = state.viewDate.getMonth();
            title.textContent = state.viewDate.toLocaleDateString(undefined, { month: 'long', year: 'numeric' });
            grid.innerHTML = '';
            const today = new Date();
            today.setHours(0, 0, 0, 0);

            // Charger les dispos depuis l'API
            try {
                if (endpoint) {
                    bookedMap = await fetchAvailability(endpoint, y, m + 1);
                } else {
                    bookedMap = new Map();
                }
            } catch (e) {
                bookedMap = new Map();
                console.error('Erreur chargement dispos CT:', e);
            }

            // En-têtes des jours (Lun-Dim)
            const dayNames = ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'];
            const headerRow = document.createElement('div'); headerRow.className = 'scheduler-week';
            dayNames.forEach(n => { const s = document.createElement('div'); s.className = 'scheduler-dow'; s.textContent = n; headerRow.appendChild(s); });
            grid.appendChild(headerRow);

            const first = new Date(y, m, 1);
            const last = new Date(y, m + 1, 0);
            // Positionner le premier jour sur Lundi=0
            let startOffset = (first.getDay() + 6) % 7; // JS: Dim=0..Sam=6 → Lun=0
            const totalDays = last.getDate();
            let day = 1 - startOffset;
            for (let row = 0; row < 6; row++) {
                const week = document.createElement('div'); week.className = 'scheduler-week';
                for (let col = 0; col < 7; col++) {
                    const cell = document.createElement('button'); cell.type = 'button'; cell.className = 'scheduler-day';
                    const dateObj = new Date(y, m, day);
                    const inMonth = dateObj.getMonth() === m;
                    if (!inMonth) cell.classList.add('muted');
                    const label = document.createElement('span'); label.textContent = String(dateObj.getDate());
                    cell.appendChild(label);
                    if (inMonth) {
                        const ymd = fmtDateYMD(dateObj);
                        cell.dataset.date = ymd;
                        if (state.selectedDate === ymd) cell.classList.add('selected');
                        const isWeekend = [0,6].includes(dateObj.getDay()); // 0=Dim, 6=Sam
                        const isPast = dateObj < today;
                        const isOriginalDate = state.originalDate === ymd;
                        const shouldDisable = (isWeekend || isPast) && !isOriginalDate;
                        if (shouldDisable) {
                            cell.disabled = true;
                            cell.title = isWeekend ? 'Week-end indisponible' : 'Date passée indisponible';
                        } else {
                            cell.addEventListener('click', () => {
                                state.selectedDate = ymd;
                                state.selectedTime = state.selectedDate === state.originalDate ? state.selectedTime : '';
                                updateSelectionUI();
                            });
                        }
                    } else {
                        cell.disabled = true;
                    }
                    week.appendChild(cell);
                    day++;
                }
                grid.appendChild(week);
            }
            renderSlots();
        }

        function renderSlots() {
            slotsWrap.innerHTML = '';
            const info = document.createElement('div'); info.className = 'muted';
            info.textContent = state.selectedDate ? `Créneaux pour le ${state.selectedDate}` : 'Choisissez un jour';
            slotsWrap.appendChild(info);
            if (!state.selectedDate) return;

            const list = document.createElement('div'); list.className = 'slots-list';
            const perDate = bookedMap.get(state.selectedDate) || new Map();
            slots.forEach(t => {
                const count = perDate.get(t) || 0;
                const isOriginal = (state.originalDate === state.selectedDate && state.originalTime === t);
                // Désactiver créneaux passés pour la date du jour
                let pastNow = false;
                if (state.selectedDate) {
                    const today = new Date();
                    const ymdToday = `${today.getFullYear()}-${String(today.getMonth()+1).padStart(2,'0')}-${String(today.getDate()).padStart(2,'0')}`;
                    if (state.selectedDate === ymdToday) {
                        const [hh, mm] = t.split(':').map(n => parseInt(n, 10));
                        const slotTime = new Date(today.getFullYear(), today.getMonth(), today.getDate(), hh, mm, 0, 0);
                        pastNow = slotTime < today;
                    }
                }
                const disabled = !isOriginal && (count >= maxPerSlot || pastNow);
                const btn = document.createElement('button');
                btn.type = 'button';
                btn.className = 'btn ' + (state.selectedTime === t ? 'btn-primary' : '');
                const isFull = count >= maxPerSlot;
                // Contenu: heure + badge
                const timeSpan = document.createElement('span');
                timeSpan.className = 'slot-time';
                timeSpan.textContent = t;
                btn.appendChild(timeSpan);
                if (count > 0) {
                    const badge = document.createElement('span');
                    badge.className = 'slot-badge' + (isFull ? ' full' : ' partial');
                    badge.textContent = isFull ? 'Complet' : `${Math.min(count, maxPerSlot)}/2`;
                    btn.appendChild(badge);
                }
                // ARIA label
                const ariaParts = [t];
                if (isFull) ariaParts.push('complet'); else if (count > 0) ariaParts.push(`${count} sur 2`);
                btn.setAttribute('aria-label', ariaParts.join(', '));
                btn.disabled = disabled;
                if (isFull) {
                    btn.classList.add('full');
                }
                if (count === 1) {
                    btn.classList.add('partial');
                }
                btn.addEventListener('click', () => {
                    state.selectedTime = t;
                    updateSelectionUI();
                });
                list.appendChild(btn);
            });
            slotsWrap.appendChild(list);
        }

        function updateSelectionUI() {
            // Dates
            container.querySelectorAll('.scheduler-day').forEach(b => {
                if (!b.dataset.date) return;
                b.classList.toggle('selected', b.dataset.date === state.selectedDate);
            });
            // Heures
            renderSlots();
            // Mise à jour des champs cachés
            const hiddenDate = document.getElementById('f-date');
            const hiddenTime = document.getElementById('f-heure');
            if (hiddenDate) hiddenDate.value = state.selectedDate || '';
            if (hiddenTime) hiddenTime.value = state.selectedTime || '';
        }

        prevBtn.addEventListener('click', () => { state.viewDate.setMonth(state.viewDate.getMonth() - 1); renderMonth(); });
        nextBtn.addEventListener('click', () => { state.viewDate.setMonth(state.viewDate.getMonth() + 1); renderMonth(); });
        renderMonth();

        // Pré-sélection
        if (state.selectedDate) updateSelectionUI();
    }

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

    // Auto-format immatriculation (SIV: AA-123-AA) ou ancien format (1234 AB 56)
    function formatImmat(value) {
        if (!value) return '';
        const raw = String(value).toUpperCase().replace(/[^A-Z0-9]/g, '');
        if (!raw) return '';

        // Si commence par une lettre, on privilégie SIV (AA-123-AA)
        if (/^[A-Z]/.test(raw)) {
            const a = raw.slice(0, 2);
            const b = raw.slice(2, 5);
            const c = raw.slice(5, 7);
            return [a, b, c].filter(Boolean).join('-');
        }

        // Ancien format: D{1..4} L{1..3} D{1..2}
        const aMatch = raw.match(/^\d{0,4}/);
        const a = aMatch ? aMatch[0] : '';
        const rest = raw.slice(a.length);
        const bMatch = rest.match(/^[A-Z]{0,3}/);
        const b = bMatch ? bMatch[0] : '';
        const rest2 = rest.slice(b.length);
        const cMatch = rest2.match(/^\d{0,2}/);
        const c = cMatch ? cMatch[0] : '';
        return [a, b, c].filter(Boolean).join(' ');
    }

    // Binder: auto-format pendant la saisie
    if (fields.immatriculation) {
        fields.immatriculation.addEventListener('input', (e) => {
            const caretEnd = e.target.selectionEnd; // caret handling minimal (optional)
            e.target.value = formatImmat(e.target.value);
            // Optionnel: replacer grossièrement le caret en fin
            try { e.target.setSelectionRange(e.target.value.length, e.target.value.length); } catch (_) {}
        });
    }

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

        // Formater l'immatriculation si présente
        if (fields.immatriculation && fields.immatriculation.value) {
            fields.immatriculation.value = formatImmat(fields.immatriculation.value);
        }

        // Initialiser le calendrier CT
        const schedEl = document.getElementById('ct-scheduler');
        const initialDate = (fields.date?.value || '').trim();
        const initialTime = (fields.heure?.value || '').trim();
        renderCtScheduler(schedEl, { initialDate, initialTime });

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

    // Validation avant soumission: s'assurer qu'un créneau est sélectionné
    const editForm = document.getElementById('editForm');
    if (editForm) {
        editForm.addEventListener('submit', (e) => {
            const d = document.getElementById('f-date')?.value || '';
            const h = document.getElementById('f-heure')?.value || '';
            if (!d || !h) {
                e.preventDefault();
                alert('Veuillez choisir une date et une heure pour le contrôle technique.');
            }
        });
    }
});