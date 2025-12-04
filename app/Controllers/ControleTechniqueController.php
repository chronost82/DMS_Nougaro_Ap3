<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ControleTechniqueController extends BaseController
{
    public function affiche(int $idDemande)
    {
        $testTechniques = model('TestTechniqueModel')->findAll();
        $eleve = model('ElevesModel')->findAll();
        $ct = model('CTModel')->getCTWithDemande($idDemande);
        
        // Marquer le CT comme en cours
        if (!empty($ct) && isset($ct[0]['IDCT'])) {
            $ctModel = model('CTModel');
            $ctModel->update($ct[0]['IDCT'], [
                'CTENCOURS' => 1
            ]);
            
            // Récupérer les états des tests existants pour ce CT
            $idCt = $ct[0]['IDCT'];
            $testModel = model('TestModel');
            $testStates = $testModel->where('IDCT', $idCt)->findAll();
            $testStatesMap = [];
            foreach ($testStates as $test) {
                $testStatesMap[$test['IDTESTTECHNIQUE']] = $test['ETAT'];
            }
        } else {
            $testStatesMap = [];
        }
        
        return view('controleTechnique/controleTechnique.php', [
            'tests' => $testTechniques,
            'ct' => $ct,
            'eleves' => $eleve,
            'idDemande' => $idDemande,
            'testStates' => $testStatesMap,
        ]);
    }

    public function saveEtat()
    {
        $request = $this->request;

        $idCt   = $request->getPost('idCt');   // id du controle/demande
        $etat   = $request->getPost('etat');   // tableau [idTest => statut]

        if (!$idCt || !is_array($etat)) {
            return $this->response->setStatusCode(400)
                ->setJSON(['status' => 'error', 'message' => 'Données invalides']);
        }

        $testModel = model('TestModel');

        foreach ($etat as $idTest => $statut) {
            if (!in_array($statut, ['ok', 'surv', 'def'], true)) {
                continue;
            }

            // Vérifie si un enregistrement existe déjà pour ce CT + test
            $existing = $testModel->where([
                'IDCT'             => $idCt,
                'IDTESTTECHNIQUE'  => (int) $idTest,
            ])->first();

            if ($existing) {
                // Mise à jour : on utilise IDTESTTECHNIQUE comme identifiant
                $testModel->update($existing['IDTESTTECHNIQUE'], [
                    'ETAT' => $statut,
                ]);
            } else {
                // Création
                $testModel->insert([
                    'IDCT'            => $idCt,
                    'IDTESTTECHNIQUE' => (int) $idTest,
                    'ETAT'            => $statut,
                ]);
            }
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    public function selection()
    {
        $modelCt = model('CTModel');
        return view('controleTechnique/SelectionControleTechnique.php', [
            'cts' => $modelCt->getAllCTWithClient(),
        ]);

    }

    public function saveControleur()
    {
        $request = $this->request;

        $idCt    = $request->getPost('idCt');
        $idEleve = $request->getPost('idEleve');

        if (!$idCt || !$idEleve) {
            return $this->response->setStatusCode(400)
                ->setJSON(['status' => 'error', 'message' => 'Données invalides: idCt=' . $idCt . ', idEleve=' . $idEleve]);
        }

        $ctModel = model('CTModel');

        $ctModel->update($idCt, [
            'IDELEVE' => $idEleve,
        ]);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function saveCommentaire()
    {
        $request = $this->request;

        $idCt        = $request->getPost('idCt');
        $commentaire = $request->getPost('commentaire');

        if (!$idCt) {
            return $this->response->setStatusCode(400)
                ->setJSON(['status' => 'error', 'message' => 'ID contrôle manquant']);
        }

        $ctModel = model('CTModel');

        $ctModel->update($idCt, [
            'COMMENTAIRE' => $commentaire,
        ]);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function terminer()
    {
        $idDemande = $this->request->getPost('idDemande');
        $idCt = $this->request->getPost('idCt');

        if (!$idDemande || !$idCt) {
            return $this->response->setStatusCode(400)
                ->setJSON(['status' => 'error', 'message' => 'Données manquantes']);
        }

        $ctModel = model('CTModel');
        $demandeModel = model('Demande');

        // Mettre CTENCOURS à 0
        $ctModel->update($idCt, ['CTENCOURS' => 0]);

        // Mettre ETAT à 'terminee' dans la demande
        $demandeModel->update($idDemande, ['ETAT' => 'terminee']);

        return $this->response->setJSON(['status' => 'success']);
    }

    public function restitution(int $idDemande)
    {
        $ct = model('CTModel')->getCTWithDemande($idDemande);
        
        if (empty($ct)) {
            return $this->response->setStatusCode(404)
                ->setBody('Contrôle technique non trouvé');
        }

        $ct = $ct[0];
        $testModel = model('TestModel');
        $tests = $testModel->where('IDCT', $ct['IDCT'])->findAll();

        // Récupérer les données des tests techniques
        $testTechniques = model('TestTechniqueModel')->findAll();
        $testMap = [];
        foreach ($testTechniques as $test) {
            $testMap[$test['IDTESTTECHNIQUE']] = $test;
        }

        // Générer le contenu HTML
        $html = view('controleTechnique/restitution', [
            'ct' => $ct,
            'tests' => $tests,
            'testMap' => $testMap,
        ]);

        // Créer une instance de Dompdf
        $dompdf = new \Dompdf\Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Définir les en-têtes pour le téléchargement
        $filename = 'rapport_CT_' . ($ct['NUMCT'] ?? $ct['IDCT']) . '_' . date('Y-m-d') . '.pdf';
        
        return $this->response
            ->setHeader('Content-Type', 'application/pdf')
            ->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"')
            ->setBody($dompdf->output());
    }

}

