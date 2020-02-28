<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 08/11/2018
 * Time: 01:49
 */

namespace App\Controllers;


use App\Core\Controller;
use App\Core\DateFR;
use App\Core\PDF;
use App\Core\Router;

class VenteController extends Controller
{

    private $ventes = [];

    private $prixTotaux = 0;

    private $depenses = 0;

    public function __construct()
    {
        parent::__construct();

        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $uri = explode('/', $uri);
        if (!isset($uri[1]) || empty($uri[1])) {
            header('Location:' . Router::url('vente', 'mesventes'));
        }
        $this->ventes = $this->Commande->all([], 'cmdid DESC');
//
//        $this->Commandedetail->get_multi_commandes([1, 2, 3]);
//
//        var_dump($this->Commandedetail->get_multi_commandes([1, 2, 3]));
//        var_dump($this->Commande->my_commande($this->session->stkiduser));
//
//        die('mes vente');
    }

    public function add()
    {
        if ($_POST) {
            if (isset($_POST['btnProcessVente1'])) {
                if (isset($this->input->clientVente) AND !empty($this->input->clientVente)) {
                    $_SESSION['vente']['cmdclient'] = $this->input->clientVente;
                    $_SESSION['vente']['cmddate'] = $this->input->dateVente_submit . ' ' . date('H:i', time());
                    $_SESSION['vente_detail'] = [];
                    $_SESSION['vente_detail_db'] = [];
                }

                header('Location:' . Router::url('vente', 'add'));
            }elseif(isset($_POST['btnEnProcessVenteSubmit'])) {
                $vente = [
                    'cmdpaiement' => $this->input->modePaiment,
                    'cmdclient' => $this->session->vente['cmdclient'],
                    'cmdrealiserpar' => $this->session->stkiduser,
                    'cmddate' => $this->session->vente['cmddate'],
                ];
                $vente_detail = [];

                if ($this->Commande->insert($vente)) {
                    $idVente = $this->Commande->lastInsert();
                    $arr_id = []; 
                    
                    foreach ($this->session->vente_detail as $detail) {
                        if (in_array($detail['idProduit'], $arr_id)) {
                            $vente_detail = $details [$detail['idProduit']];
                            $vente_detail['quantite'] += $detail['quantite'];
                            $details [$detail['idProduit']] = $vente_detail;
                        } else {
                            $vente_detail = [
                                'produit' => $detail['idProduit'],
                                'commande' => $idVente,
                                'quantite' => $detail['quantite'],
                                'prixtotal' => $detail['prixtotal'],
                                'date' => date('Y-m-d H:i:s', time()),
                            ];
                            $details [$detail['idProduit']] = $vente_detail;
                        }
                        
                        $arr_id [] = $detail['idProduit'];
                        $_SESSION['ventes_print'] = $_SESSION['vente_detail'];
                    }
                    
                    foreach ($details as $vente_detail) {
                        $this->Commandedetail->insert($vente_detail);
                    } 
                    
                }
                header('Location:' . Router::url('vente', 'add'));
            }
        }

        $idVente = $this->Commande->lastInsert() + 1;
        $this->layout->assign('idVente', $idVente);
        $paiements = $this->Paiement->all();
        $this->layout->assign('paiements', $paiements);
        $this->layout->assign('clients', $this->Client->all());
        $this->layout->setTitle('Vente');
        $this->layout->setTitle('Nouvelle vente', 'v');
        $this->layout->setStyle('vente' . DS . 'vente');
        $this->layout->setJS('vente' . DS . 'index');
        $this->layout->render('vente' . DS . 'add');
    }

    public function endProcess()
    {
        unset($_SESSION['vente_detail']);
        unset($_SESSION['ventes_print']);
        unset($_SESSION['vente']);
        unset($_SESSION['vente_detail_db']);
        unset($_SESSION['endProcessVente']);

        header('Location:' . Router::url('vente', 'mesventes'));
    }

    public function loadProduitForTableAddVente()
    {
        
        $tmps = $this->Produit->all();
        $produits = [];
        if (isset($_SESSION['vente_detail']) AND !empty($_SESSION['vente_detail'])) {
            $details = $_SESSION['vente_detail'];
            $arr_id = [];
            $arr_quantite = [];
            
            foreach ($details as $detail) {
                if (in_array($detail['idProduit'], $arr_id)) {
                    $t = $arr_quantite [$detail['idProduit']] + $detail['quantite'];
                    $arr_quantite [$detail['idProduit']] = $t;
                }else {
                    $arr_quantite [$detail['idProduit']] = $detail['quantite'];
                }
                $arr_id [] = $detail['idProduit'];
                
            }
            $stock_span = '';
            foreach ($tmps as $val) {
                $produit = [
                    'idProduit' => $val->proid,
                    'produit' => ucfirst($val->prodesignation),
                    'famille' => $val->famille,
                    'proprixUnitVente' => '<span style="font-style: italic;">' . $val->proprixUnitVente . '</span>',
                    'proprixblogVente' => '<span style="font-style: italic;">' . $val->proprixblogVente . '</span>',
                    'pronbproduitBlog' => $val->pronbproduitBlog
                ];
                $stock_bool = true;
                $stock = $this->stock_boutique_by_produit($val->proid);
                
                if (in_array($val->proid, $arr_id)) {
                    $stock -= $arr_quantite[$val->proid];
                }
                 if ($stock == 0) {
                    $stock_bool = false;
                    $stock_span = '<span style="font-style: italic; font-size: 12px;" class="text-danger">en rupture</span>';
                }elseif ($stock <= $val->proseuilalert) {
                    $stock_span = '<span class="text-warning">' . $stock . ' <span style="font-size: 12px;">(alerte stock)</span></span>';
                }else {
                    $stock_span = '<span>' . $stock . '</span>';
                }
                $produit ['quantite'] = $stock_span;
                $produit ['quantiteBool'] = $stock_bool;
                $produits [] = $produit;
            }
        }else {
            foreach ($tmps as $val) {
                $produit = [
                    'idProduit' => $val->proid,
                    'produit' => ucfirst($val->prodesignation),
                    'famille' => $val->famille,
                    'proprixUnitVente' => '<span style="font-style: italic;">' . $val->proprixUnitVente . '</span>',
                    'proprixblogVente' => '<span style="font-style: italic;">' . $val->proprixblogVente . '</span>',
                    'pronbproduitBlog' => $val->pronbproduitBlog
                ];
                $stock_bool = true;
                $stock = $this->stock_boutique_by_produit($val->proid);
               
                 if ($stock == 0) {
                    $stock_bool = false;
                    $stock_span = '<span style="font-style: italic; font-size: 12px;" class="text-danger">en rupture</span>';
                }elseif ($stock <= $val->proseuilalert) {
                    $stock_span = '<span class="text-warning">' . $stock . ' <span style="font-size: 12px;">(alerte stock)</span></span>';
                }else {
                    $stock_span = '<span>' . $stock . '</span>';
                }
                $produit ['quantite'] = $stock_span;
                $produit ['quantiteBool'] = $stock_bool;
                $produits [] = $produit;
            }
        }
        
        $this->layout->assign('produits', $produits);
        $this->layout->assign('stock', $this);

        echo json_encode([
            'modalBody' => $this->layout->ajax('vente' . DS . 'ajax' . DS . 'tableProduit')
        ]);

    }

    public function loadInfosProduitVente()
    {
        $produit = $this->Produit->get_by('proid', $this->input->idProduit);
         $quantite = 0;
        if (isset($_SESSION['vente_detail']) AND !empty($_SESSION['vente_detail'])) {
            foreach ($_SESSION['vente_detail'] as $detail) {
                if (intval($detail['idProduit']) == intval($produit->proid)) {
                    $quantite += $detail['quantite'];
                }
            }
            
        }
        
        $stock = $this->stock_boutique_by_produit($produit->proid) - $quantite;
        $this->layout->assign('produit', $produit);
        $this->layout->assign('stock', $stock);
        $html    = $this->layout->ajax('vente' . DS . 'ajax' . DS . 'loadInfosProduitVente');
        echo json_encode([
            'modalBody' => $html
        ]);
    }

    public function addProduitInVente()
    {
        $produit                                 = $this->Produit->get_by('proid', $this->input->idProduit);
        $prixtataux                              = 0;
        $_SESSION['vente_detail'][] = ['idProduit' => $this->input->idProduit,
                                       'produit' => ucfirst($produit->prodesignation),
                                       'famille' => $produit->famille,
                                       'unite' => $produit->unite,
                                       'prix' => $produit->proprixUnitVente,
                                       'quantite' => $this->input->quantite,
                                       'prixtotal' => $this->input->quantite * $produit->proprixUnitVente,
                                      ];

        $line                      = $this->input->line + 1;
        $html                      = '<tr class="line' . $line . '">' .
                                        '<td>' . $this->input->idProduit . '</td>' .
                                        '<td>' . ucfirst($produit->prodesignation) . '</td>' .
                                        '<td>' . $produit->famille . '</td>' .
                                        '<td>' . $produit->unite . '</td>' .
                                        '<td>' . $produit->proprixUnitVente . '</td>' .
                                        '<td>' . $this->input->quantite . '</td>' .
                                        '<td>' . $this->input->quantite * $produit->proprixUnitVente . '</td>' .
                                        '<td>' .
                                            '<a href="javascript: removeProduitInVente(' . $produit->proid . ', ' . $line . ');" class="btn abtn abtn-danger"><i class="fa fa-trash"></i></a>' .
                                        '</td>' .
                                    '</tr>';

        foreach ($_SESSION['vente_detail'] as $detail) {
            $prixtataux += $detail['prixtotal'];
        }

        echo json_encode([
            'prixTotaux' => number_format($prixtataux, 2, ',', ' ') . ' fcfa',
            'lineTable' => $html
        ]);
    }

    public function removeProduitInVente()
    {
        $idProduit                = $this->input->idProduit;
        $prixtotaux               = 0;
        $tmp                      = $_SESSION['vente_detail'];
        $_SESSION['vente_detail'] = [];
        $j                        = 1;

        $trouver = [];
        $details = [];
        foreach ($tmp as $detail) {
            if (intval($detail['idProduit']) == intval($idProduit) AND intval($this->input->line) === $j) {
                //$_SESSION['vente_detail'][] = $detail;
                //$prixtotaux += $detail['prixtotal'];
                $trouver ['line'] = $j;
                $trouver ['detail'] = $detail;
            }else {
                $_SESSION['vente_detail'][] = $detail;
                $prixtotaux += $detail['prixtotal'];
            }
            $j ++ ;
        }

        if (empty($_SESSION['vente_detail'])) {
            $_SESSION['endProcessVente'] = false;
            unset($_SESSION['endProcessVente']);
        }
        echo json_encode([
            'prixtotaux' => number_format($prixtotaux, 2, ',', ' ') . ' fcfa'
        ]);
    }

    public function loadNextPart()
    {
        $_SESSION['endProcessVente'] = true;
        $paiements = $this->Paiement->all();
        $this->layout->assign('paiements', $paiements);
        echo $this->layout->ajax('vente' . DS . 'loadNextPart');
    }

    public function mesventes()
    {
        $this->ventes = $this->Commande->my_commande($this->session->stkiduser);
        $listOfIdcommandes = [];

        foreach ($this->ventes as $vente) {
            $listOfIdcommandes[] = $vente->cmdid;
        }

        $this->layout->setTitle('Mes ventes');
        $this->layout->setTitle('Mes ventes', 'v');
        $this->layout->assign('tbodyMesventes', $this->loadTable());
        $this->layout->assign('valuesprix', [
            'depenses' => $this->Commandedetail->get_depense_by_multi_commande($listOfIdcommandes),
            'benefices' => $this->Commandedetail->get_benefice_by_multi_commande($listOfIdcommandes),
            'totaux_ventes' => $this->prixTotaux
        ]);
        $this->layout->setJS('vente' . DS . 'index');
        $this->layout->setStyle('vente' . DS . 'vente');
        $this->layout->render('vente' . DS . 'mesventes');
    }

    public function imprimer()
    {
        $idcommande = null;
        $idcommande = $this->Commande->lastInsert();
        $ventes     = $this->Commandedetail->get_by_commande($idcommande);
        $pdf        = null;
        $user       = $this->User->get_by('useid', $this->session->stkiduser);

        if (count($ventes) < 9) {
            $pdf = new PDF('L', 'mm', 'A7');
        }else {
            $pdf = new PDF('P', 'mm', 'A6');
        }
        $this->layout->assign('pdf', $pdf);
        $this->layout->assign('ventes', $ventes);
        $this->layout->assign('user', $user);
        $this->layout->render('vente' . DS . 'impression' . DS . 'recu', true);
    }

    public function trieVente()
    {
        $idTrie = $this->input->idTrie;
        $tmps   = [];
        $ventes = [];

        if ($idTrie == 1) {
            $start  = date('Y-m-d', time()) . ' 00:00';
            $end    = date('Y-m-d', time()) . ' 23:59';
        }elseif ($idTrie == 2) {
            $start = getStartingDay();
            $end = getLastDay();
        }elseif ($idTrie == 3) {
            $start = date('Y') . '-01-01 00:00';
            $end = date('Y') . '-12-31 23:59';
        }elseif ($idTrie == 'd') {            
            $start = $this->input->date;
            
            $date = new \DateTime(str_replace('/', '-', $start));
            $start = $date->format('Y-m-d') . ' 00:00:00';
            $end = $date->format('Y-m-d') . ' 23:59:59';
        }else {
            
            $start = $this->input->startDate;
            $end = $this->input->endDate;
            
            $startDate = new \DateTime(str_replace('/', '-', $start));
            $start = $startDate->format('Y-m-d') . ' 00:00:00';

            $endDate = new \DateTime(str_replace('/', '-', $end));
            $end = $endDate->format('Y-m-d') . ' 23:59:59';
        }

        $tmps   = $this->Commande->get_by_periode($start, $end);
        $ventes = $this->autoLoad($tmps);
        $benefices = $this->prixTotaux - $this->depenses;
        echo json_encode([
            'ventes' => $ventes,
            'depenses' => number_format($this->depenses, 2, ',', ' ') . ' fcfa',
            'benefices' => number_format($benefices, 2, ',', ' ') . ' fcfa',
            'totaux_ventes' => number_format($this->prixTotaux, 2, ',', ' ') . ' fcfa'
        ]);
    }

    public function impression_vente()
    {
        $idPrint = $this->input->idPrint;
        $tmps   = [];
        $ventes = [];
        $title_date = '';

        if ($idPrint == 1) {
            $start  = date('Y-m-d', time()) . ' 00:00';
            $end    = date('Y-m-d', time()) . ' 23:59';
            $start_date_vente = new DateFR($start);
            $end_date_vente = new DateFR($end);
            $title_date = 'Liste des ventes de la journée ' . $start_date_vente->getDate() . ' ' . $start_date_vente->getMois(3) . ' ' . $start_date_vente->getYear() .  ' (00:00:00 - 23:59:59)';
        }elseif ($idPrint == 2) {
            $start = getStartingDay();
            $end = getLastDay();
            $start_date_vente = new DateFR($start);
            $end_date_vente = new DateFR($end);
            $title_date = 'Liste des ventes du mois ' . $start_date_vente->getMois() . ' ' . $start_date_vente->getYear() . ' (' . $start_date_vente->getDate() . ' ' . $start_date_vente->getMois(3) . ' à ' . $end_date_vente->getDate() . ' ' . $end_date_vente->getMois(3) . ')';
        }elseif ($idPrint == 3) {
            $start = date('Y') . '-01-01 00:00';
            $end = date('Y') . '-12-31 23:59';
            $start_date_vente = new DateFR($start);
            $end_date_vente = new DateFR($end);
            $title_date = 'Liste des ventes de l\'année ' . $start_date_vente->getYear() . ' (Janvier - Decembre)';
        }elseif ($idPrint == 'd') {
            if ($_POST) {
                $start = $this->input->startOneDatePrintVente_submit;
            }else {
                $start = $this->input->date;
            }

            $date = new \DateTime(str_replace('/', '-', $start));
            $start = $date->format('Y-m-d') . ' 00:00:00';
            $end = $date->format('Y-m-d') . ' 23:59:59';

            $start_date_vente = new DateFR($start);
            $end_date_vente = new DateFR($end);

            $title_date = 'Liste des ventes de la journée ' . $start_date_vente->getDate() . ' ' . $start_date_vente->getMois(3) . ' ' . $start_date_vente->getYear() . ' (' . $start_date_vente->getTime() . ' - ' . $end_date_vente->getTime() . ')';
        }else {

            if ($_POST) {
                $start = $this->input->startDatePrintVente_submit;
                $end = $this->input->endDatePrintVente_submit;
            }else {
                $start = $this->input->startDate;
                $end = $this->input->endDate;
            }

            $startDate = new \DateTime(str_replace('/', '-', $start));
            $start = $startDate->format('Y-m-d') . ' 00:00:00';

            $endDate = new \DateTime(str_replace('/', '-', $end));
            $end = $endDate->format('Y-m-d') . ' 23:59:59';

            $start_date_vente = new DateFR($start);
            $end_date_vente = new DateFR($end);
            $title_date = 'Liste des ventes de la période ' . $start_date_vente->getDate() . ' ' . $start_date_vente->getMois(3) . ' ' . $start_date_vente->getYear() . ' ' . $start_date_vente->getTime() . ' à ' . $end_date_vente->getDate() . ' ' . $end_date_vente->getMois(3) . ' ' . $end_date_vente->getYear() . ' ' . $end_date_vente->getTime();
        }

        $ventes   = $this->Commande->get_by_periode($start, $end);
        $listOfIdcommandes = [];
        $pdf = $pdf = new PDF();

        foreach ($ventes as $vente) {
            $listOfIdcommandes[] = $vente->cmdid;
        }

        $this->layout->assign('pdf', $pdf);
        $this->layout->assign('ventes', $ventes);
//        $this->layout->assign('get_client', $this->Client);
        $this->layout->assign('obj_vente', $this);
        $this->layout->assign('title_date', $title_date);
        $this->layout->assign('benefice', $this->Commandedetail->get_benefice_by_multi_commande($listOfIdcommandes));
        $this->layout->assign('depense', $this->Commandedetail->get_depense_by_multi_commande($listOfIdcommandes));
        $this->layout->render('vente' . DS . 'impression' . DS . 'ventes', true);
    }

    public function showAllInfosMesventes()
    {
        $idCommande = $this->input->idCommande;
        $details    = $this->Commandedetail->get_by_commande($idCommande);
        $commande      = $this->Commande->get_by('cmdid', $idCommande);

        if ($commande) {
            $html = '';
            $prixTotal = 0;
            $i = 0;

            $html .= '<div class="detail">' .
                '<div><span class="label">Client: </span>' . $commande->clinom . ' ' . $commande->cliprenom . '</div>' .
                '<div><span class="label">Date: </span>' . $commande->cmddate . '</div>' .
                '<div><span class="label">Remise: </span>' . number_format(0, 2, ',', ' ') . '</div>' .
                '</div>';

            $html .= '<div class="produits scrollbar-deep-purple">';
            foreach ($details as $detail) {
                if ($i == count($details) - 1) {
                    $html .= '<div class="produit end">' .
                        '<div class="name"><span class="label">Produit: </span>' . $detail->prodesignation . '</div>' .
                        '<div class="prix"><span class="label">Prix unitaire: </span>' . $detail->proprixUnitVente . '</div>' .
                        '<div class="quantite"><span class="label">Quantité: </span>' . $detail->quantite . '</div>' .
                        '<div class="quantite"><span class="label">Prix total: </span> <span style="font-style: italic;">' . number_format($detail->proprixUnitVente * $detail->quantite, 2, ',', ' ') . ' fcfa</span></div>' .
                        '</div>';
                } else {
                    $html .= '<div class="produit">' .
                        '<div class="name"><span class="label">Produit: </span>' . $detail->prodesignation . '</div>' .
                        '<div class="prix"><span class="label">Prix unitaire: </span>' . $detail->proprixUnitVente . '</div>' .
                        '<div class="quantite"><span class="label">Quantité: </span>' . $detail->quantite . '</div>' .
                        '<div class="quantite"><span class="label">Prix total: </span> <span style="font-style: italic;">' . number_format($detail->proprixUnitVente * $detail->quantite, 2, ',', ' ') . ' fcfa</span></div>' .
                        '</div>';
                }
                $i++;


                $prixTotal += $detail->proprixUnitVente * $detail->quantite;
            }

            $html .= '</di>';
            $html .= '<div class="prixRemise"><span class="label">Prix avec remise:..................</span><span class="value">' . number_format(0, 2, ',' , ' ') . ' fcfa</span></div>';
            $html .= '<div class="prixTotal"><span class="label">Prix totaux:.................</span><span class="value">' . number_format($prixTotal, 2, ',' , ' ') . ' fcfa</span></div>';

            echo json_encode([
                'bodyModal' => $html,
                'headerModal' => 'Vente (' . $commande->cmdid . ')'
            ]);
        }else {
            echo json_encode([
                'bodyModal' => 'Rien à afficher',
                'headerModal' => 'Vente non disponible'
            ]);
        }


    }

    public function all()
    {
        $listOfIdcommandes = [];

        foreach ($this->ventes as $vente) {
            $listOfIdcommandes[] = $vente->cmdid;
        }

        $this->layout->assign('tbodyMesventes', $this->loadTable());
        $this->layout->assign('valuesprix', [
            'depenses' => $this->Commandedetail->get_depense_by_multi_commande($listOfIdcommandes),
            'benefices' => $this->Commandedetail->get_benefice_by_multi_commande($listOfIdcommandes),
            'totaux_ventes' => $this->prixTotaux
        ]);
        $this->layout->setTitle('Ventes');
        $this->layout->setTitle('Toutes les ventes', 'v');
        $this->layout->setJS('vente' . DS . 'index');
        $this->layout->setStyle('vente' . DS . 'vente');
        $this->layout->render('vente' . DS . 'mesventes');
    }

    public function prixTauxVente(int $idcmd) {
        $vente_detail = $this->Commandedetail->get_by_commande($idcmd);
        $prixtotaux = 0;
        $depenses = 0;
//        dd($vente_detail);
        foreach ($vente_detail as $detail) {
            $prixtotaux += $detail->prixtotal;
            $depenses += $detail->proprixUnitAchat;
        }

        return ['prixTotaux' => $prixtotaux, 'depenses' => $depenses];
    }

    private function loadTable()
    {
        $this->prixTotaux = 0;
        $this->depenses = 0;
        $ventes = $this->ventes;
        $html   = '';

//        dd($ventes);
        foreach ($ventes as $vente) {
            $client = $this->Client->get_by((string)'cliid', (int)$vente->cmdclient);
            if ($client) {
                $nameClient = ucfirst($client->clinom) . ' ' . ucfirst($client->cliprenom);
            }else {
                $nameClient = 'leo';
            }
            $prixtotaux = $prixtotaux = $this->prixTauxVente($vente->cmdid)['prixTotaux'];
            $html .= '<tr>' .
                        '<td>' . $vente->cmdid . '</td>' .
                        '<td>' . $nameClient . '</td>' .
                        '<td>' . $vente->cmddate . '</td>' .
                        '<td><span style="font-style: italic;">' . number_format($prixtotaux, 2, ',', ' ' ) . ' fcfa</span></td>' .
                        '<td></td>' .
                    '</tr>';

            $this->prixTotaux += $prixtotaux;
            $this->depenses += $this->prixTauxVente($vente->cmdid)['depenses'];
        }

        return $html;
    }

    private function autoLoad($data)
    {
        $totaux_ventes = 0;
        $depenses = 0;
        $ventes = [];
        foreach ($data as $tmp) {
            $client = $this->Client->get_by('cliid', $tmp->cmdclient);
            if ($client) {
                $nameClient = ucfirst($client->clinom) . ' ' . ucfirst($client->cliprenom);
            }else {
                $nameClient = 'Inconnu';
            }

            $prixtotaux = $this->prixTauxVente($tmp->cmdid)['prixTotaux'];
            $ventes [] = [
                'id' => $tmp->cmdid,
                'name' => $nameClient,
                'date' => $tmp->cmddate,
                'prix' => '<span style="font-style: italic;">' . number_format($prixtotaux, 2, ',', ' ' ) . ' fcfa</span>',
            ];

            $totaux_ventes += $prixtotaux;
            $depenses += $this->prixTauxVente($tmp->cmdid)['depenses'];
        }
        $this->prixTotaux = $totaux_ventes;
        $this->depenses   = $depenses;

        return $ventes;
    }

}