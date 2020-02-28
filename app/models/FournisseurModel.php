<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 29/10/2018
 * Time: 02:27
 */

namespace App\Models;


use App\Core\Model;

class FournisseurModel extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'fournisseurs';
    }

}