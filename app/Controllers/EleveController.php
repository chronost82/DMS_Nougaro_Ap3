<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class EleveController extends BaseController
{
    public function affiche()
    {
        $elevesModel = model('ElevesModel');
        $listeEleves = $elevesModel->findall();
        return view("eleve/ListeEleve", ["listeEleves" => $listeEleves]);
    }

    public function delete($idEleve)
    {
        $elevesModel = model('EleveModel');
        $elevesModel->delete($idEleve);
        return redirect("eleve/ListeEleve");
    }

    public function modif($idEleve)
    {
        $elevesModel = model('EleveModel');
        $eleveAModif = $elevesModel->find($idEleve);
        return view("eleves/modifEleve", ["eleveAModif" => $eleveAModif]);
    }
    public function update()
    {
        $elevesModel = model('EleveModel');

        $eleveAModif = [
            'IDELEVE' => $this->request->getPost('idEleve'),
            'NOM' => $this->request->getPost('nom'),
            'PRENOM' => $this->request->getPost('prenom'),
            'ANNEE' => $this->request->getPost('annee'),
        ];
        $elevesModel->save($eleveAModif);
        return redirect('liste-eleves');
    }

    public function ajout()
    {
        return view("eleve/ajoutEleve");
    }
    public function create()
    {
        $eleveModel = model('EleveModel');
        $ajoutEleve = [
            'NOM' => $this->request->getPost('nom'),
            'PRENOM' => $this->request->getPost('prenom'),
            'ANNEE' => $this->request->getPost('annee'),
        ];
        $eleveModel->save($ajoutEleve);

        return redirect('liste-eleves');
    }
}
