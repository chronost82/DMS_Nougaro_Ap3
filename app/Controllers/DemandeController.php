<?php

namespace App\Controllers;

use App\Controllers\BaseController;

class DemandeController extends BaseController
{
    /**
     * Génère un identifiant client au format AAAA-1234-AAAA
     */
    private function generateClientNumber(): string
    {
        $letters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $digits  = '0123456789';

        $randFrom = function (string $pool, int $len): string {
            $out = '';
            $max = strlen($pool) - 1;
            for ($i = 0; $i < $len; $i++) {
                $out .= $pool[random_int(0, $max)];
            }
            return $out;
        };

        return $randFrom($letters, 4) . '-' . $randFrom($digits, 4) . '-' . $randFrom($letters, 4);
    }

    /**
     * Valide le format AAAA-1234-AAAA
     */
    private function isValidClientNumber(?string $num): bool
    {
        if ($num === null) return false;
        return (bool) preg_match('/^[A-Z]{4}-[0-9]{4}-[A-Z]{4}$/', strtoupper($num));
    }

    /**
     * Vérifie si un NUMRANDOM existe déjà (hors ID exclu)
     */
    private function numRandomExists(string $num, $excludeId = null): bool
    {
        $clientModel = model('ClientModel');
        $builder = $clientModel->where('NUMRANDOM', $num);
        if (!empty($excludeId)) {
            $builder = $builder->where('IDCLIENT !=', $excludeId);
        }
        return (bool) $builder->first();
    }

    /**
     * Génère une valeur unique pour NUMRANDOM
     */
    private function generateUniqueClientNumber($excludeId = null, int $maxAttempts = 10): string
    {
        for ($i = 0; $i < $maxAttempts; $i++) {
            $candidate = $this->generateClientNumber();
            if (!$this->numRandomExists($candidate, $excludeId)) {
                return $candidate;
            }
        }
        // En cas d'épuisement (hautement improbable), on ajoute un suffixe temps
        return $this->generateClientNumber();
    }

    public function affiche()
    {

        $demandeModel = model('Demande');
        $demandes = $demandeModel->findJoinAll();
        $status = 'attente';
        return view('dashboard/GestionDemandes.php', ['clients' => $demandes, 'status' => $status]);
    }

    public function delete()
    {
        //
    }

    public function modif()
    {
        // Réservé pour une future implémentation
    }

    public function update()
    {
        $clientModel = model('ClientModel');

        $currentId = $this->request->getPost('id');
        $numRandomPost = strtoupper(trim((string) ($this->request->getPost('numrandom') ?? '')));

        if (!$this->isValidClientNumber($numRandomPost) || $this->numRandomExists($numRandomPost, $currentId)) {
            $numRandomFinal = $this->generateUniqueClientNumber($currentId);
        } else {
            $numRandomFinal = $numRandomPost;
        }

        $client = [
            'NOM' => $this->request->getPost('nom'),
            'PRENOM' => $this->request->getPost('prenom'),
            'EMAIL' => $this->request->getPost('email'),
            'TEL' => $this->request->getPost('telephone'),
            'NUMRANDOM' => $numRandomFinal,
        ];
        $idClient = $clientModel->insert($client, true);

        $demandeModel = model('Demande');
        $demandeModel->update($currentId, [
            'IDCLIENT' => $idClient,
            'ETAT' => 'validee',
        ]);

        $ctModel = model('CTModel');
        $idCT = $ctModel->insert([
            'DATECT' => $this->request->getPost('date') ,
            'HEURE' => $this->request->getPost('heure'),
        ]);

        $possedeModel = model('PossedeModel');
        $possedeModel->insert([
            'IDVEHICULE' => null,
            'IDCLIENT' => $idClient,
            'IDCT' => $idCT,
            'NUMCHASSIS' => $this->request->getPost('chassis'),
            'IMAT' => $this->request->getPost('immatriculation'),
            'ANNEE' => $this->request->getPost('annee')
        ]);
        return redirect()->to(url_to('admin-liste-demandes-en-attentes'));
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
