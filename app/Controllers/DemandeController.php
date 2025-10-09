<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ControllerDemande extends BaseController
{
   public function affiche()
    {
        return view('demande/liste-demandes-en-attentes');
    }

    public function delete()
    {
        //
    }

    public function modif()
    {
        //
    }
    public function update()
    {
        //
    }

    public function ajout()
    {
        return view('accueil');
    }

    public function create()
    {
        //
    }
}
