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

    public function sorties_by_produit($idproduit)
    {
        $query = $this->makeQuery()
                ->select( $this->table . '.*', 'produits.*', 'u.unilibelle unite', 'u.uniabv')
                ->where('mvtproduit = ?')
                ->join('produits', 'proid = mvtproduit')
                ->join('unitemesure u', 'prounitemessure = u.uniid', 'inner ');
        return $this->execute($query, [$idproduit]);
    }


    public function quanite_sortie_by_produit($idproduit)
    {
        $sorties  = $this->sorties_by_produit($idproduit);
        $quantite = 0;

        foreach ($sorties as $sortie) {
            $quantite += $sortie->mvtquantite;
        }

        return $quantite;
    }

    protected function findAllQuery(?array $fields = [], ?string $orderBy = null)
    {
        $fields = [$this->table . '.*', 'p.*', 'u.unilibelle unite', 'u.uniabv'];
        if (!empty($orderBy)) {
            $orderBy = 'mvtid DESC';
        }
        return parent::findAllQuery($fields, $orderBy)
            ->join('produits as p', 'proid = mvtproduit')
            ->join('unitemesure as u', 'prounitemessure = u.uniid');
    }

    public function get_by_periode($start = '', $end = '')
    {
        if (empty($start)) {
            $start = date('Y-m-d', time()) . ' 00:00';
        } else {
            $start = date('Y-m-d', strtotime($start)) . ' 00:00';
        }
        if (empty($end)) {
            $end = date('Y-m-d', time()) . ' 23:59';
        } else {
            $end = date('Y-m-d', strtotime($end)) . ' 23:59';
        }

        $query = $this->makeQuery()
                ->where('mvtdate BETWEEN ? AND ?')
                ->join('produits', 'proid = mvtproduit')
                ->join('unitemesure u', 'prounitemessure = u.uniid')
                ->select($this->table . '.*', 'produits.*', 'u.unilibelle unite', 'u.uniabv');

        $statement = 'SELECT ' . $this->table . '.*, produits.*, u.unilibelle unite, u.uniabv FROM ' .
            $this->table . ' JOIN produits JOIN unitemesure u ' .
            'WHERE prounitemessure = u.uniid AND proid = mvtproduit AND mvtdate BETWEEN ? AND ?';
        
        return $this->execute($query, [$start, $end]);
    }

}