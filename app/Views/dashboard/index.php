<?php $this->extend('layout') ?>


<?= $this->section('meta') ?>
<link rel="stylesheet" href="css/dashboard.css">
<?php $this->endSection() ?>



<?= $this->section('content') ?>

<div class="dashboard-container">
    <main class="dashboard">
        <section class="dashboard-hero">
            <h1>Tableau de bord administrateur</h1>
            <p class="muted">Accédez rapidement aux principales sections et suivez l’activité du système.</p>
        </section>

        <!-- <section class="dashboard-stats" aria-label="Vue d’ensemble">
            <a class="stat-card" href="<?= url_to('admin-liste-demandes-en-attentes') ?>">
                <span class="stat-icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M4 6h16v2H4V6zm0 5h16v2H4v-2zm0 5h10v2H4v-2z" />
                    </svg>
                </span>
                <div class="stat-meta">
                    <div class="label">Demandes en attente</div>
                    <div class="value">Voir la liste</div>
                </div>
            </a>
            <a class="stat-card" href="<?= url_to('liste-clients') ?>">
                <span class="stat-icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V20h14v-3.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V20h6v-3.5c0-2.33-4.67-3.5-7-3.5z" />
                    </svg>
                </span>
                <div class="stat-meta">
                    <div class="label">Clients</div>
                    <div class="value">Voir la liste</div>
                </div>
            </a>
            <a class="stat-card" href="<?= url_to('test-liste') ?>">
                <span class="stat-icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M20.9 6.2a4.2 4.2 0 01-5.5 4L8 17.1l-1.9 1.9a2 2 0 11-2.8-2.8L5.2 14l6.9-7.4a4.2 4.2 0 016-4.9l-2.8 2.8 2.4 2.4 2.9-2.7c.1.3.2.7.2 1zM6 20.5a1 1 0 100-2 1 1 0 000 2z" />
                    </svg>
                </span>
                <div class="stat-meta">
                    <div class="label">Tests</div>
                    <div class="value">Voir la liste</div>
                </div>
            </a>
            <a class="stat-card" href="<?= url_to('liste-eleve') ?>">
                <span class="stat-icon" aria-hidden="true">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M12 3l9 5-9 5-9-5 9-5zm-7 9l7 4 7-4v4l-7 4-7-4v-4z" />
                    </svg>
                </span>
                <div class="stat-meta">
                    <div class="label">Élèves</div>
                    <div class="value">Voir la liste</div>
                </div>
            </a>
        </section> -->

        <section class="dashboard-actions" aria-label="Actions rapides">
            <a class="action-card" href="<?= url_to('admin-liste-demandes-en-attentes') ?>">
                <div class="action-icon" aria-hidden="true">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M3 13h2v-2H3v2zm0 4h2v-2H3v2zM3 9h2V7H3v2zm4 8h14v-2H7v2zm0-4h14v-2H7v2zm0-6v2h14V7H7z" />
                    </svg>
                </div>
                <div class="action-content">
                    <h3>Gérer les demandes</h3>
                    <p>Consultez et validez les demandes en attente.</p>
                </div>
            </a>

            <a class="action-card" href="<?= url_to('liste-clients') ?>">
                <div class="action-icon" aria-hidden="true">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M16 11c1.66 0 2.99-1.34 2.99-3S17.66 5 16 5c-1.66 0-3 1.34-3 3s1.34 3 3 3zm-8 0c1.66 0 2.99-1.34 2.99-3S9.66 5 8 5C6.34 5 5 6.34 5 8s1.34 3 3 3zm0 2c-2.33 0-7 1.17-7 3.5V20h14v-3.5C15 14.17 10.33 13 8 13zm8 0c-.29 0-.62.02-.97.05 1.16.84 1.97 1.97 1.97 3.45V20h6v-3.5c0-2.33-4.67-3.5-7-3.5z" />
                    </svg>
                </div>
                <div class="action-content">
                    <h3>Liste des clients</h3>
                    <p>Accédez à la base clients et mettez à jour leurs informations.</p>
                </div>
            </a>

            <a class="action-card" href="<?= url_to('test-liste') ?>">
                <div class="action-icon" aria-hidden="true">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M20.9 6.2a4.2 4.2 0 01-5.5 4L8 17.1l-1.9 1.9a2 2 0 11-2.8-2.8L5.2 14l6.9-7.4a4.2 4.2 0 016-4.9l-2.8 2.8 2.4 2.4 2.9-2.7c.1.3.2.7.2 1zM6 20.5a1 1 0 100-2 1 1 0 000 2z" />
                    </svg>
                </div>
                <div class="action-content">
                    <h3>Tests</h3>
                    <p>Parcourez les tests et consultez leurs résultats.</p>
                </div>
            </a>

            <a class="action-card" href="<?= url_to('liste-eleve') ?>">
                <div class="action-icon" aria-hidden="true">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill="currentColor" d="M12 3l9 5-9 5-9-5 9-5zm-7 9l7 4 7-4v4l-7 4-7-4v-4z" />
                    </svg>
                </div>
                <div class="action-content">
                    <h3>Élèves</h3>
                    <p>Gérez les élèves et leurs affectations.</p>
                </div>
            </a>
        </section>
    </main>
</div>

<?= $this->endSection() ?>