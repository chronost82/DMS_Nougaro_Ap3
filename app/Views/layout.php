<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/layout.css">
    <?= $this->renderSection('meta') ?>
    <title><?= esc($title ?? 'Atelier MV') ?></title>
</head>
<body>
    <?= $this->renderSection('content') ?>
</body>
</html>