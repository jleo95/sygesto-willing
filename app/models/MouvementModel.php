<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 31/10/2018
 * Time: 11:16
 */

namespace App\Models;


use App\Core\Model;

class MouvementModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    protected function findAllQuery(?array $fields = [], ?string $orderBy = null)
    {
        return parent::findAllQuery($fields, $orderBy)
                ->select($this->table . '.*', 'pro.prodesignation')
                ->join('produits pro', 'proid = mvtproduit');
    }

}