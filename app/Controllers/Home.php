<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $vehiculeModel = model('VehiculeModel');
        $marques = $vehiculeModel->distinct()->select('MARQUE')->orderBy('MARQUE', 'ASC')->findAll();
        $vehicules = $vehiculeModel->select('MARQUE, MODELE')->orderBy('MARQUE', 'ASC')->orderBy('MODELE', 'ASC')->findAll();
        return view('accueil', [
            'marques'   => $marques,
            'vehicules' => $vehicules,
        ]);
    }
}
