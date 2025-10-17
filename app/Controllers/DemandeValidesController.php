<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class DemandeValidesController extends BaseController
{
    public function affiche()
    {
        $clientModel = model('ClientModel');
        $client = $clientModel->findJoinAllWithEtat();
        $status = 'validee';
        return view('dashboard/GestionDemandes.php', ['clients' => $client, 'status' => $status]);
    }
}
