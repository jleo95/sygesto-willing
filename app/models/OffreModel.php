<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 03/11/2018
 * Time: 15:10
 */

namespace App\Models;


use App\Core\Model;

class OffreModel extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->id = 'offid';
    }

    public function getCommandeNotLivreted()
    {
        $field = 'off.offid, off.offdateLivraison livraison, off.offdescription description, fouid, CONCAT(founom, \' \', fouprenom) AS fournisseur, ofs';
        $query = $this->makeQuery()
                    ->select('off.offid, off.offdate as date, off.offdateLivraison livraison, off.offdescription description, fouid, CONCAT(founom, \' \', fouprenom) AS fournisseur, offs.offdescription as offshore')
                    ->from($this->table . ' as off')
                    ->join('fournisseurs four', 'fouid = offfournisseur')
                    ->join('offshores offs', 'offs.offid = offshore')
                    ->where('off.' . $this->id . ' NOT IN (SELECT mvtid from mouvements)');
        return $this->execute($query);
    }

    protected function findAllQuery(?array $fields = [], ?string $orderBy = null){
        $query = $this->makeQuery()
            ->select('off.offid, off.offdate, off.offdescription, offs.offid as offshore_id, offs.offdescription as offshore, paie.paiid, paie.pailibelle')
            ->from($this->table . ' off')
            ->join('offshores offs', 'offs.offid = offshore')
            ->join('paiements paie', 'paie.paiid = offpaiement');

        if ($orderBy != null) {
            $query->order($orderBy);
        }
        return $query;
    }


    protected function findByQuery(?string $nameFields = null, ?array $fields = null, ?string $orderBy = null)
    {

        $query = $this->makeQuery()
            ->select('off.offid, off.offdate, off.offdescription, offs.offid as offshore_id, offs.offdescription as offshore, paie.paiid, paie.pailibelle')
            ->from($this->table . ' off')
            ->join('offshores offs', 'offs.offid = offshore')
            ->join('paiements paie', 'paie.paiid = offpaiement');

        $query->where($nameFields . ' = ?');

        if (!is_null($orderBy))
            $query->order($orderBy);

        return $query;
    }

}