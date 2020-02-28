<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 03/11/2018
 * Time: 15:11
 */

namespace App\Models;


use App\Core\Model;

class OffredetailModel extends Model
{

    public function __construct()
    {
        parent::__construct();

        $this->table = 'offre_detail';
    }


    public function entrees_by_produit_all($idproduit)
    {
        $query = $this->makeQuery()
                ->select($this->table . '.*', 'offres.*', 'produits.*')
                ->join('offres', 'offre = offid')
                ->join('produits', 'produit = proid')
                ->where('produit = ?');
        return $this->execute($query, [$idproduit]);
    }

    public function entrees_by_produit_valide ($idproduit)
    {
        $query = $this->makeQuery()
            ->select($this->table . '.*', 'offres.*')
            ->join('offres', 'offre = offid')
            ->where('produit = ?')
            ->where('offvalidite = ?');
        return $this->execute($query, [$idproduit, 1]);
    }

    public function stock($idproduit)
    {
        $offres = $this->entrees_by_produit_valide($idproduit);
        $quantite = 0;

        $date = strtotime(date('Y-m-d H:i:s', time()));
        foreach ($offres as $offre) {
            
            if (strtotime($offre->offdateLivraison) < $date) {
                $quantite += $offre->quantite;
            }
//            if ($offre->offvalidite == 1) {
//                if (strtotime($offre->offdateLivraison) < $date) {
//                    $quantite += $offre->quantite;
//                }
//            }

        }

        return $quantite;
    }

    public function show_produit_by_offre($idOffre)
    {
        $query = $this->makeQuery()
            ->select($this->table . '.*', 'p.*')
            ->join('produits as p', $this->table . '.produit = proid')
            ->where('offre = ?');
        return $this->execute($query, [$idOffre]);
    }

    protected function findAllQuery(?array $fields = [], ?string $orderBy = null)
    {
        $fields = [$this->table . '.*', 'p.*'];
        $orderBy = 'date DESC';
        return parent::findAllQuery($fields, $orderBy)
                ->join('produits p', 'produit = proid');
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
                ->where('date BETWEEN ? AND ?')
                ->join('produits p', 'produit = proid')
                ->select($this->table . '.*', 'p.*');

        $statement = 'SELECT ' . $this->table . '.*, produits.* FROM ' .
            $this->table . ' JOIN produits WHERE produit = proid AND date BETWEEN ? AND ?';
        
        return $this->execute($query, [$start, $end]);
    }
}