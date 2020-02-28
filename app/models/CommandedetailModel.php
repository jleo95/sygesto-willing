<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 03/11/2018
 * Time: 15:14
 */

namespace App\Models;


use App\Core\Model;

class CommandedetailModel extends Model
{

    public function __construct()
    {
        parent::__construct();

        $this->table = 'commande_detail';
        $this->records = null;
    }


    public function entrees_by_produit(int $idproduit): array
    {
//        if (is_null($this->records)) {
//            $query = $this->makeQuery()->from($this->table)
//                ->select($this->table . '.*', 'p.*')
//                ->join('produits as p', 'proid = ' . $this->table . '.produit')
//                ->where($this->table . '.produit = ?');
//            $this->records = $this->execute($query, [$idproduit]);
//        }
        $query = $this->makeQuery()->from($this->table)
            ->select($this->table . '.*', 'p.*')
            ->join('produits as p', 'p.proid = ' . $this->table . '.produit')
            ->where($this->table . '.produit = ?');
        return $this->execute($query, [$idproduit]);
    }

    public function stock(int $idproduit)
    {
        $offres_by_produit = $this->entrees_by_produit($idproduit);
        $quantite = 0;

        foreach ($offres_by_produit as $offre) {
            $quantite += $offre->quantite * 1;
        }

        return $quantite;
    }

    /**
     * recuperer les vente en fonction des periode
     * @param string $start debut de la periode
     * @param string $end fin de la periode
     * @param string $orderBy
     * @return array|bool|mixed|\PDOStatement
     */
    public function get_by_periode($start = '', $end = '', $orderBy = '')
    {
        if (empty($start)) {
            $start = date('Y-m-d', time()) . ' 00:00:00';
        } else {
            $start = date('Y-m-d', strtotime($start)) . ' 00:00:00';
        }
        if (empty($end)) {
            $end = date('Y-m-d', time()) . ' 23:59:59';
        } else {
            $end = date('Y-m-d', strtotime($end)) . ' 23:59:59';
        }

        $query = $this->makeQuery()
            ->select($this->table . '.*', 'p.*')
            ->join('produits as p', 'proid = produit')
            ->where('date BETWEEN ? AND ?');

        if (!empty($orderBy)) {
            $query = $query->order($orderBy);
        }
        return $this->execute($query, [$start, $end]);
    }

    /**
     * Recupere les benifice des commande passer sur une periode
     *
     * @param null|string $start
     * @param null|string $end
     * @return float|int
     */
    public function get_benefice_by_periode(?string $start = "", ?string $end = '')
    {
        return $this->get_benefice($this->get_by_periode($start, $end));
    }

    /**
     * Recupere les depense des commande passer sur une periode
     *
     * @param null|string $start
     * @param null|string $end
     * @return float|int
     */
    public function get_depense_by_periode(?string $start = "", ?string $end = '')
    {
        return $this->get_depense($this->get_by_periode($start, $end));
    }

    /**
     * Recupere le benefice d'une commande
     *
     * @param $icommande
     * @return float|int
     */
    public function get_benefice_by_commande($icommande)
    {
        return $this->get_benefice($this->get_by_commande($icommande));
    }

    /**
     * Recupere la depense d'une commande
     *
     * @param $icommande
     * @return float|int
     */
    public function get_depense_by_commande($icommande)
    {
        return $this->get_depense($this->get_by_commande($icommande));
    }


    /**
     * Recuperer le benefice de plusieur commandes par leur Id
     *
     * @param $listOfIdcommande
     * @return float|int
     */
    public function get_benefice_by_multi_commande($listOfIdcommande)
    {
        return $this->get_benefice($this->get_multi_commandes($listOfIdcommande));
    }

    /**
     * Recuperer la depense de plusieur commandes par leur Id
     *
     * @param $listOfIdcommande
     * @return float|int
     */
    public function get_depense_by_multi_commande($listOfIdcommande)
    {
        return $this->get_depense($this->get_multi_commandes($listOfIdcommande));
    }



    /**
     * Recupere les details d'une commande par son id
     * @param $idcommande
     * @return array|bool|mixed|\PDOStatement
     */
    public function get_by_commande($idcommande)
    {
        $query = $this->makeQuery()
                ->select($this->table . '.*', 'p.*')
                ->where('commande = ?')
                ->join('produits p', 'proid = produit');
        return $this->execute($query, [$idcommande]);
    }

    public function group_by_produit()
    {
        $stemement = 'SELECT ' . $this->table . '.*, produits.* FROM ' . $this->table . ' JOIN produits ' .
            'WHERE proid = produit';
        return $this->execute($stemement);
    }

    protected function findAllQuery(?array $fields = [], ?string $orderBy = null)
    {
        if (empty($orderBy)) {
            $orderBy = 'date DESC';
        }
        $fields = [$this->table . '.*', 'p.*'];
        return parent::findAllQuery($fields, $orderBy)
            ->join('produits as p', 'proid = produit');
    }

    /**Calcul le benefice d'une liste de commande passer en parametre
     *
     * @param $commandes listes des commandes a calucluer le benefice
     * @return int|float le benefice (la difference entre le prix de vente et le prix achat
     */
    private function get_benefice($commandes)
    {
        $prixAchat = 0;
        $prixVente = 0;
        foreach ($commandes as $commande ) {
            $prixAchat += intval($commande->proprixUnitAchat) * intval($commande->quantite);
            $prixVente += intval($commande->proprixUnitVente) * intval($commande->quantite);
        }
        return $prixVente - $prixAchat;
    }

    /**
     * Recupere les depense d'une liste de commandes
     *
     * @param $commandes
     * @return float|int
     */
    private function get_depense($commandes)
    {
        $prixAchat = 0;
        foreach ($commandes as $commande ) {
            $prixAchat += intval($commande->proprixUnitAchat) * intval($commande->quantite);
        }
        return $prixAchat;
    }

    /**
     * Recupere plusieur par leur id
     *
     * @param array $listOfIdcommande
     * @return array|bool|mixed|\PDOStatement
     */
    private function get_multi_commandes(array $listOfIdcommande)
    {
        $values = [];
        for ($i = 0; $i < count($listOfIdcommande); $i ++) {
            $values[] = '?';
        }
        $values = join($values, ', ');
        $query = $this->makeQuery()
            ->select($this->table . '.*', 'p.*')
            ->where('commande IN (' . $values . ')')
            ->join('produits p', 'proid = produit');
        return $this->execute($query, $listOfIdcommande);
    }

}