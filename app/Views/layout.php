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
    <a class="btn disconnect top-left-fixed" href="<?= url_to("logout"); ?>">Déconnexion</a>
    <header class="top-nav" role="navigation" aria-label="Navigation principale">
        <nav class="top-nav__inner">
            <a href="<?= url_to('admin-liste-demandes-en-attentes')?>" class="btn">Demandes en attente</a>
            <a href="<?= url_to('liste-clients')?>" class="btn">Liste des clients</a>
            <a href="<?= url_to('test-liste') ?>" class="btn">Liste des tests</a>
            <a href="<?= url_to('liste-eleve') ?>" class="btn">Liste des élèves</a>
        </nav>
    </header>
    <?= $this->renderSection('content') ?>
</body>

</html>