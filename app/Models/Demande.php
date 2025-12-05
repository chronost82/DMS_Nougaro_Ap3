<?php

namespace App\Models;

use CodeIgniter\Model;

class DEMANDE extends Model
{
    protected $table            = 'DEMANDE';
    protected $primaryKey       = 'IDDEMANDE';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['NOM', 'PRENOM', 'EMAIL', 'TEL', 'MARQUE', 'MODELE', 'ETAT', 'IDCLIENT', 'DATEDEMANDE'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function findJoinAll(): array
    {
        // Sous-requête: dernière ligne POSSEDE par client (basée sur IDCT max)
        $sub = 'SELECT p.* FROM POSSEDE p INNER JOIN (SELECT IDCLIENT, MAX(IDCT) AS max_idct FROM POSSEDE GROUP BY IDCLIENT) last ON last.IDCLIENT = p.IDCLIENT AND last.max_idct = p.IDCT';

        return $this->select('DEMANDE.IDDEMANDE AS IDDEMANDE, DEMANDE.IDCLIENT, DEMANDE.NOM, DEMANDE.PRENOM, DEMANDE.EMAIL, DEMANDE.TEL, DEMANDE.MARQUE, DEMANDE.MODELE, DEMANDE.DATEDEMANDE, DEMANDE.ETAT, LP.IMAT as IMMATRICULATION, LP.ANNEE, LP.NUMCHASSIS as CHASSIS, CLIENT.NUMRANDOM, CT.DATECT AS DATE, CT.HEURE AS HEURE, CT.CTENCOURS, CT.IDCT')
            ->join('CLIENT', 'DEMANDE.IDCLIENT = CLIENT.IDCLIENT', 'left')
            // Joint la dernière possession par client pour stabiliser l'affichage
            ->join("($sub) LP", 'LP.IDCLIENT = CLIENT.IDCLIENT', 'left', false)
            ->join('CT', 'CT.IDCT = LP.IDCT', 'left')
            ->orderBy('DEMANDE.DATEDEMANDE', 'ASC')
            ->findAll();
    }
}
