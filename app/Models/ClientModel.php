<?php

namespace App\Models;

use CodeIgniter\Model;

class CLIENTModel extends Model
{
    protected $table            = 'CLIENT';
    protected $primaryKey       = 'IDCLIENT';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ["NOM", "PRENOM", "TEL", "EMAIL", "NUMRANDOM"];

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

    public function findJoinAllWithEtat(): array
    {
        return $this->select('CLIENT.IDCLIENT AS ID, CLIENT.NOM, CLIENT.PRENOM, CLIENT.EMAIL, CLIENT.TEL, CLIENT.NUMRANDOM, DEMANDE.ETAT as ETAT')
            ->join('DEMANDE', 'DEMANDE.IDCLIENT = CLIENT.IDCLIENT', 'left')
            ->orderBy('CLIENT.IDCLIENT', 'ASC')
            ->findAll();
    }

    public function deleteAllById(int $id): void
    {
        // Si le CT est en cours alors on ne peut pas supprimer le client
        $ctModel = model("CTModel");
        $ctEnCours = $ctModel->join('POSSEDE', 'POSSEDE.IDCT = CT.IDCT')->where('POSSEDE.IDCLIENT', $id)->where('CTENCOURS', 1)->first();
        if (!empty($ctEnCours)) {
            throw new \Exception("Impossible de supprimer le client car un contrôle technique est en cours.");
        }
        // Supprimer les demandes associées au client
        $demandeModel = model("Demande");
        $demandeModel->where('IDCLIENT', $id)->delete();

        // $possedeModel = model("PossedeModel");
        // $ctID = $possedeModel->select('IDCT')->where('IDCLIENT', $id)->first();
        // // dd($ctID);
        // $possedeModel->where('IDCLIENT', $id)->delete();

        // if (!empty($ctID)) {
        //     $ctModel = model("CTModel");
        //     $ctModel->where('IDCT', $ctID)->delete();
        // }
    }
}
