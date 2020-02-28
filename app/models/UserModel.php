<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 28/10/2018
 * Time: 03:12
 */

namespace App\Models;


use App\Core\Model;

class UserModel extends Model
{

    public function __construct()
    {
        parent::__construct();

        $this->table = 'users';
    }

    protected function findAllQuery(?array $fields = [], ?string $orderBy = null)
    {
        $fields = [$this->table . '.*', 'profile.*'];
        if (empty($orderBy)) {
            $orderBy = 'prfid DESC';
        }
        return parent::findAllQuery($fields, $orderBy)
                ->join('profile', 'useprofile = prfid');
    }
}