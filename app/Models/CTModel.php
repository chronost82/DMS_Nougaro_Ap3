<?php

namespace App\Models;

use CodeIgniter\Model;

class CTModel extends Model
{
    protected $table            = 'CT';
    protected $primaryKey       = 'IDCT';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDELEVE', 'COMMENTAIRE', 'CTENCOURS', 'NUMCT', 'DATECT', 'HEURE'];

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

    public function getCTWithDemande(int $idDemande): array
    {
        return $this->select('CT.*, CLIENT.NOM, CLIENT.PRENOM, POSSEDE.*, VEHICULE.*, DEMANDE.IDDEMANDE')
            ->join('POSSEDE', 'POSSEDE.IDCT = CT.IDCT')
            ->join('CLIENT', 'CLIENT.IDCLIENT = POSSEDE.IDCLIENT')
            ->join('VEHICULE', 'VEHICULE.IDVEHICULE = POSSEDE.IDVEHICULE')
            ->join('DEMANDE', 'DEMANDE.IDCLIENT = CLIENT.IDCLIENT')
            ->where('DEMANDE.IDDEMANDE', $idDemande)
            ->findAll();
    }

        public function getAllCTWithClient(): array
    {
        return $this->select('CT.*, CLIENT.NOM, CLIENT.PRENOM, POSSEDE.*, VEHICULE.*')
            ->join('POSSEDE', 'possede.IDCT = CT.IDCT')
            ->join('CLIENT', 'CLIENT.IDCLIENT = POSSEDE.IDCLIENT')
            ->join('VEHICULE', 'VEHICULE.IDVEHICULE = POSSEDE.IDVEHICULE')
            ->where('CT.CTENCOURS', 1)
            ->findAll();
    }
}