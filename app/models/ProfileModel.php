<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 28/10/2018
 * Time: 02:02
 */

namespace App\Models;


use App\Core\Model;

class ProfileModel extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->table = 'profile';
    }

}