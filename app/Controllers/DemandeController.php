<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DemandeController extends BaseController
{
    public function affiche()
    {
        $client = [
            [
                "id" => 1,
                "nom" => "Dupont",
                "prenom" => "Jean",
                "email" => "@gmail.com",
                "heure" => "14:30",
                "telephone" => "06 12 34 56 78",
                "marque" => "Yamaha",
                "modele" => "MT-07",
                "immatriculation" => "AB-123-CD",
                "etat" => "validee"
            ],
            [
                "id" => 2,
                "nom" => "Durand",
                "prenom" => "Marie",
                "email" => "@yahoo.fr",
                "heure" => "16:00",
                "telephone" => "06 98 76 54 32",
                "marque" => "Honda",
                "modele" => "CB500F",
                "immatriculation" => "EF-456-GH",
                "etat" => "attente"
            ],
            [
                "id" => 3,
                "nom" => "Martin",
                "prenom" => "Paul",
                "email" => "@hotmail.com",
                "heure" => "10:00",
                "telephone" => "06 11 22 33 44",
                "marque" => "Kawasaki",
                "modele" => "Ninja 400",
                "immatriculation" => "IJ-789-KL",
                "etat" => "terminee"
            ],
            [
                "id" => 4,
                "nom" => "Bernard",
                "prenom" => "Sophie",
                "email" => "@orange.fr",
                "heure" => "11:30",
                "telephone" => "06 55 66 77 88",
                "marque" => "Suzuki",
                "modele" => "GSX-S750",
                "immatriculation" => "MN-012-OP",
                "etat" => "attente"
            ],
            [
                "id" => 5,
                "nom" => "Lefevre",
                "prenom" => "Luc",
                "email" => "@free.fr",
                "heure" => "15:00",
                "telephone" => "06 99 88 77 66",
                "marque" => "Ducati",
                "modele" => "Monster 821",
                "immatriculation" => "QR-345-ST",
                "etat" => "validee"
            ]
        ];
        $status = 'attente';
        return view('Dashboard/GestionDemandes.php', ['clients' => $client, 'status' => $status]);
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
        $demandeModel = model('Demande');
        $demande = $this->request->getPost();

        $demande['etat'] = 'attente';
        $demandeModel->save($demande);
        // dd($demande);
        return view('accueil');
    }
}
