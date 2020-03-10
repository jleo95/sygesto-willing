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

class MouvementController extends Controller
{

    private $entrees;
    private $sorties;

    public function __construct()
    {
        parent::__construct();
        $this->entrees = $this->Mouvement->all([], 'mvtid DESC');
    }

    public function index()
    {
        $this->layout->assign('entrees', $this->tableEntree());
        $this->layout->setTitle('Mouvement');
        $this->layout->setTitle('Mouvement', 'v');
        $this->layout->setJS('mouvement' . DS . 'index');
        $this->layout->setStyle('mouvement' . DS . 'mouvement');
        $this->layout->render('mouvement' . DS . 'index');
    }

    public function entree()
    {
//        $idOffre = 1;
//        $commande = $this->Offre->get_by('off.offid', $idOffre);
//        $commande_details = $this->Offredetail->show_produit_by_offre($idOffre);
//        $commande = $this->Offre->get_by('off.offid', 1);
//        var_dump($commande);
//        var_dump($commande_details);
////        die();
//        unset($_SESSION['listOfNewLivraison']);
//        unset($_SESSION['newMouvements']);
        if (!isset($_SESSION['newMouvements']))
            $_SESSION['newMouvements'] = [];


        if ($_POST) {
            $data = [];

            foreach ($this->session->newMouvements as $commande) {
                $data = [
                    'mvtdate' => $commande['dateMvt'],
                    'mvtproduit' => $commande['produit'],
                    'mvtquantite' => $commande['quantite'],
                    'mvtoffshore' => $commande['offshore'],
                    'mvtrealiserpar' => $this->session->stkiduser,
                    'mvtoffre' => $commande['offre'],
                ];
                $this->Mouvement->insert($data);
            }
            unset($_SESSION['newMouvements']);
            header('Location: ' . Router::url('mouvement'));
        }

        $this->layout->setTitle('Entrée');
        $this->layout->setTitle('Nouvelle livraison', 'v');
        $this->layout->setJS('mouvement' . DS . 'index');
        $this->layout->setStyle('mouvement' . DS . 'mouvement');
        $this->layout->render('mouvement' . DS . 'entree');
    }


    /**
     * On charge les commandes non livrer ici
     */
    public function loadProduitForEntree()
    {
        $commandes = $this->Offre->getCommandeNotLivreted();
        $this->layout->assign('commandes', $commandes);
        echo json_encode([
            'bodyModal' => $this->layout->ajax('mouvement' . DS . 'ajax' . DS . 'loadProduitForEntree')
        ]);
    }

    public function loadInfosProduitEntree()
    {
        $_SESSION['livraison'] = [];
        $idOffre = intval($this->input->idOffre);
        $commande = $this->Offre->get_by('off.offid', $idOffre);
        $commande_details = $this->Offredetail->show_produit_by_offre($idOffre);
        $this->layout->assign('commande', $commande);
        $this->layout->assign('commande_details', $commande_details);
        $html = $this->layout->ajax('mouvement' . DS . 'ajax' . DS . 'loadInfosProduitEntree');
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
        $this->layout->render('mouvement' . DS . 'impression' . DS . 'stock_1', TRUE);
    }

    public function impressionMouvement() {
        $entrees = $this->entrees;
        $idprint = 1;
        $this->layout->assign('entrees', $entrees);
        $this->layout->assign('pdf', new \App\Core\PDF());
        $this->layout->assign('stock', $this);
        $this->layout->assign('printable', $idprint);
        $this->layout->render('mouvement' . DS . 'impression' . DS . 'mouvement', TRUE);
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
        $this->layout->render('mouvement' . DS . 'impression' . DS . 'stock', TRUE);
    }

    public function removeProduitFromCommande()
    {
        $idProduit = $this->input->idCommande;
        $delete = $this->Offredetail->deletProduitFromCommande($idProduit);

        echo json_encode(['idCommande' => $idProduit]);
    }

    #livraison

    public function addLivraisonOnView()
    {
        $count = count($_SESSION['newMouvements']) + 1;
        $idOffre = intval($this->input->idOffre);
        $idOffshore = intval($this->input->idOffre);
        $dateMvt = (isset($this->input->dateMvt) && !empty($this->input->dateMvt)) ? $this->input->dateMvt : date('Y-m-d H:i:s', time());
        $commandes  = $this->Offredetail->show_produit_by_offre($idOffre);
        $commande  = $this->Offre->get_by('off.offid', $idOffre);
        $setData = (isset($_POST['setData'])) ? $_POST['setData'] : [];
        $item = [];
        $idArray = [];

        foreach ($setData as $setDatum) {
            $trouver = 0;
            $quantite = 0;
            foreach ($setData as $d) {
                if ($setDatum['produit'] == $d['produit']) {
                    $trouver ++;
                    $quantite = $d['produit'];
                }
            }
            if ($trouver == 1){
//                $idArray[] = $setDatum['produit'];
                $setDatum['dateCmd'] = $commande->date;
                $setDatum['dateMvt'] = $dateMvt;
                $setDatum['quantite'] = $quantite;
                $item[] = $setDatum;
            }
        }

        $tmp = $item;

        foreach ($commandes as $cmd) {
            $trouver = false;
            foreach ($tmp as $t) {
                if ($cmd->produit == $t['produit'])
                    $trouver = true;
            }
            if (!$trouver)
                $item[] = [
                        'produit' => intval($cmd->produit),
                        'quantite' => $cmd->quantite,
                        'offre' => $idOffre,
                        'offshore' => $idOffshore,
                        'dateMvt' => $dateMvt,
                        'dateCmd' => $commande->date
                    ];
        }

        $date = (isset($this->input->date)) ? $this->input->date : date('Y-m-d H:m', time());

        foreach ($item as $i) {
            $_SESSION['newMouvements'][] = $i;
        }
//        $_SESSION['newMouvements'][] =


        $html = '';
        foreach ($item as $i) {
            $html .= '<tr>' .
                        '<td>' . $count ++ . '</td>' .
                        '<td>'. $i['offshore'] . '</td>' .
                        '<td>'. $i['offre'] . '</td>' .
                        '<td>'. $i['produit'] . '</td>' .
                        '<td>'. $i['dateCmd'] . '</td>' .
                        '<td>'. $i['dateMvt']. '</td>' .
                        '<td><a href="javascript: removeProduitForEntree(<?php echo $commde->offid . \', \' . $i ?>)" class="btn abtn abtn-danger"><i class="fa fa-trash-o"></i></a></td>' .
                    '</tr>';
        }
        echo json_encode([
            'livraison' => $this->input->idOffre,
            'html' => $html
        ]);
    }

    private function tableEntree()
    {
        $entrees = (!empty($this->entrees)) ? $this->entrees : [];
        $html    = '';
        $date    = '';
        foreach ($entrees as $entree) {
            if (date('Y-m-d', strtotime($entree->mvtdate)) === date('Y-m-d')) {
                $date = date('H:i:s', strtotime($entree->mvtdate));
            } else {
                $date = formatDate($entree->mvtdate, 'd/m/y');
            }

            $html .= '<tr>' .
                        '<td>' . $entree->mvtid . '</td>'.
                        '<td>' . $entree->mvtoffshore . '</td>'.
                        '<td>' . $entree->mvtoffre . '</td>'.
                        '<td>' . $entree->mvtproduit . " #" . $entree->prodesignation . '#</td>'.
                        '<td><span class="text-success"><i class="fa fa-plus" style="font-size: 10px;"></i>' . $entree->mvtquantite . '</span></td>'.
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
}