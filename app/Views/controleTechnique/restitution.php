<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Rapport Précontrole Technique</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', 'Segoe UI', sans-serif;
        }

        body {
            font-family: 'Segoe UI', 'Segoe UI', sans-serif;
            color: #222;
            line-height: 1.5;
            padding: 10px;
            font-size: 15px;
        }

        .bandeau-lycee {
            width: 100%;
            text-align: center;
            margin-bottom: 5px;
            page-break-after: avoid;
        }

        .bandeau-lycee img {
            max-width: 100%;
            max-height: 100%;
            height: auto;
        }

        .restitution-header {
            margin-bottom: 12px;
            text-align: center;
            padding-bottom: 8px;
            border-bottom: 3px solid #A24246;
            page-break-after: avoid;
        }

        .restitution-header h1 {
            font-size: 24px;
            color: #A24246;
            margin-bottom: 3px;
            font-weight: 700;
            letter-spacing: 1px;
        }

        .restitution-header p {
            color: #666;
            font-size: 16px;
        }

        .restitution-section {
            margin-bottom: 12px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 12px;
            page-break-inside: avoid;
        }

        .restitution-section h2 {
            font-size: 16px;
            color: #A24246;
            margin-bottom: 10px;
            border-left: 5px solid #A24246;
            padding-left: 10px;
            letter-spacing: 1px;
            font-weight: 700;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .info-item {
            background: white;
            padding: 10px 12px;
            border-radius: 5px;
            border-left: 4px solid #A24246;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .info-label {
            font-weight: 700;
            font-size: 12px;
            color: #A24246;
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-bottom: 8px;
        }

        .info-value {
            font-size: 18px;
            color: #222;
            font-weight: 500;
            line-height: 1.5;
        }

        .tests-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .tests-table th,
        .tests-table td {
            padding: 12px 14px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .tests-table th {
            background: #0066cc;
                background: #A24246;
            color: white;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 13px;
            letter-spacing: 0.5px;
        }

        .tests-table tr:nth-child(even) {
            background: #f9f9f9;
        }

        .tests-table td:first-child {
            font-weight: 500;
            color: #333;
            font-size: 15px;
        }

        .status-ok {
            background: #d4edda;
            color: #155724;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: 700;
            display: inline-block;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-surv {
            background: #fff3cd;
            color: #856404;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: 700;
            display: inline-block;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-def {
            background: #f8d7da;
            color: #721c24;
            padding: 6px 12px;
            border-radius: 4px;
            font-weight: 700;
            display: inline-block;
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .commentaire-box {
            background: white;
            border: 1px solid #f0d0d0;
            padding: 16px;
            border-radius: 5px;
            margin-top: 12px;
        }

        .commentaire-box h3 {
            color: #A24246;
            font-size: 15px;
            margin-bottom: 10px;
            font-weight: 700;
        }

        .commentaire-text {
            color: #333;
            font-size: 16px;
            line-height: 1.8;
            white-space: pre-wrap;
        }

        .signature-section {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 40px;
            margin-top: 25px;
        }

        .signature-box {
            text-align: center;
        }

        .signature-line {
            border-top: 2px solid #333;
            margin-top: 55px;
            padding-top: 10px;
            font-size: 12px;
            font-weight: 600;
            color: #333;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 2px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 13px;
            line-height: 1.8;
        }
    </style>
</head>
<body>
    <div class="bandeau-lycee">
        <img src="<?= base_url('img/bandeau_lycee.png') ?>" alt="Bandeau Lycée Claude Nougaro">
    </div>

    <div class="restitution-header">
        <h1>Rapport de Précontrole Technique</h1>
    </div>

    <!-- Informations vehicule -->
    <div class="restitution-section">
        <h2>INFORMATIONS VEHICULE</h2>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Immatriculation</div>
                <div class="info-value"><?= esc($ct['IMAT'] ?? 'N/A') ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Marque - Modele</div>
                <div class="info-value"><?= esc(($ct['MARQUE'] ?? '') . ' - ' . ($ct['MODELE'] ?? '')) ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Numero Chassis</div>
                <div class="info-value"><?= esc($ct['NUMCHASSIS'] ?? 'N/A') ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Numero de CT</div>
                <div class="info-value"><?= esc($ct['NUMCT'] ?? 'N/A') ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Date du controle</div>
                <div class="info-value"><?= esc($ct['DATECT'] ?? 'N/A') ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Heure du controle</div>
                <div class="info-value"><?= esc($ct['HEURE'] ?? 'N/A') ?></div>
            </div>
        </div>
        <h2>INFORMATIONS CLIENT</h2>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Nom</div>
                <div class="info-value"><?= esc($ct['NOM'] ?? 'N/A') ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Prenom</div>
                <div class="info-value"><?= esc($ct['PRENOM'] ?? 'N/A') ?></div>
            </div>
        </div>
    </div>

    <!-- Resultats des tests -->
    <div class="restitution-section">
        <h2>RESULTATS DES TESTS</h2>
        <table class="tests-table">
            <thead>
                <tr>
                    <th>Test</th>
                    <th>Statut</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($tests)) : ?>
                    <?php foreach ($tests as $test) : ?>
                        <?php $testInfo = $testMap[$test['IDTESTTECHNIQUE']] ?? null; ?>
                        <tr>
                            <td><?= esc($testInfo['LIBELLE'] ?? 'Test #' . $test['IDTESTTECHNIQUE']) ?></td>
                            <td>
                                <?php
                                $statusClass = 'status-' . $test['ETAT'];
                                $statusText = [
                                    'ok' => 'OK',
                                    'surv' => 'A surveiller',
                                    'def' => 'Defaillant'
                                ][$test['ETAT']] ?? 'Inconnu';
                                ?>
                                <span class="<?= $statusClass ?>"><?= $statusText ?></span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else : ?>
                    <tr>
                        <td colspan="2" style="text-align: center; color: #999;">Aucun test enregistre</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <!-- Commentaires -->
    <?php if (!empty($ct['COMMENTAIRE'])) : ?>
        <div class="restitution-section">
            <h2>OBSERVATIONS ET REMARQUES</h2>
            <div class="commentaire-box">
                <div class="commentaire-text"><?= esc($ct['COMMENTAIRE']) ?></div>
            </div>
        </div>
    <?php endif; ?>

    <div class="footer">
        <p style="margin-top: 12px;">Cet outil de pré-contrôle technique est destiné exclusivement à des fins pédagogiques et ne possède aucune valeur officielle.</p>
        <p style="margin-top: 12px;">Copyright <?= date('Y') ?> - Tous droits reserves</p>
    </div>
</body>
</html>
