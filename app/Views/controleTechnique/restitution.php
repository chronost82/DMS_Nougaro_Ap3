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
            line-height: 1.8;
            padding: 30px 20px;
            font-size: 16px;
        }

        .restitution-header {
            text-align: center;
            margin-bottom: 40px;
            padding-bottom: 20px;
            border-bottom: 3px solid #0066cc;
        }

        .restitution-header h1 {
            font-size: 32px;
            color: #0066cc;
            margin-bottom: 8px;
            font-weight: 700;
        }

        .restitution-header p {
            color: #666;
            font-size: 16px;
        }

        .restitution-section {
            margin-bottom: 30px;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 6px;
            padding: 20px;
            page-break-inside: avoid;
        }

        .restitution-section h2 {
            font-size: 18px;
            color: #0066cc;
            margin-bottom: 18px;
            border-left: 4px solid #0066cc;
            padding-left: 12px;
            font-weight: 700;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 18px;
        }

        .info-item {
            background: white;
            padding: 14px 16px;
            border-radius: 5px;
            border-left: 4px solid #0066cc;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .info-label {
            font-weight: 700;
            font-size: 12px;
            color: #0066cc;
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
            border: 1px solid #cce5ff;
            padding: 16px;
            border-radius: 5px;
            margin-top: 12px;
        }

        .commentaire-box h3 {
            color: #0066cc;
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
    <div class="restitution-header">
        <h1>Rapport de Précontrole Technique</h1>
        <p>Restitution complete du précontrole technique effectué</p>
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
        </div>
    </div>

    <!-- Informations client -->
    <div class="restitution-section">
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

    <!-- Resume -->
    <div class="restitution-section">
        <h2>RESUME</h2>
        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Date du controle</div>
                <div class="info-value"><?= esc($ct['DATECT'] ?? 'N/A') ?></div>
            </div>
            <div class="info-item">
                <div class="info-label">Heure du controle</div>
                <div class="info-value"><?= esc($ct['HEURE'] ?? 'N/A') ?></div>
            </div>
        </div>
    </div>

    <!-- Signature -->
    <div class="restitution-section">
        <h2>SIGNATURES</h2>
        <div class="signature-section">
            <div class="signature-box">
                <div class="signature-line">Signature du Controleur</div>
            </div>
            <div class="signature-box">
                <div class="signature-line">Date et Signature du Client</div>
            </div>
        </div>
    </div>

    <div class="footer">
        <p style="margin-top: 12px;">Copyright <?= date('Y') ?> - Tous droits reserves</p>
    </div>
</body>
</html>
