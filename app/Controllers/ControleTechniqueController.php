<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ControleTechniqueController extends BaseController
{
    public function affiche(int $idClient)
    {
        $testTechniques = model('TestTechniqueModel')->findAll();
        $eleve = model('EleveModel')->findAll();
        $ct = model('CTModel')->getCTWithClient($idClient);
        return view('controleTechnique/controleTechnique.php', [
            'tests' => $testTechniques,
            'ct' => $ct,
            'eleve' => $eleve,
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
}
