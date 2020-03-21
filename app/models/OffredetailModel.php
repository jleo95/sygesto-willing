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
            ->select($this->table . '.*', 'p.*', 'un.unilibelle unite')
            ->join('produits as p', $this->table . '.produit = proid')
            ->join('unitemesure as un', $this->table . '.unite = un.uniid')
            ->where('offre = ?');
        return $this->execute($query, [$idOffre]);
    }

    public function show_produit_by_offre_groupBy_famille($idOffre)
    {
        $query = $this->makeQuery()
            ->select('produit as produit_id', 'p.prodesignation produit', 'quantite', 'un.uniid as unite_id, un.unilibelle unite', 'f.famid as famille_id', 'f.famlibelle as famille')
            ->join('produits as p', $this->table . '.produit = proid')
            ->join('unitemesure as un', 'p.prounite = un.uniid')
            ->join('familles as f', 'f.famid = p.profamille')
            ->groupeBy('famille')
            ->where('offre = ?');
        return $this->execute($query, [$idOffre]);
    }

    public function show_offre_detail_by_offre($idOffre)
    {
        $query = $this->makeQuery()
            ->select('produit as produit_id', 'p.prodesignation produit', 'quantite', 'un.uniid as unite_id, un.unilibelle unite', 'f.famid as famille_id', 'f.famlibelle as famille')
            ->join('produits as p', $this->table . '.produit = proid')
            ->join('unitemesure as un', 'p.prounite = un.uniid')
            ->join('familles as f', 'f.famid = p.profamille')
            ->where('offre = ?');
        return $this->execute($query, [$idOffre]);
    }

    /**
     * Selectionner les detail d'une commande par rapport a l'id du produit et de la commande /// en calculant les quantite livree et restante
     * @param $idOffre
     * @param $produit
     * @return array|bool|mixed|\PDOStatement
     */
    public function show_offre_detail_by_offre_and_produit($idOffre, $produit)
    {
        $query = $this->makeQuery()
            ->select('produit as produit_id', 'p.prodesignation produit', 'quantite', 'un.uniid as unite_id, un.unilibelle unite',
                'f.famid as famille_id', 'f.famlibelle as famille', $this->table . '.offre, of.offdate as offre_date',
                'ofs.offid as offshore_id, ofs.offdescription offshore, ofs.offdatefin offshore_datefin')
            ->join('produits as p', $this->table . '.produit = proid')
            ->join('unitemesure as un', 'p.prounite = un.uniid')
            ->join('familles as f', 'f.famid = p.profamille')
            ->join('offres of', 'of.offid = offre')
            ->join('offshores ofs', 'of.offshore = ofs.offid')
            ->where('offre = ? and ' . $this->table . '.produit = ?');
        $detail = $this->execute($query, [$idOffre, $produit], $one = true);

        #recupere les les produit deja livree mais incomplet
        $livraisons = $this->execute(
                    $this->makeQuery()
                            ->from('livraisons')
                            ->select('livquantite quantite, livproduit produit, livoffre offre')
                            ->where('livoffre = ? and livproduit = ?'),
                    [$idOffre, $produit]);
//        $data = $this->execute($query);
        $tmp = [];
        $quantite = $detail->quantite;
        foreach ($livraisons as $livraison) {
            $quantite -= $livraison->quantite;
        }
        $detail->quantite_restante = $quantite;
//        var_dump($livraisons);
        return $detail;
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

    public function deletProduitFromCommande(int $idProduit)
    {
        return parent::delete_by('produit', $idProduit);
    }

    public function show_produit_byNotLivred()
    {
        $query = $this->makeQuery()
                ->select('off.produit produit_id, p.prodesignation produit', 'quantite', 'u.uniid unite_id, u.unilibelle unite', 'off.offre')
            ->from('offre_detail as off')
            ->join('produits p', 'p.proid = produit')
            ->join('unitemesure u', 'p.prounite = u.uniid');
//            ->join('offres o', 'o.offid = off.offre')
//            ->order('offre DESC');

        $livraisons = $this->execute($this->makeQuery()->from('livraisons')->select('livquantite quantite, livproduit produit, livoffre offre'));
        $data = $this->execute($query);
        $tmp = [];

        foreach ($data as $d) {
            foreach ($livraisons as $l) {
                if (intval($l->produit) == intval($d->produit_id) && intval($l->offre) == intval($d->offre)) {
                    $d->quantite = $d->quantite - $l->quantite;
                }
            }
            $tmp[] = $d;
        }
        return $data;
    }

    protected function findAllQuery(?array $fields = [], ?string $orderBy = null)
    {
        $fields = [$this->table . '.*', 'p.*'];
        $orderBy = 'date DESC';
        return parent::findAllQuery($fields, $orderBy)
            ->join('produits p', 'produit = proid');
    }
}