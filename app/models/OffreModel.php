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


    protected function findByQuery(?string $nameFields = null, ?array $fields = null, ?string $orderBy = null)
    {
        $fields = [$this->table . '.*', 'f.*'];
        return parent::findByQuery($nameFields, $fields, $orderBy)
            ->select('off.offid, off.offdate as date, off.offdateLivraison livraison, off.offdescription description, fouid, CONCAT(founom, \' \', fouprenom) AS fournisseur, offs.offdescription as offshore')
            ->from($this->table . ' as off')
            ->join('fournisseurs four', 'fouid = offfournisseur')
            ->join('offshores offs', 'offs.offid = offshore');
    }


}