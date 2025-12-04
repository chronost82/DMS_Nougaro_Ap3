<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CTModel;
use CodeIgniter\HTTP\ResponseInterface;

class ClientController extends BaseController
{
    public function affiche()
    {
        $ClientsModel = model('ClientModel');
        $listeClients = $ClientsModel->findall();
        return view("clients/listeClient", ["listeClients" => $listeClients]);
    }

    public function delete($idClient)
    {
        $clientsModel = model('ClientModel');
        $possedeModel = model('PossedeModel');
        $cTModel = model('CTModel');
        $testModel = model('TestModel');
        $demandeModel = model('Demande');
        $clientPossede = $possedeModel->where('IDCLIENT', $idClient)->find();
        $clientTest = $testModel->where('IDCT', $clientPossede[0]['IDCT'])->find();

        if ($demandeModel->where('IDCLIENT', $idClient)->find()) {
            return redirect("liste-clients")->back()->with('erreur', 'Avant de supprimer ce client, veuillez effacer toutes les demandes associées à lui.');
        } else {
            $possedeModel->delete($idClient);
            if ($testModel->where('IDCT', $clientPossede[0]['IDCT'])->find()) {
                foreach ($clientTest as $test) {
                    $testModel->delete($test['IDTESTTECHNIQUE']);
                }
            }
            $cTModel->delete($clientPossede[0]['IDCT']);

            $clientsModel->delete($idClient);
            return redirect("liste-clients");
        }
    }

    public function confirmDelete($idClient)
    {
        $demandeModel = model('Demande');
        if ($demandeModel->where('IDCLIENT', $idClient)->find()) {
            return redirect("liste-clients")->back()->with('erreur', 'Avant de supprimer ce client, veuillez effacer toutes les demandes associées à lui.')->with('idClientToDelete',$idClient);
        } else {
            return redirect("liste-clients")->back()->with('confirm', 'Êtes-vous sure de vouloir supprimer ce client')->with('idClientToDelete',$idClient);
        }
    }

    public function modif($idClient)
    {
        $ClientsModel = model('ClientModel');
        $clientAModif = $ClientsModel->find($idClient);
        return view("clients/modifClient", ["clientAModif" => $clientAModif]);
    }

    public function update()
    {
        $ClientsModel = model('ClientModel');

        $clientAModif = [
            'IDCLIENT' => $this->request->getPost('idclient'),
            'NOM' => $this->request->getPost('nom'),
            'PRENOM' => $this->request->getPost('prenom'),
            'TEL' => $this->request->getPost('tel'),
            'EMAIL' => $this->request->getPost('email'),
            'NUMRANDOM' => $this->request->getPost('numrandom')
        ];
        $ClientsModel->save($clientAModif);
        return redirect('liste-clients');
    }

    public function mail()
    {
        $ClientsModel = model('ClientModel');

        // Récupération des emails
        $emails = $ClientsModel->select('email')->findAll();

        if (empty($emails)) {
            return 'Aucun email trouvé.';
        }

        $content = implode("\n", array_column($emails, 'email'));
        $filename = 'emails_Clients_' . date('Y-m-d') . '.txt';

        return $this->response->download($filename, $content);
    }
}
