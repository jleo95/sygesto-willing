<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 03/11/2018
 * Time: 15:13
 */

namespace App\Models;


use App\Core\Model;

class CommandeModel extends Model
{

    public function __construct()
    {
        parent::__construct();
        $this->id = 'cmdid';
    }


    public function my_commande($iduser, $one = FALSE)
    {
        $query = $this->makeQuery()
                ->select($this->table . '.*', 'c.*')
                ->where('cmdrealiserpar = ?')
                ->join('clients as c', 'cmdclient = cliid');
        return $this->execute($query, [$iduser], $one);
    }

    /**
     * recuperer les commandes en fonction des periode
     * @param string $start debut de la periode
     * @param string $end fin de la periode
     * @param string $orderBy
     * @return array|bool|mixed|\PDOStatement
     */
    public function get_by_periode($start = '', $end = '', $orderBy = '')
    {
        if (empty($start)) {
            $start = date('Y-m-d', time()) . ' 00:00:00';
        }
        if (empty($end)) {
            $end = date('Y-m-d', time()) . ' 23:59:59';
        }
        $query = $this->makeQuery()
                ->where('cmddate BETWEEN ? AND ?');

        if (empty($orderBy)) {
            $orderBy = 'cmddate DESC';
        }
        $query = $query->order($orderBy);
        return $this->execute($query, [$start, $end]);

    }

    protected function findByQuery(?string $nameFields = null, ?array $fields = null, ?string $orderBy = null)
   {
       $fields = [$this->table . '.*', 'c.*'];
       return parent::findByQuery($nameFields, $fields, $orderBy)
                ->join('clients c', 'cmdclient = cliid');
   }
}