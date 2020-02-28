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

    protected function findByQuery(?string $nameFields = null, ?array $fields = null, ?string $orderBy = null)
    {
        $fields = [$this->table . '.*', 'f.*'];
        return parent::findByQuery($nameFields, $fields, $orderBy)
            ->join('fournisseurs f', 'offfournisseur = fouid');
    }

}