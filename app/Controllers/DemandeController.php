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

        $vehiculeModel = model('VehiculeModel');
        $marques = $vehiculeModel->distinct()->select('MARQUE')->orderBy('MARQUE', 'ASC')->findAll();
        $vehicules = $vehiculeModel->select('MARQUE, MODELE')->orderBy('MARQUE', 'ASC')->orderBy('MODELE', 'ASC')->findAll();

        $status = $this->request->getGet('status') ?? 'attente';
        return view('dashboard/GestionDemandes.php', ['clients' => $demandes, 'status' => $status, 'marques' => $marques, 'vehicules' => $vehicules]);
    }

    public function delete(int $id)
    {
        $status = $this->request->getGet('status') ?? 'attente';
        $demandeModel = model('Demande');
        $demandeModel->delete($id);
        return redirect()->to(url_to('admin-liste-demandes-en-attentes') . '?status=' . $status)->with('success', 'Demande supprimée avec succès.');
    }

    public function deleteDemandeValide(int $id)
    {
        $status = $this->request->getGet('status') ?? 'validee';
        if (empty($id)) {
            return redirect()->to(url_to('admin-liste-demandes-en-attentes') . '?status=' . $status)->with('error', 'ID de la demande invalide.');
        }
        $clientModel = model('ClientModel');
        $clientModel->deleteAllById($id);
        return redirect()->to(url_to('admin-liste-demandes-en-attentes') . '?status=' . $status)->with('success', 'Demande et données associées supprimées avec succès.');
    }

    public function update()
    {
        // Prélever les champs nécessaires
        $idType = (string) ($this->request->getPost('id_type') ?? '');
        $postedId = $this->request->getPost('id');
        $postedIdDemande = $this->request->getPost('iddemande');
        $currentId = ($idType === 'client') ? ($postedIdDemande ?: $postedId) : $postedId; // fallback au besoin

        $immat = trim((string) ($this->request->getPost('immatriculation') ?? ''));
        $annee = trim((string) ($this->request->getPost('annee') ?? ''));
        $chassis = trim((string) ($this->request->getPost('chassis') ?? ''));
        $date = $this->request->getPost('date');
        $heure = $this->request->getPost('heure');

        // Scénario partiel: si AU MOINS un des 3 (IMAT/ANNEE/CHASSIS) manque et que date/heure sont fournis
        $isPartial = ((empty($immat) || empty($annee) || empty($chassis)) && !empty($date) && !empty($heure));
        if ($isPartial) {
            // On doit créer/mettre à jour Client, CT et Possede même sans IMAT/ANNEE/CHASSIS (mis à null)
            // 1) Véhicule: vérifier ou créer si nouveau
            $vehiculeModel = model('VehiculeModel');
            $marque = trim((string) ($this->request->getPost('marque') ?? ''));
            $modele = trim((string) ($this->request->getPost('modele') ?? ''));
            $vehiculeExisting = $vehiculeModel->findVehicule($marque, $modele);
            if (!$vehiculeExisting) {
                // Créer le véhicule s'il n'existe pas
                $idVehicule = $vehiculeModel->insert([
                    'MARQUE' => $marque,
                    'MODELE' => $modele,
                ], true);
                if (!$idVehicule) {
                    return redirect()->back()->withInput()->with('error', 'Erreur lors de la création du véhicule.');
                }
            } else {
                $idVehicule = $vehiculeExisting['IDVEHICULE'];
            }

            $db = \Config\Database::connect();
            $db->transStart();

            // 2) Client: réutiliser si déjà lié à la demande, sinon créer
            $demandeModel = model('Demande');
            $demandeRow = $demandeModel->find($currentId);
            $existingClientId = $demandeRow['IDCLIENT'] ?? null;
            $clientModel = model('ClientModel');

            $numRandomPost = strtoupper(trim((string) ($this->request->getPost('numrandom') ?? '')));
            if (!$this->isValidClientNumber($numRandomPost) || $this->numRandomExists($numRandomPost, $existingClientId)) {
                $numRandomFinal = $this->generateUniqueClientNumber($existingClientId);
            } else {
                $numRandomFinal = $numRandomPost;
            }

            $clientData = [
                'NOM' => $this->request->getPost('nom'),
                'PRENOM' => $this->request->getPost('prenom'),
                'EMAIL' => $this->request->getPost('email'),
                'TEL' => $this->request->getPost('telephone'),
                'NUMRANDOM' => $numRandomFinal,
            ];
            if (!empty($existingClientId)) {
                $clientData['IDCLIENT'] = $existingClientId;
                $clientModel->save($clientData);
                $idClient = $existingClientId;
            } else {
                $idClient = $clientModel->insert($clientData, true);
            }

            // 3) CT: réutiliser si un Possede existe déjà (prendre son IDCT), sinon créer
            $possedeModel = model('PossedeModel');
            $existingPossede = $possedeModel->where('IDCLIENT', $idClient)->where('IDVEHICULE', $idVehicule)->first();
            $ctModel = model('CTModel');
            $idCT = null;
            if ($existingPossede) {
                $idCT = $existingPossede['IDCT'] ?? null;
                if ($idCT && !empty($date) && !empty($heure)) {
                    $ctModel->save([
                        'IDCT' => $idCT,
                        'DATECT' => $date,
                        'HEURE' => $heure,
                    ]);
                }
            } else {
                // Créer un nouveau CT si date/heure fournis
                if (!empty($date) && !empty($heure)) {
                    $idCT = $ctModel->insert([
                        'DATECT' => $date,
                        'HEURE' => $heure,
                    ], true);
                }
            }

            // 4) Possede: créer si absent, sinon mettre à jour (avec nulls si vide)
            $possedeData = [
                'NUMCHASSIS' => ($chassis !== '' ? $chassis : null),
                'IMAT' => ($immat !== '' ? $immat : null),
                'ANNEE' => ($annee !== '' ? $annee : null),
            ];
            if ($existingPossede) {
                // Mise à jour par conditions si la clé primaire est inconnue
                $possedeModel->where('IDCLIENT', $idClient)
                    ->where('IDVEHICULE', $idVehicule)
                    ->set($possedeData)
                    ->update();
            } else {
                $possedeModel->insert(array_merge([
                    'IDVEHICULE' => $idVehicule,
                    'IDCLIENT' => $idClient,
                    'IDCT' => $idCT,
                ], $possedeData));
            }

            // 5) Mettre à jour la demande (infos de base + IDCLIENT), sans changer ETAT
            $demandeUpdate = [
                'NOM' => $this->request->getPost('nom'),
                'PRENOM' => $this->request->getPost('prenom'),
                'EMAIL' => $this->request->getPost('email'),
                'TEL' => $this->request->getPost('telephone'),
                'MARQUE' => $this->request->getPost('marque'),
                'MODELE' => $this->request->getPost('modele'),
                'IDCLIENT' => $idClient,
            ];
            $demandeModel->update($currentId, $demandeUpdate);

            $db->transComplete();
            if ($db->transStatus() === false) {
                return redirect()->back()->withInput()->with('error', 'Erreur lors de l\'enregistrement partiel.');
            }
            return redirect()->back()->with('success', 'Les informations ont été mise à jour. Pour valider la demande, veuillez compléter les informations manquantes.');
        }

        // Flux complet (toutes infos remplies) -> validation et création des entités liées
        // Récupérer l'id du véhicule grâce à la marque et au modèle de la demande
        // Si introuvable, créer le véhicule
        $vehiculeModel = model('VehiculeModel');
        $marque = trim((string) ($this->request->getPost('marque') ?? ''));
        $modele = trim((string) ($this->request->getPost('modele') ?? ''));
        $vehiculeExisting = $vehiculeModel->findVehicule($marque, $modele);
        if ($vehiculeExisting) {
            $idVehicule = $vehiculeExisting['IDVEHICULE'];
        } else {
            // Créer le véhicule s'il n'existe pas
            $idVehicule = $vehiculeModel->insert([
                'MARQUE' => $marque,
                'MODELE' => $modele,
            ], true);
            if (!$idVehicule) {
                return redirect()->back()->withInput()->with('error', 'Erreur lors de la création du véhicule.');
            }
        }

        $db = \Config\Database::connect();
        $db->transStart();

        $demandeModel = model('Demande');
        $demandeRow = $demandeModel->find($currentId);
        $existingClientId = $demandeRow['IDCLIENT'] ?? null;

        $clientModel = model('ClientModel');
        $numRandomPost = strtoupper(trim((string) ($this->request->getPost('numrandom') ?? '')));
        if (!$this->isValidClientNumber($numRandomPost) || $this->numRandomExists($numRandomPost, $existingClientId)) {
            $numRandomFinal = $this->generateUniqueClientNumber($existingClientId);
        } else {
            $numRandomFinal = $numRandomPost;
        }

        $clientData = [
            'NOM' => $this->request->getPost('nom'),
            'PRENOM' => $this->request->getPost('prenom'),
            'EMAIL' => $this->request->getPost('email'),
            'TEL' => $this->request->getPost('telephone'),
            'NUMRANDOM' => $numRandomFinal,
        ];
        if (!empty($existingClientId)) {
            $clientData['IDCLIENT'] = $existingClientId;
            $clientModel->save($clientData);
            $idClient = $existingClientId;
        } else {
            $idClient = $clientModel->insert($clientData, true);
        }

        // Met à jour la demande (ETAT validée)
        $demandeModel->update($currentId, [
            'IDCLIENT' => $idClient,
            'ETAT' => 'validee',
        ]);

        // CT: réutiliser via possede si existant, sinon créer
        $possedeModel = model('PossedeModel');
        $existingPossede = $possedeModel->where('IDCLIENT', $idClient)->where('IDVEHICULE', $idVehicule)->first();
        $ctModel = model('CTModel');
        $idCT = null;
        if ($existingPossede) {
            $idCT = $existingPossede['IDCT'] ?? null;
            if ($idCT) {
                $ctModel->save([
                    'IDCT' => $idCT,
                    'DATECT' => $date,
                    'HEURE' => $heure,
                ]);
            } else {
                $idCT = $ctModel->insert([
                    'DATECT' => $date,
                    'HEURE' => $heure,
                ], true);
            }
        } else {
            $idCT = $ctModel->insert([
                'DATECT' => $date,
                'HEURE' => $heure,
            ], true);
        }

        // Possede: créer si absent, sinon mettre à jour
        $possedeData = [
            'NUMCHASSIS' => $chassis,
            'IMAT' => $immat,
            'ANNEE' => $annee,
        ];
        if ($existingPossede) {
            $possedeModel->where('IDCLIENT', $idClient)
                ->where('IDVEHICULE', $idVehicule)
                ->set(array_merge(['IDCT' => $idCT], $possedeData))
                ->update();
        } else {
            $possedeModel->insert(array_merge([
                'IDVEHICULE' => $idVehicule,
                'IDCLIENT' => $idClient,
                'IDCT' => $idCT,
            ], $possedeData));
        }

        $db->transComplete();

        if ($db->transStatus() === false) {
            return redirect()->back()->withInput()->with('error', "Une erreur est survenue lors de la validation de la demande.");
        }

        return redirect()->to(url_to('admin-liste-demandes-en-attentes') . '?status=validee')->with('success', 'Demande validée avec succès.');
    }

    public function updateToTerminee(int $id)
    {
        $demandeModel = model('Demande');
        $demande = $demandeModel->find($id);

        if (empty($demande) || empty($demande['IDCLIENT'])) {
            return redirect()->back()->with('error', 'Demande introuvable.');
        }

        // Récupérer l'IDCT via le client
        $possedeModel = model('PossedeModel');
        $possede = $possedeModel->where('IDCLIENT', $demande['IDCLIENT'])->first();

        if (empty($possede) || empty($possede['IDCT'])) {
            return redirect()->back()->with('error', 'Aucun contrôle technique associé à ce client.');
        }

        $idCT = $possede['IDCT'];

        // Générer NUMCT si inexistant
        $ctModel = model('CTModel');
        $ct = $ctModel->find($idCT);

        if (empty($ct['NUMCT'])) {
            // Générer un numéro unique basé sur l'année et un compteur
            $year = date('Y');
            $lastNum = $ctModel->where('NUMCT LIKE', $year . '%')
                ->orderBy('NUMCT', 'DESC')
                ->first();

            if ($lastNum && !empty($lastNum['NUMCT'])) {
                $lastNumInt = (int) substr($lastNum['NUMCT'], 4);
                $newNum = $year . str_pad($lastNumInt + 1, 6, '0', STR_PAD_LEFT);
            } else {
                $newNum = $year . '000001';
            }

            $ctModel->update($idCT, ['NUMCT' => (int)$newNum]);
        }

        // Rediriger vers la page de contrôle technique
        return redirect()->to(url_to('controle-technique', $id));
    }

    public function ajout()
    {
        $vehiculeModel = model('VehiculeModel');
        $marques = $vehiculeModel->distinct()->select('MARQUE')->orderBy('MARQUE', 'ASC')->findAll();
        $vehicules = $vehiculeModel->select('MARQUE, MODELE')->orderBy('MARQUE', 'ASC')->orderBy('MODELE', 'ASC')->findAll();
        return view('accueil', ['marques' => $marques, 'vehicules' => $vehicules]);
    }

    public function create()
    {
        $demandeModel = model('Demande');
        $demande = $this->request->getPost();
        $demande['MODELE'] = $this->request->getPost('modele');
        unset($demande['modele']);
        $demande['DATEDEMANDE'] = date('Y-m-d');
        $demande['ETAT'] = 'attente';
        $demandeModel->save($demande);
        $vehiculeModel = model('VehiculeModel');
        $marques = $vehiculeModel->distinct()->select('MARQUE')->orderBy('MARQUE', 'ASC')->findAll();
        // Fournit la liste des paires Marque/Modèle pour filtrer côté client
        $vehicules = $vehiculeModel->select('MARQUE, MODELE')->orderBy('MARQUE', 'ASC')->orderBy('MODELE', 'ASC')->findAll();
        return view('accueil', ['marques' => $marques, 'vehicules' => $vehicules]);
    }

    /**
     * API: retourne la disponibilité des créneaux CT, comptés à partir de la table CT.
     * Query params supportés:
     * - year, month (1-12) pour limiter au mois donné
     * Réponse: { "YYYY-MM-DD": { "HH:MM": count, ... }, ... }
     */
    public function ctAvailability()
    {
        $year = (int) ($this->request->getGet('year') ?? 0);
        $month = (int) ($this->request->getGet('month') ?? 0);

        $from = null;
        $to = null;
        if ($year > 0 && $month > 0) {
            $from = sprintf('%04d-%02d-01', $year, $month);
            $to = date('Y-m-t', strtotime($from));
        }

        $db = \Config\Database::connect();
        $builder = $db->table('CT')->select('DATECT, HEURE, COUNT(*) AS C')
            ->groupBy('DATECT, HEURE');
        if ($from && $to) {
            $builder->where('DATECT >=', $from)->where('DATECT <=', $to);
        }
        $rows = $builder->get()->getResultArray();

        $out = [];
        foreach ($rows as $r) {
            $d = $r['DATECT'] ?? '';
            $h = $r['HEURE'] ?? '';
            if (!$d || !$h) continue;
            // Normalise heure en HH:MM
            if (strlen($h) >= 5) $h = substr($h, 0, 5);
            if (!isset($out[$d])) $out[$d] = [];
            $out[$d][$h] = (int) ($r['C'] ?? 0);
        }
        return $this->response->setJSON($out);
    }

    public function mail()
    {
        $demandeModel = model('Demande');

        // Récupération des emails
        $emails = $demandeModel->select('email')->where('etat', 'attente')->findAll();

        if (empty($emails)) {
            return 'Aucun email trouvé.';
        }

        $content = implode("\n", array_column($emails, 'email'));
        $filename = 'emails_Clients_en_attentes' . date('Y-m-d') . '.txt';

        return $this->response->download($filename, $content);
    }

    public function redirect() {
        return view('redirectionaccueil');
    }
}
