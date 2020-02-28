<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 29/10/2018
 * Time: 02:53
 */

namespace App\Models;


use App\Core\Model;

class FamilleModel extends Model
{

    public function __construct()
    {
        parent::__construct();

        $this->id = 'famid';
    }

}