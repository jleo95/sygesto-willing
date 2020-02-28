<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 09/11/2018
 * Time: 13:07
 */

namespace App\Controllers;


use App\Core\Controller;
use App\Core\Router;

class BoutiqueController extends Controller
{

    private $entrees;
    private $sorties;

    public function __construct()
    {
        parent::__construct();
        $this->entrees = $this->Mouvement->all([], 'mvtid DESC');
        $this->sorties = $this->Commandedetail->all([], 'date DESC');
    }

    public function mouvement()
    {
        
        $this->layout->assign('produits', $this->Produit->all());
        $this->layout->assign('familles', $this->Famille->all());
        $this->layout->assign('entrees', $this->tableEntree());
        $this->layout->assign('sorties', $this->tableSortie());
        $this->layout->setTitle('Boutique');
        $this->layout->setTitle('Boutique', 'v');
        $this->layout->setJS('boutique' . DS . 'index');
        $this->layout->setStyle('boutique' . DS . 'boutique');
        $this->layout->render('boutique' . DS . 'index');
    }

    public function stock()
    {
        $html       = '';
        $produits   = $this->Produit->all();
        $prixtotaux = 0;
        $prix = 0;

        foreach ($produits as $produit) {
            $stock = $this->stock_boutique_by_produit($produit->proid);
            $prix = $stock;
            $unite = '';

            if ($stock > 0) {
                if ($produit->uniabv !== 'aucun' && $produit->uniabv !== 'Aucun' && $produit->uniabv !== '') {
                    $unite = $produit->uniabv;
                }
            }

            if ($stock > $produit->proseuilalert) {
                $status = '<span class="text-success" style="font-size: 12.5px;">disponible</span>';
            }elseif ($stock > 0 && $stock <= $produit->proseuilalert){
                $status = '<span class="text-warning" style="font-size: 12.5px;">en alert</span>';
            }else {
                $stock = '<span style="font-style: italic; font-size: 13px;">/</span>';
                $status = '<span class="text-danger" style="font-size: 12.5px;">en rupture</span>';
            }
            $quantite = 0;
            if (intval($this->stock_boutique_by_produit($produit->proid)) * 1 !== 0) {
                $quantite = (intval($this->stock_boutique_by_produit($produit->proid)) * 1) * $produit->proprixUnitVente;
            }

            $quantite = number_format($quantite, 2, ',', ' ');
            $html .= '<tr>' .
                        '<td>' . $produit->proid . '</td>' .
                        '<td>' . ucfirst($produit->prodesignation) . '</td>' .
                        '<td>' . ucfirst($produit->famille) . '</td>' .
                        '<td>' . $stock . ' ' . $unite . '</td>' .
                        '<td>' . $quantite . '</td>' .
                        '<td>' . $status . '</td>' .
                     '</tr>';

            $prixtotaux += $prix * $produit->proprixUnitVente;
        }

        $this->layout->assign('prixtotaux', number_format($prixtotaux, 2, ',', ' '));
        $this->layout->assign('produits', $html);

        $this->layout->setTitle('Stock boutique', 'p');
        $this->layout->setTitle('Stock boutique');
        $this->layout->setJS('boutique' . DS . 'stock');
        $this->layout->setStyle('boutique' . DS . 'boutique');
        $this->layout->render('boutique' . DS . 'stock');
    }

    public function entree()
    {
        if ($_POST) {
            $arrId = [];

            foreach ($this->session->entrees as $entree) {
                if (!in_array($entree['idProduit'], $arrId)) {
                    $arrId [] = $entree['idProduit'];
                }
            }

            $quantite = 0;
            $ent      = [];

            foreach ($arrId as $id) {
                $trouver = false;
                $quantite = 0;
                foreach ($this->session->entrees as $entree) {
                    if ($id == $entree['idProduit']) {
                        $trouver = true;
                        $quantite += $entree['quantite'];
                    }
                    if ($trouver) {
                        $ent['mvtproduit'] = $id;
                        $ent['mvtdate'] = $entree['date'];
                        $ent['mvtrealiserpar'] = $this->session->stkiduser;
                    }
                }
                $ent['mvtquantite'] = $quantite;

                $details [] = $ent;

                $this->Mouvement->insert($ent);
            }
            unset($_SESSION['entrees']);
            header('Location: ' . Router::url('boutique', 'mouvement'));
        }

        $this->layout->setTitle('Entrée');
        $this->layout->setTitle('Nouvelle entrée', 'v');
        $this->layout->setJS('boutique' . DS . 'index');
        $this->layout->setStyle('boutique' . DS . 'boutique');
        $this->layout->render('boutique' . DS . 'entree');
    }

    public function loadProduitForEntree()
    {
        $this->layout->assign('produits', $this->Produit->all([], 'proid DESC'));
        $this->layout->assign('stock_function', $this);
        echo json_encode([
            'bodyModal' => $this->layout->ajax('boutique' . DS . 'ajax' . DS . 'loadProduitForEntree')
        ]);
    }

    public function loadInfosProduitEntree()
    {
        $this->layout->assign('stock_function', $this);
        $this->layout->assign('produit', $this->Produit->get_by('proid', $this->input->idProduit));
        $html = $this->layout->ajax('boutique' . DS . 'ajax' . DS . 'loadInfosProduitEntree');
        echo json_encode([
            'bodyModal' => $html
        ]);
    }

    public function addInEntree()
    {
        $produit               = $this->Produit->get_by('proid', $this->input->idProduit);
        $numLine               = $this->input->line + 1;
        $_SESSION['entrees'][] = [
            'produit' => $produit->prodesignation,
            'famille' => $produit->prodesignation,
            'quantite' => $this->input->quantite,
            'idProduit' => $produit->proid,
            'date' => date('Y-m-d H:i:s', time())
        ];

        $line = '<tr class="' . $numLine . '">' .
                    '<td>' . $produit->proid . '</td>' .
                    '<td>' . ucfirst($produit->prodesignation) . '</td>' .
                    '<td>' . ucfirst($produit->famille) . '</td>' .
                    '<td>' . $this->input->quantite . '</td>' .
                    '<td><a href="javascript: removeProduitForEntree(' . $produit->proid . ', ' . $numLine . ')" class="btn abtn abtn-danger"><i class="fa fa-trash-o"></i></a></td>' .
                '</tr>';

        echo json_encode([
            'bodyTableProduitEntree' => $line
        ]);
    }

    public function removeProduitForEntree()
    {
        $numLine              = $this->input->line;
        $tmps                 = $this->session->entrees;
        $_SESSION ['entrees'] = [];
        $j                    = 1;

        foreach ($tmps as $tmp) {
            if ($tmp['idProduit'] == $this->input->idProduit AND $j == $numLine) {
                //code
            }else {
                $_SESSION['entrees'][] = $tmp;
            }
            $j ++;
        }
        echo json_encode([
            'error' => 0
        ]);
    }

    public function trieEntree1()
    {
        $idTrie = intval($this->input->idTrie);
        $entrees = [];

        if ($idTrie == 1) {
            $tmps = $this->Mouvement->all([], 'mvtid DESC');
            $date = date('Y-m-d', time());
            $entrees = [];

            foreach ($tmps as $tmp) {
                if (date('Y-m-d', strtotime($tmp->mvtdate)) == $date) {
                    $entrees [] = $tmp;
                }
            }
            $this->entrees = $this->Mouvement->get_by_periode();
        }elseif ($idTrie == 2) {
            $moth = date('n');
            $year = date('Y');
            $date = strtotime($year . '-' . $moth . '-01');

            $this->entrees = $this->Mouvement->get_by_periode(getStartingDay(), getLastDay());
        } elseif ($idTrie == 3) {
            $moth = date('n');
            $year = date('Y');
            $date = strtotime($year . '-' . $moth . '-01');

            $this->entrees = $this->Mouvement->get_by_periode(date('Y') . '-01-01 00:00', date('Y') . '-12-31 23:59');
        }

        echo json_encode([
            'tbodyTableEntree' => $this->autoLoadEntree()
        ]);
    }

    public function triByProduit() {
        $idproduit = intval($this->input->idProduit);
        $idTrie    = intval($this->input->idTri);
        $data      = [];
        
        if ($idTrie == 1 ) {
            $this->entrees = $this->Mouvement->sorties_by_produit($idproduit);
            $data = [
                'tbodyTableEntree' => $this->autoLoadEntree(),
                'output' => 1
            ];
        } else {
            $this->sorties = $this->Commandedetail->entrees_by_produit($idproduit);
            $data = [
                'tbodyTableSortie' => $this->autoLoadSortie(),
                'output' => 2
            ];
        }
        
        echo json_encode($data);
    }
    
    public function trieSortie1()
    {
        $idTrie  = intval($this->input->idTrie);
        $this->sorties = [];

        if ($idTrie == 1) {
            $date = date('Y-m-d', time());
            $this->sorties = $this->Commandedetail->get_by_periode();
        } elseif ($idTrie == 2) {
            $moth = date('m');
            $year = date('Y');
            $date = strtotime($year . '-' . $moth . '-01');

            $this->sorties = $this->Commandedetail->get_by_periode(getStartingDay(), getLastDay());
        } elseif ($idTrie == 3) {
            $moth = date('m');
            $year = date('Y');
            $date = strtotime($year . '-' . $moth . '-01');

            $this->sorties = $this->Commandedetail->get_by_periode($year . '-01-01 00:00:00', $year . '-12-31 23:59:59');
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
                if ($this->stock_boutique_by_produit($v->proid) > 0 AND $this->stock_boutique_by_produit($v->proid) > $v->proseuilalert) {
                    $produits [] = $v;
                }
            }
        }elseif ($idprint == 2) {
            foreach ($tmps as $v) {
                if ($this->stock_boutique_by_produit($v->proid) > 0 AND $this->stock_boutique_by_produit($v->proid) <= $v->proseuilalert) {
                    $produits [] = $v;
                }
            }
        } else {
            foreach ($tmps as $v) {
                if ($this->stock_boutique_by_produit($v->proid) === 0) {
                    $produits [] = $v;
                }
            }
        }

        $this->layout->assign('produits', $produits);
        $this->layout->assign('pdf', new \App\Core\PDF());
        $this->layout->assign('stock', $this);
        $this->layout->assign('printable', $idprint);
        $this->layout->render('boutique' . DS . 'impression' . DS . 'stock_1', TRUE);
    }

    public function impressionMouvement() {
        $entrees = $this->entrees;
        $idprint = 1;
        $this->layout->assign('entrees', $entrees);
        $this->layout->assign('pdf', new \App\Core\PDF());
        $this->layout->assign('stock', $this);
        $this->layout->assign('printable', $idprint);
        $this->layout->render('boutique' . DS . 'impression' . DS . 'mouvement', TRUE);
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
                if ($this->stock_boutique_by_produit($v->proid) > 0) {
                    $produits [] = $v;
                }
            }
        }elseif ($printable == 2) {
            foreach ($tmps as $v) {
                if ($this->stock_boutique_by_produit($v->proid) > 0 && $this->stock_boutique_by_produit($v->proid) <= $v->proseuilalert) {
                    $produits [] = $v;
                }
            }
        }else{
            foreach ($tmps as $v) {
                if ($this->stock_boutique_by_produit($v->proid) <= 0) {
                    $produits [] = $v;
                }
            }
        }

        $this->layout->assign('pdf', $pdf);
        $this->layout->assign('stock_function', $this);
        $this->layout->assign('pdf', $pdf);
        $this->layout->assign('produits', $produits);
        $this->layout->assign('printable', $printable);
        $this->layout->render('boutique' . DS . 'impression' . DS . 'stock', TRUE);
    }

    private function tableEntree()
    {
        $entrees = $this->entrees;
        $html    = '';
        $date    = '';
        foreach ($entrees as $entree) {
            if (date('Y-m-d', strtotime($entree->mvtdate)) === date('Y-m-d')) {
                $date = date('H:i:s', strtotime($entree->mvtdate));
            } else {
                $date = $entree->mvtdate;
            }

            $html .= '<tr>' .
                '<td>' . $entree->proid . '</td>'.
                '<td>' . ucfirst($entree->prodesignation) . '</td>'.
                '<td><span class="text-success"><i class="fa fa-sign-out"></i>Entrée</span></td>'.
                '<td><span class="text-success"><i class="fa fa-plus" style="font-size: 10px;"></i>' . $entree->mvtquantite . '</span></td>'.
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
            if (date('Y-m-d', strtotime($sortie->date)) === date('Y-m-d')) {
                $date = date('H:i:s', strtotime($sortie->date));
            } else {
                $date = $sortie->date;
            }

            $html .= '<tr>' .
                        '<td>' . $sortie->proid . '</td>'.
                        '<td>' . ucfirst($sortie->prodesignation) . '</td>'.
                        '<td><span class="text-danger"><i class="fa fa-sign-out"></i>Sorite</span></td>'.
                        '<td><span class="text-danger"><i class="fa fa-minus" style="font-size: 10px;"></i>' . $sortie->quantite . '</span></td>'.
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

            if (date('Y-m-d', strtotime($entree->mvtdate)) === date('Y-m-d')) {
                $date = date('H:i:s', strtotime($entree->mvtdate));
            } else {
                $date = $entree->mvtdate;
            }
            $mouvements [] = [
                'id' => $entree->proid,
                'name' => ucfirst($entree->prodesignation),
                'mouvement' => '<span class="text-success"><i class="fa fa-sign-out"></i>Entrée</span>',
                'quantite' => '<span class="text-success"><i class="fa fa-plus" style="font-size: 10px;"></i>' . $entree->mvtquantite . '</span>',
                'date' => $date,
            ];
        }
        return $mouvements;
    }

    private function autoLoadSortie()
    {
        $sorties    = $this->sorties;
        $mouvements = [];
        $date       = '';

        foreach ($sorties as $sortie) {
            if (date('Y-m-d', strtotime($sortie->date)) === date('Y-m-d')) {
                $date = date('H:i:s', strtotime($sortie->date));
            } else {
                $date = $sortie->date;
            }

            $mouvements [] = [
                'id' => $sortie->proid,
                'name' => ucfirst($sortie->prodesignation),
                'mouvement' => '<span class="text-danger"><i class="fa fa-sign-out"></i>Sorite</span>',
                'quantite' => '<span class="text-success"><i class="fa fa-plus" style="font-size: 10px;"></i>' . $sortie->quantite . '</span>',
                'date' => $date,
            ];
        }
        return $mouvements;
    }
}