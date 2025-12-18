<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Feuille de style globale de l'application -->
    <link rel="stylesheet" href="<?= base_url('css/layout.css') ?>">
    <?= $this->renderSection('meta') ?>
    <!-- Titre de la page; valeur par défaut si $title est absent -->
    <title><?= esc($title ?? 'Atelier MV') ?></title>
</head>

<body>
    <?php
    // Utilitaire de navigation: renvoie la classe de bouton en fonction de la route active
    // Permet d'appliquer un style "actif" (btn-primary) au lien dont l'URL correspond à la page courante
    if (!function_exists('nav_btn_class')) {
        /**
         * Retourne la classe CSS à appliquer au lien d'une route pour indiquer l'état actif.
         * @param string $route Nom de la route (utilisée par url_to)
         * @return string 'btn-primary' si la route correspond à l'URL actuelle, sinon 'btn'
         */
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
            <a href="<?= url_to('admin-liste-demandes-en-attentes') ?>" class="<?= nav_btn_class('admin-liste-demandes-en-attentes') ?>">Demandes</a>
            <a href="<?= url_to('selection-controle-technique') ?>">Voir contrôles techniques en cours</a>
            <?php
            if (auth()->user()->inGroup('prof')) {?>
            <a href="<?= url_to('liste-clients') ?>" class="<?= nav_btn_class('liste-clients') ?>">Liste des clients</a>
            <a href="<?= url_to('test-liste') ?>" class="<?= nav_btn_class('test-liste') ?>">Liste des tests</a>
            <a href="<?= url_to('liste-eleve') ?>" class="<?= nav_btn_class('liste-eleve') ?>">Liste des élèves</a>
            <?php };?>
            <a href="<?= url_to('logout'); ?>" class="btn disconnect" title="Se déconnecter">Déconnexion</a>
        </nav>
    </header>
    <script>
        // Toggle responsive du menu: ouvre/ferme la navigation sur petits écrans
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