<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?= base_url('css/layout.css') ?>">
    <?= $this->renderSection('meta') ?>
    <title><?= esc($title ?? 'Atelier MV') ?></title>
</head>

<body>
    <?php
    // Utilitaire: renvoie la classe de bouton en fonction de la route active
    if (!function_exists('nav_btn_class')) {
        function nav_btn_class(string $route): string
        {
            return current_url() === url_to($route) ? 'btn-primary' : 'btn';
        }
    }
    ?>
    <header class="top-nav" role="navigation" aria-label="Navigation principale">
        <div class="top-nav__bar">
            <div class="top-nav__brand">
                <a href="<?= url_to('dashboard') ?>" aria-label="Accueil">Atelier MV</a>
            </div>
            <button class="top-nav__toggle btn" type="button" aria-controls="mainNav" aria-expanded="false">
                Menu
            </button>
        </div>
        <nav id="mainNav" class="top-nav__inner">
            <a href="<?= url_to('admin-liste-demandes-en-attentes') ?>" class="<?= nav_btn_class('admin-liste-demandes-en-attentes') ?>">Demandes en attente</a>
            <a href="<?= url_to('liste-clients') ?>" class="<?= nav_btn_class('liste-clients') ?>">Liste des clients</a>
            <a href="<?= url_to('test-liste') ?>" class="<?= nav_btn_class('test-liste') ?>">Liste des tests</a>
            <a href="<?= url_to('liste-eleve') ?>" class="<?= nav_btn_class('liste-eleve') ?>">Liste des élèves</a>
            <a href="<?= url_to('logout'); ?>" class="btn disconnect" title="Se déconnecter">Déconnexion</a>
        </nav>
    </header>
    <script>
        // Toggle responsive du menu
        (function() {
            const header = document.querySelector('.top-nav');
            const toggle = document.querySelector('.top-nav__toggle');
            if (!header || !toggle) return;
            toggle.addEventListener('click', () => {
                const open = header.classList.toggle('is-open');
                toggle.setAttribute('aria-expanded', String(open));
            });
        })();
    </script>
    <?= $this->renderSection('content') ?>
</body>

</html>