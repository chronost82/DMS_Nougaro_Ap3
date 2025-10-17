<?php

namespace App\Models;

use CodeIgniter\Model;

class PossedeModel extends Model
{
    protected $table            = 'POSSEDE';
    protected $primaryKey       = 'IDCLIENT, IDVEHICULE, IDCT';
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
        return $this->select('possede.IDCLIENT, possede.IDVEHICULE, possede.IDCT, client.NOM as client, vehicule.MODELE as vehicule, controle_technique.DATECT as controle_technique')
            ->join('client', 'client.IDCLIENT = possede.IDCLIENT', 'left')
            ->join('vehicule', 'vehicule.IDVEHICULE = possede.IDVEHICULE', 'left')
            ->join('ct', 'ct.IDCT = possede.IDCT', 'left')
            ->findAll();
    }
}
