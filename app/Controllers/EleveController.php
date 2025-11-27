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
        $elevesModel = model('ElevesModel');
        $elevesModel->delete($idEleve);
        return redirect("liste-eleve");
    }

    public function modif($idEleve)
    {
        $elevesModel = model('ElevesModel');
        $eleveAModif = $elevesModel->find($idEleve);
        return view("eleve/modifEleve", ["eleveAModif" => $eleveAModif]);
    }
    public function update()
    {
        $anneeCourrante = date('Y');
        $elevesModel = model('ElevesModel');

        $eleveAModif = [
            'IDELEVE' => $this->request->getPost('idEleve'),
            'NOM' => $this->request->getPost('nom'),
            'PRENOM' => $this->request->getPost('prenom'),
            'ANNEE' => $anneeCourrante,
        ];
        $elevesModel->save($eleveAModif);
        return redirect('liste-eleve');
    }

    public function ajout()
    {
        return view("eleve/ajoutEleve");
    }

    public function create()
    {
        $anneeCourrante = date('Y');
        $eleveModel = model('ElevesModel');
        $ajoutEleve = [
            'NOM' => $this->request->getPost('nom'),
            'PRENOM' => $this->request->getPost('prenom'),
            'ANNEE' => $anneeCourrante,
        ];
        $eleveModel->save($ajoutEleve);

        return redirect('liste-eleve');
    }

    public function modifAnnee($idEleve)
    {
        $eleveModel = model('ElevesModel');
        $eleveAModif = $eleveModel->find($idEleve);
        if($eleveAModif['ANNEE']<date('Y')){
            $modifAnnee =[
                'ANNEE'=>date('Y'),
            ];
           $eleveModel->update($idEleve,$modifAnnee);
        }
        return redirect('liste-eleve');
    }
}
