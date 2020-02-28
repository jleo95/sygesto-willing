<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 19/12/2018
 * Time: 00:07
 */

namespace App\Controllers;


use App\Core\Controller;

class StatController extends Controller
{

    public function __construct()
    {
        parent::__construct();


        $this->dataVente = $this->Commandedetail->all();
    }

    public function ventes()
    {
        $this->layout->setTitle('Statistique de vente');
        $this->layout->setTitle('Statistique de vente par produit', 'v');
        $this->layout->render('stat' . DS . 'ventes');
    }

    public function expiration()
    {
        $produits = $this->Produit->produit_by_peremtion(8, false);
        $html     = '';

        foreach ($produits as $produit) {
            $html .= '<tr>' .
                        '<td>' . $produit->proid . '</td>' .
                        '<td>' . ucfirst($produit->prodesignation) . '</td>' .
                        '<td>' . $produit->famille . '</td>' .
                        '<td>' . $produit->prodatePeremption . '</td>' .
                        '<td class="text-danger" style="font-size: 12px;"> <i class="fa fa-minus"></i>' . 8 . ' mois</td>' .
                        '<td><a href="javascript: void()"><i class="fa fa-eye"></i></a></td>' .
                     '</tr>';
        }

        $this->layout->assign('produits', $html);
        $this->layout->setTitle('Peremption proche');
        $this->layout->setTitle('Expiration proche', 'v');
        $this->layout->setJS('stat' . DS . 'peremption');
        $this->layout->render('stat' . DS . 'expiration');
    }

    public function produitplus()
    {
        $ventes = $this->Commandedetail->group_by_produit();
        $id_existes = [];
        $tmp = [];

        foreach ($ventes as $vente) {
            if (in_array($vente->produit, $id_existes)) {
                $tmp[$vente->produit]->prixtotal += $vente->prixtotal;
                $tmp[$vente->produit]->quantite += $vente->quantite;
            }else {
                $id_existes[] = $vente->produit;
                $tmp[$vente->produit] = $vente;
            }

        }

        $tab_ventes = [];
        foreach ($tmp as $value) {
            $tab_ventes[] = $value;
        }
        $taile = count($tab_ventes);
        for ($i = 0; $i < $taile; $i ++) {
            $trouver = false;
            $vente = $tab_ventes[$i];
            $v = $tab_ventes[$i]->quantite;
            $j = $i;
            while ($j >= 1 && $v > $tab_ventes[$j - 1]->quantite) {
                $tab_ventes[$j] = $tab_ventes[$j - 1];
                $j --;
            }
            $tab_ventes[$j] = $vente;
        }
        $this->dataVente = $tab_ventes;

        $this->layout->setTitle('Les produits les plus vendus', 'p');
        $this->layout->setTitle('Stat. ventes');
        $this->layout->setJS('stat' . DS . 'ventes');
        $this->layout->assign('tbody', $this->tableVenteProduit());
        $this->layout->render('stat' . DS . 'produitplus');
    }


    private function autoLoadVentes()
    {
        $data = $this->dataVente;
        foreach ($data as $d) {
            $tab [] = [
                'id' => $d->produit,
                'name' => $d->prodesignation,
                'quante' => $d->produit,
                'prix' => $d->prixtotal,
                'taux' => ''
            ];
        }
        return $tab;
    }
    private function tableVenteProduit()
    {
        $data = $this->dataVente;
        $html = '';
        $i    = 0;

        if (count($data) > 10) {
            while ($i < 10) {
                $d = $data[$i++];
                $html .= '<tr>' .
                    "<td>$d->produit</td>" .
                    "<td>$d->prodesignation</td>" .
                    "<td>$d->quantite</td>" .
                    "<td>" . number_format($d->prixtotal, 2, ',', ' ') . " fcfa</td>" .
                    "<td></td>" .
                    '</tr>';
            }
        }else {
            foreach ($data as $d) {
                $html .= '<tr>' .
                    "<td>$d->produit</td>" .
                    "<td>$d->prodesignation</td>" .
                    "<td>$d->quantite</td>" .
                    "<td>" . number_format($d->prixtotal, 2, ',', ' ') . " fcfa</td>" .
                    "<td></td>" .
                    '</tr>';
            }
        }

        return $html;
    }

}