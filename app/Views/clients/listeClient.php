<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<section class="container">
    <div class="card">
        <div style="padding:16px; border-bottom:1px solid var(--border); background:#fafafa; display:flex; align-items:center; justify-content:space-between; gap:12px;">
            <h1>Liste des clients</h1>
            <form method="post">
                <button class="btn-warning" type="submit" name="recup-mail">Récupération des adresses mails des clients</button>
            </form>
        </div>
        <div class="table-wrap" style="padding: 8px 12px 16px;">
            <?php
            $tableauclient = new \CodeIgniter\View\Table();
            // Optionnel: ajouter une classe à la table (les styles globaux s'appliquent aussi sans)
            $tableauclient->setTemplate([
                'table_open' => '<table id="clientsTable">'
            ]);
            $tableauclient->setHeading('Nom', 'Prénom', 'Tel', 'Mail', 'Actions', ' ');
            foreach ($listeClients as $client) {
                $tableauclient->addRow(
                    esc($client['NOM']),
                    esc($client['PRENOM']),
                    esc($client['TEL']),
                    esc($client['EMAIL']),
                    '<a class="btn-edit" href="' . url_to('modif-client', $client['IDCLIENT']) . '">Modifier</a>',
                    '<a class="btn-danger" href="' . url_to('client-suppr', $client['IDCLIENT']) . '">Supprimer</a>'
                );
            }
            echo $tableauclient->generate();
            ?>
        </div>
    </div>
</section>
<?= $this->endSection() ?>