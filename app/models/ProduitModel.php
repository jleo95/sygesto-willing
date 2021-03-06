<?php

/**
 * Description of ProduitModel
 *
 * @author LEOBA
 */




namespace App\Models;

use App\Core\Model;

class ProduitModel extends Model {
    //put your code here

    public function __construct()
    {
        parent::__construct();
        $this->table = 'produits';
        $this->id = 'proid';
    }



    /**
     * @param $temps int le temps est fixé en terme de mois par exemple 3, ou 4, 8 ... (mois)
     * @param $expire bool on decide de chosir aussi les produit expirer ou pas
     * @param $jumule bool si on veut jumuler les produits expirés avec ceux proche à expirés
     * @return array||bool
     */
    public function produit_by_peremtion($temps = 3, $expire = true, $jumule = false )
    {
        $start = getStartingDay();
        $end = '';

        if (empty($date)) {
            $date = new \Datetime($start);
            $end = $date->modify("+" . $temps . " month -1 day")->format('Y-m-d');
        }

        $start .= ' 00:00';
        $end .= ' 23:59';

        $query = $this->makeQuery()
                ->join('famailles', 'profamille = famid')
                ->join('fournisseurs', 'fouid = profournisseur')
                ->join('unitemesure', 'prounitemessure = uniid');

        if (!empty($orderBy)) {
            $query = $query->order($orderBy);
        }

        $data = [$start, $end];
        if ($expire && !$jumule) {
            $query->where('prodatePeremption <= ?');
            $data = [$end];
        }elseif ($expire && $jumule) {
            $query = $query->where('(prodatePeremption BETWEEN ? AND ? OR prodatePeremption <= ? )');
            $data [] = $end;
        }
        else {
            $query = $query->where('prodatePeremption BETWEEN ? AND ?');
        }
        return $this->execute($query, $data);
    }

    protected function findAllQuery(?array $fields = [], ?string $orderBy = null)
    {
        $fields = explode(',', 'proid, prodesignation, proreference,' .
            'pronbproduitBlog, proseuilalert, unilibelle unite, uniabv, uniid unite_id, famlibelle famille, famid famille_id, famembalageBlog embalage, ' .
            'founom, fouprenom');
        return parent::findAllQuery($fields, $orderBy)
            ->join('familles', 'profamille = famid')
            ->join('fournisseurs', 'fouid = profournisseur')
            ->join('unitemesure', 'prounitemessure = uniid');
    }


    protected function findByQuery(?string $nameFields = null, ?array $fields = null, ?string $orderBy = null)
    {
        $fields = explode(',',
            'produits.*, unilibelle unite, uniabv, famlibelle famille, famembalageBlog embalage, ' .
            'founom, fouprenom');
        $query = parent::findByQuery($nameFields, $fields, $orderBy)
            ->join('familles', 'profamille = famid')
            ->join('fournisseurs', 'fouid = profournisseur')
            ->join('unitemesure', 'prounitemessure = uniid');
        if (!empty($orderBy)) {
            $query = $query->order($orderBy);
        }
        return $query;
    }
}
