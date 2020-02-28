<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 28/10/2018
 * Time: 14:54
 */

namespace App\Models;


use App\Core\Model;

class MenuModel extends Model
{

    public function __construct()
    {
        parent::__construct();
    }


    public function get_by_module_droits($idgrpmenu, $ardroit = [])
    {
        $occdroit = [];
        for ($i = 0; $i < count($ardroit); $i ++ ) {
            $occdroit [] = '?';
        }
        $values = $ardroit;
        array_push($values, $idgrpmenu);
        array_push($values, 0);
        $query = $this->makeQuery()
                ->select( 'menid', 'menlibelle', 'menhref', 'menicone', 'mengroupe', 'menrole', 'grporder')
                ->join('groupemenu g', 'mengroupe = grpid')
                ->where('menrole IN (' . implode($occdroit, ', ') . ')', 'mengroupe = ?', 'menverouillage = ?');

        return $this->execute($query, $values);
    }

    public function get_by_droits($ardroit = [])
    {
        $occdroit = [];
        for ($i = 0; $i < count($ardroit); $i ++ ) {
            $occdroit [] = '?';
        }
        $values = $ardroit;
        if (empty($values)) {
            $values = [00];
            $occdroit [] = '?';
        }
        $query = $this->makeQuery()
                ->select('menid', 'menlibelle', 'menhref', 'menicone', 'mengroupe', 'menrole')
                ->where('menrole IN (' . implode($occdroit, ', ') . ')')
                ->where('menverouillage = ?');
        array_push($values, 0);
//    die($statement);
        return $this->execute($query, $values);
    }

}