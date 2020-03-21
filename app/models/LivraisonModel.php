<?php
/**
 * Created by PhpStorm.
 * User: antol
 * Date: 3/15/2020
 * Time: 12:53 AM
 */

namespace App\models;


use App\Core\Model;

class LivraisonModel extends Model
{

    protected $id = 'livid';

    public function all(?array $fields = null, ?string $orderBy = null)
    {
        $livraisons = parent::all($fields, $orderBy);
        $tmp = [];
        foreach ($livraisons as $livraison) {
            $query = $this->makeQuery()
                ->select('d.produit, d.offre, d.quantite')
                ->from('offre_detail d')
                ->where('produit = ? AND offre = ?');
            $details = $this->execute($query, [$livraison->produit_id, $livraison->offre], $one = true);
            $livraison->quantite_restante = $details->quantite - $livraison->quantite;
            $tmp[] = $livraison;
        }
        return $tmp;
    }


    /**
     * Reccuperer les livraisons par periode
     *
     * @param $start Date date de commencement
     * @param $end Date date de fin
     * @return array|bool|mixed|\PDOStatement
     */
    public function getByDate($start, $end)
    {
        if (empty($start))
            $start = date('Y-m-d', time()) . ' 00:00';
        if (empty($end))
            $end = date('Y-m-d', time()) . ' 23:59';

        $query = $this->makeQuery()
                        ->from($this->table)
                        ->select('livproduit produit_id', 'p.prodesignation produit',
                            'livquantite quantite',
                            'u.uniid unite_id, u.unilibelle unite', 'off.offshore offshore_id', 'offs.offdescription offshore, offs.offdatefin offshore_datef',
                            'off.offid offre, off.offdate offre_date')
                        ->join('produits p', 'p.proid = livproduit')
                        ->join('unitemesure u', 'u.uniid = p.prounite')
                        ->join('offres off', 'off.offid = livoffre')
                        ->join('offshores offs', 'offs.offid = off.offshore')
                        ->where('livdate BETWEEN ? AND ?');
        $livraisons = $this->execute($query, [$start, $end]);

        $tmp = [];
        foreach ($livraisons as $livraison) {
            $query = $this->makeQuery()
                ->select('d.produit, d.offre, d.quantite')
                ->from('offre_detail d')
                ->where('produit = ? AND offre = ?');
            $details = $this->execute($query, [$livraison->produit_id, $livraison->offre], $one = true);
            $livraison->quantite_restante = $details->quantite - $livraison->quantite;
            $tmp[] = $livraison;
        }
        return $tmp;
    }

    protected function findAllQuery(?array $fields = [], ?string $orderBy = null)
    {
        $query = $this->makeQuery()
                        ->from($this->table)
                        ->select('livproduit produit_id', 'p.prodesignation produit',
                            'livquantite quantite',
                            'u.uniid unite_id, u.unilibelle unite', 'off.offshore offshore_id', 'offs.offdescription offshore, offs.offdatefin offshore_datef',
                            'off.offid offre, off.offdate offre_date')
                        ->join('produits p', 'p.proid = livproduit')
                        ->join('unitemesure u', 'u.uniid = p.prounite')
                        ->join('offres off', 'off.offid = livoffre')
                        ->join('offshores offs', 'offs.offid = off.offshore');
//        echo $query;
//        die();
        return $query;
    }

}