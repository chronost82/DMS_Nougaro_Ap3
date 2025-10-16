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
    <a href="<?= url_to("logout"); ?>">Déconnexion</a>
    <?= $this->renderSection('content') ?>
</body>

</html>