<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 02/11/2018
 * Time: 03:19
 */

namespace App\Models;


use App\Core\Model;

class ClientModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Recuperer tout les clients
     *
     * @param array|null $fields les colonne a retourne
     * @param null|string $orderBy
     * @return \App\Core\Query
     */
   protected function findAllQuery(?array $fields = [], ?string $orderBy = null)
   {
       return parent::findAllQuery($fields, $orderBy)->join('categories_client as cat', 'catid = clicategory');
   }

}