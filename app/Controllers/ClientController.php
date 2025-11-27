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
        $ClientsModel = model('ClientModel');
        $PossedeModel = model('PossedeModel');
        $CTModel = model('CTModel');

        $clientPossede = $PossedeModel->where('IDCLIENT', $idClient)->find();
        $PossedeModel->delete($idClient);
        $ClientsModel->delete($clientPossede[0]['IDCLIENT']);
        $CTModel->delete($clientPossede[0]['IDCT']);
        return redirect("liste-clients");
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
            'EMAIL' => $this->request->getPost('mail'),
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
