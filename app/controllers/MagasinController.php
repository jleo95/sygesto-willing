<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 10/11/2018
 * Time: 23:16
 */

namespace App\Controllers;


use App\Core\Controller;

class MagasinController extends Controller
{
    private $entrees;
    private $sorties;

    public function __construct()
    {
        parent::__construct();

        $this->entrees = $this->Offredetail->all();
        $this->sorties = $this->Mouvement->all([], 'mvtid DESC');
    }

    public function mouvement()
    {
        $this->layout->assign('produits', $this->Produit->all());
        $this->layout->assign('familles', $this->Famille->all());
        $this->layout->assign('entrees', $this->tableEntree());
        $this->layout->assign('sorties', $this->tableSortie());
        $this->layout->setTitle('Magasin');
        $this->layout->setJS('magasin' . DS . 'index');
        $this->layout->setStyle('magasin' . DS . 'magasin');
        $this->layout->render('magasin' . DS . 'index');
    }

    public function stock()
    {
        $html       = '';
        $produits   = $this->Produit->all();
        $prixtotaux = 0;
        $depenses   = 0;

        foreach ($produits as $produit) {
            $stock = $this->stock_magasin_by_produit($produit->proid);
            if ($this->stock_magasin_by_produit($produit->proid) > $produit->proseuilalert) {
                $etat = '<span class="text-success" style="font-size: 12.5px;">disponible</span>';
            }elseif ($this->stock_magasin_by_produit($produit->proid) > 0 && $this->stock_magasin_by_produit($produit->proid) <= $produit->proseuilalert) {
                $etat = '<span class="text-warning" style="font-size: 12.5px;">en alert</span>';
            }else {
                $stock = '<span style="font-style: italic; font-size: 13px;">/</span>';
                $etat = '<span class="text-danger" style="font-size: 12.5px;">en rupture</span>';
            }
            $html .= '<tr>' .
                '<td>' . $produit->proid . '</td>' .
                '<td>' . ucfirst($produit->prodesignation) . '</td>' .
                '<td>' . $produit->famille . '</td>' .
                '<td>' . $stock . '</td>' .
                '<td>' . number_format($this->stock_magasin_by_produit($produit->proid) * $produit->proprixUnitVente, 2, ',', ' ') . '</td>' .
                '<td>' . $etat . '</td>' .
                '</tr>';

            $prixtotaux += $this->stock_magasin_by_produit($produit->proid) * $produit->proprixUnitVente;
            $depenses += $this->stock_magasin_by_produit($produit->proid) * $produit->proprixUnitAchat;
        }

        $this->layout->assign('prixtotaux', number_format($prixtotaux, 2, ',', ' '));
        $this->layout->assign('depenses', number_format($depenses, 2, ',', ' '));
        $this->layout->assign('produits', $html);

        $this->layout->setTitle('Stock magasin', 'p');
        $this->layout->setTitle('Stock magasin');
        $this->layout->setJS('magasin' . DS . 'stock');
        $this->layout->setStyle('magasin' . DS . 'magasin');
        $this->layout->render('magasin' . DS . 'stock');
    }

    public function trieEntree1()
    {
        $idTrie = intval($this->input->idTrie);
        $entrees = [];

        if ($idTrie == 1) {
            $tmps = $this->Offredetail->all([], 'date DESC');
            $date = date('Y-m-d', time());
            $entrees = [];

            foreach ($tmps as $tmp) {
                if (date('Y-m-d', strtotime($tmp->date)) == $date) {
                    $entrees [] = $tmp;
                }
            }
            $this->entrees = $this->Offredetail->get_by_periode();
        } elseif ($idTrie == 2) {

            $moth = date('m');
            $year = date('Y');
            $date = strtotime($year . '-' . $moth . '-01');
            
            $this->entrees = $this->Offredetail->get_by_periode($date . ' 00:00:00', getLastDay());
        } elseif ($idTrie == 3) {
            $moth = date('m');
            $year = date('Y');
            $date = strtotime($year . '-' . $moth . '-01');
            
            $this->entrees = $this->Offredetail->get_by_periode($year . '-01-01 00:00:00', $year . '-12-31 23:59:59');
        }

        echo json_encode([
            'tbodyTableEntree' => $this->autoLoadEntree()
        ]);
    }

    public function trieSortie1()
    {
        $idTrie = intval($this->input->idTrie);
        $sorties = [];

        if ($idTrie == 1) {
            $tmps = $this->Mouvement->all([], 'mvtdate DESC');
            $date = date('Y-m-d', time());
            $this->sorties = $this->Mouvement->get_by_periode();
        } elseif ($idTrie == 2) {
            $moth = date('m');
            $year = date('Y');
            $date = strtotime($year . '-' . $moth . '-01');

            $this->sorties = $this->Mouvement->get_by_periode($date, getLastDay());
        } elseif ($idTrie == 3) {
            $year = date('Y');
            $this->sorties = $this->Mouvement->get_by_periode($year . '-01-01 00:00:00', $year . '-12-31 23:59:59');
        }

        echo json_encode([
            'tbodyTableSortie' => $this->autoLoadSortie()
        ]);
    }

    public function imprimerStock() {
        $idprint = intval($this->input->idPrinter);
        $tmps = $this->Produit->all();
        $produits = [];

        if ($idprint == 1) {
            foreach ($tmps as $v) {
                if ($this->stock_magasin_by_produit($v->proid) > 0 AND $this->stock_magasin_by_produit($v->proid) > $v->proseuilalert) {
                    $produits [] = $v;
                }
            }
        }elseif ($idprint == 2) {
            foreach ($tmps as $v) {
                if ($this->stock_magasin_by_produit($v->proid) > 0 AND $this->stock_magasin_by_produit($v->proid) <= $v->proseuilalert) {
                    $produits [] = $v;
                }
            }
        } else {
            foreach ($tmps as $v) {
                if ($this->stock_magasin_by_produit($v->proid) === 0) {
                    $produits [] = $v;
                }
            }
        }

        $this->layout->assign('produits', $produits);
        $this->layout->assign('pdf', new \App\Core\PDF());
        $this->layout->assign('stock', $this);
        $this->layout->assign('printable', $idprint);
        $this->layout->render('magasin' . DS . 'impression' . DS . 'stock_1', TRUE);
    }

    public function triByProduit() {
        $idproduit = intval($this->input->idProduit);
        $idTrie    = intval($this->input->idTri);
        $data      = [];

        if ($idTrie == 1 ) {
            $this->entrees = $this->Offredetail->entrees_by_produit_all($idproduit);
            $data = [
                'tbodyTableEntree' => $this->autoLoadEntree(),
                'output' => 1
            ];
        } else {
            $this->sorties = $this->Mouvement->sorties_by_produit($idproduit);
            $data = [
                'tbodyTableSortie' => $this->autoLoadSortie(),
                'output' => 2
            ];
        }

        echo json_encode($data);
    }

    public function imprimer()
    {
        $pdf       = new \App\Core\PDF();
        $tmps      = [];
        $tmps      = $this->Produit->all();
        $produits  = [];
        $printable = $_POST['idPrinter'];

        if ($printable == 1) {
            foreach ($tmps as $v) {
                if ($this->stock_magasin_by_produit($v->proid) > 0) {
                    $produits [] = $v;
                }
            }
        }elseif ($printable == 2) {
            foreach ($tmps as $v) {
                if ($this->stock_magasin_by_produit($v->proid) > 0 && $this->stock_magasin_by_produit($v->proid) <= $v->proseuilalert) {
                    $produits [] = $v;
                }
            }
        }else{
            foreach ($tmps as $v) {
                if ($this->stock_magasin_by_produit($v->proid) <= 0) {
                    $produits [] = $v;
                }
            }
        }

        $this->layout->assign('pdf', $pdf);
        $this->layout->assign('stock_function', $this);
        $this->layout->assign('pdf', $pdf);
        $this->layout->assign('produits', $produits);
        $this->layout->assign('printable', $printable);
        $this->layout->render('magasin' . DS . 'impression' . DS . 'stock', TRUE);
    }

    private function tableEntree()
    {
        $entrees = $this->entrees;
        $html    = '';
        $date    = '';
        foreach ($entrees as $entree) {
            if (date('Y-m-d', strtotime($entree->date)) === date('Y-m-d')) {
                $date = date('H:i:s', strtotime($entree->date));
            } else {
                $date = $entree->date;
            }
            $html .= '<tr>' .
                '<td>' . $entree->proid . '</td>'.
                '<td>' . ucfirst($entree->prodesignation) . '</td>'.
                '<td><span class="text-success"><i class="fa fa-sign-out"></i>Entrée</span></td>'.
                '<td><span class="text-success"><i class="fa fa-plus" style="font-size: 10px;"></i>' . $entree->quantite . '</span></td>'.
                '<td>' . $date . '</td>'.
                '</tr>';
        }
        return $html;
    }

    private function tableSortie()
    {
        $sorties = $this->sorties;
        $html    = '';
        $date    = '';
        foreach ($sorties as $sortie) {
            if (date('Y-m-d', strtotime($sortie->mvtdate)) === date('Y-m-d')) {
                $date = date('H:i:s', strtotime($sortie->mvtdate));
            } else {
                $date = $sortie->mvtdate;
            }

            $html .= '<tr>' .
                '<td>' . $sortie->proid . '</td>'.
                '<td>' . ucfirst($sortie->prodesignation) . '</td>'.
                '<td><span class="text-danger"><i class="fa fa-sign-out"></i>Sorite</span></td>'.
                '<td><span class="text-danger"><i class="fa fa-minus" style="font-size: 10px;"></i>' . $sortie->mvtquantite . '</span></td>'.
                '<td>' . $date . '</td>'.
                '</tr>';
        }
        return $html;
    }

    private function autoLoadEntree()
    {
        $entrees    = $this->entrees;
        $mouvements = [];
        $date       = '';
        foreach ($entrees as $entree) {
            if (date('Y-m-d', strtotime($entree->date)) === date('Y-m-d')) {
                $date = date('H:i:s', strtotime($entree->date));
            } else {
                $date = $entree->date;
            }

            $mouvements [] = [
                'id' => $entree->proid,
                'name' => ucfirst($entree->prodesignation),
                'mouvement' => '<span class="text-success"><i class="fa fa-sign-out"></i>Entrée</span>',
                'quantite' => '<span class="text-success"><i class="fa fa-plus" style="font-size: 10px;"></i>' . $entree->quantite . '</span>',
                'date' => $date
            ];
        }
        return $mouvements;
    }

    private function autoLoadSortie()
    {
        $sorties = $this->sorties;
        $mouvements = [];
        $date       = '';

        foreach ($sorties as $sortie) {
            if (date('Y-m-d', strtotime($sortie->mvtdate)) === date('Y-m-d')) {
                $date = date('H:i:s', strtotime($sortie->mvtdate));
            } else {
                $date = $sortie->mvtdate;
            }
            $mouvements [] = [
                'id' => $sortie->proid,
                'name' => ucfirst($sortie->prodesignation),
                'mouvement' => '<i class="fa fa-sign-out"></i>Sorite</span>',
                'quantite' => '<span class="text-danger"><i class="fa fa-minus" style="font-size: 10px;"></i>' . $sortie->mvtquantite . '</span>',
                'date' => $date
            ];
        }
        return $mouvements;
    }

}