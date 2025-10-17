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
    protected $protectFields    = false;
    protected $allowedFields    = [];

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
        return $this->select('DEMANDE.IDDEMANDE AS ID, DEMANDE.IDCLIENT, DEMANDE.NOM, DEMANDE.PRENOM, DEMANDE.EMAIL, DEMANDE.TEL, DEMANDE.MARQUE, DEMANDE.MODELE, DEMANDE.DATEDEMANDE, DEMANDE.ETAT, POSSEDE.IMAT as IMMATRICULATION, POSSEDE.ANNEE, POSSEDE.NUMCHASSIS as CHASSIS, CLIENT.NUMRANDOM')
            ->join('CLIENT', 'DEMANDE.IDCLIENT = CLIENT.IDCLIENT', 'left')
            ->join('POSSEDE', 'POSSEDE.IDCLIENT = CLIENT.IDCLIENT', 'left')
            ->orderBy('DEMANDE.DATEDEMANDE', 'ASC')
            ->findAll();
    }
}
