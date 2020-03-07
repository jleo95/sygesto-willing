<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 28/10/2018
 * Time: 17:47
 */

namespace App\Controllers;


use App\Core\Controller;

class OffshoreController extends Controller
{

    private $offshores = [];

    public function __construct()
    {
        parent::__construct();
        
//        $this->calledMethodesChild();

        //$this->offshores = $this->Offshore->findAllQuery([], 'offid DESC');
//        var_dump($this->stock_magasin_by_produit(409));
      
    }


    public function index()
    {
        if ($_POST) {
            $data = [];
            $error = [];

            if (isset($this->input->designAddOffshore) AND !empty($this->input->designAddOffshore)) {
                $data ['offdescription'] = $this->input->designAddOffshore;
            }else {
                $error ['danger']['offdescription'] = true;
            }

           

            
            if (empty($error)) {
                $this->Offshore->insert($data);
                $this->layout->assign('success', true);
//                die('cool');
            }else {
                var_dump($error);
                die('il y\'a une erreur');
            }
        }
        $this->layout->setTitle('offshores');
        $this->layout->setTitle('Liste de tous les offshores', 'v');
        $this->layout->assign('offshore', $this->Offshore->all([], 'offid DESC'));
        $this->layout->assign('clients', $this->Client->all());

       // $this->layout->assign('fournisseurs', $this->Fournisseur->all());
        //$this->layout->assign('client', $this->Client->all());
        //$this->layout->assign('familles', $this->Famille->all());
        //$this->layout->assign('mouvement', $this->Mouvement);
        $this->layout->assign('tbodyOffshore', $this->loadTable());
        $this->layout->setJS('offshore' . DS . 'index');
        $this->layout->setStyle('offshore' . DS . 'offshore');
        $this->layout->render('offshore' . DS . 'index');
        
    }

    #ajout d'un produit
    public function add()
    {
        $data = [];
        $error = [];

        if (isset($this->input->designAddOffshore) AND !empty($this->input->designAddOffshore)) {
            $data ['offdescription'] = $this->input->designAddOffshore;
        }else {
            $error ['danger']['description'] = true;
        }

       
        if (isset($this->input->respoAddOffshore) AND !empty($this->input->respoAddOffshore)) {
            $data ['offresposable'] = $this->input->respoAddOffshore;
        }

        else {
            $error ['danger']['offresposable'] = true;
        }

        

        $response = [];


        if (empty($error)) {
            $data['prodatecreation'] = date('Y-m-d H:i:s', time());
            $data['prorealiserpar'] = $this->session->stkiduser;
            if ($this->Offshore->insert($data)) {

                $offshore = $this->Offshore->get_by('offid', $this->Offshore->lastInsert());

                

                $offshoreHtml = [
                    'id' => $offshore->offid,
                    'description' => $offshore->offdescription,
                    'respo' => $offshore->offres,
                    
                ];

                $response['off']           = $offshoreHtml;
                $response ['error']            = 0;
                $this->offshores                = $this->Offshore->all();
                $html                          = $this->loadTable();
                $response ['bodyTableOffshore'] = $html;
                $modalFooter                   = '<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-primary" onclick="confirmAdd(0)">Ok</button></div>';
                $response ['modalFooter']      = $modalFooter;
            }else {
                $response ['error'] = 2;
                $modalFooter = '<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-primary" onclick="confirmAdd(1)">Ok</button></div>';
                $response ['modalFooter'] = $modalFooter;
            }
        }else {
            $response ['error'] = 1;
            $response ['mssge'] = '<div class="alert alert-danger alert-dismissable">' .
                '<button type="button" class="close" data-dismiss="alert">&times;</button>' .
                '<stron>Attention !</stron> Veillez verifier les infos entrés' .
                '</div>';
        }

        echo json_encode($response);

    }

    #chargement de donnee pour l'ajout
    public function loadAdd()
    {
        $this->layout->assign('fournisseurs', $this->Fournisseur->all());
        $this->layout->assign('clients', $this->Client->all());
        $this->layout->assign('unites', $this->Unite->all());
        $this->layout->assign('familles', $this->Famille->all());
        $this->layout->assign('mouvement', $this->Mouvement);
        echo $this->layout->ajax('offshore' . DS . 'ajax' . DS . 'add');
    }

    #chargement des element pour la suppression
    public function laodDataForDeleteOffshore()
    {
        $offshore = $this->Offshore->get_by('offid', $this->input->idOffshore);
        $html = '<div>' .
                '<span>Voulez-vous supprimer le produit: </span>' . ucfirst($offshore->prodesignation) . ' ?' .
                '</div>';

        echo json_encode(['modalBody' => $html, 'nameOffshore' => ucfirst($offshore->prodesignation)]);
    }

    #suppression d'un produit
    public function delete()
    {
        if ($this->Offshore->delete_by('offid', $this->input->idOffshore)) {
            $html = $this->loadTable(true);
            echo json_encode(['succes' => 0, 'tbodyOffshore' => $html]);
        }else {
            echo json_encode(['succes' => 1]);
        }
    }

    #chargement des donnee pour l'edition
    public function laodForEditeOffshore()
    {
        $this->layout->assign('offshore', $this->Offshore->get_by('offid', $this->input->idOffshore));
        $this->layout->assign('fournisseurs', $this->Fournisseur->all());
        $this->layout->assign('familles', $this->Famille->all());
        $this->layout->assign('unites', $this->Unite->all());
        $contentAjax = $this->layout->ajax('offshore' . DS . 'ajax' . DS . 'edit');
        echo json_encode(['modalBody' => $contentAjax]);
    }

    #editer un produit
    public function edit()
    {
        $offshore = $this->Offshore->get_by('offid', $this->input->idOffshoreEdit);
        $data    = [];
        $error   = [];

        if (isset($this->input->designEditOffshore) AND !empty($this->input->designEditOffshore)) {
            if ($offshore->prodesignation !== $this->input->designEditOffshore) {
                $data ['prodesignation'] = $this->input->designEditOffshore;
            }
        }else {
            $error ['danger']['designation'] = true;
        }

        if (isset($this->input->prixachatEditOffshore) AND !empty($this->input->prixachatEditOffshore)) {
            if ($this->input->prixachatEditOffshore !== $offshore->proprixUnitAchat) {
                $data ['proprixUnitAchat'] = $this->input->prixachatEditOffshore;
            }
        }else {
            $error ['danger']['prixUnitAchat'] = true;
        }

        if (isset($this->input->prixGlobVenteEditOffshore) AND !empty($this->input->prixGlobVenteEditOffshore)) {
            if ($this->input->prixGlobVenteEditOffshore !== $offshore->proprixblogVente) {
                $data ['proprixblogVente'] = $this->input->prixGlobVenteEditOffshore;
            }
        }else {
            $error ['danger']['prixGlobalVente'] = true;
        }

        if (isset($this->input->nbblogEditOffshore) AND !empty($this->input->nbblogEditOffshore)) {
            if ($this->input->nbblogEditOffshore !== $offshore->pronbproduitBlog) {
                $data ['pronbproduitBlog'] = $this->input->nbblogEditOffshore;
            }
        }

        if (isset($this->input->prixUnitVenteEditOffshore) AND !empty($this->input->prixUnitVenteEditOffshore)) {
            if ($this->input->prixUnitVenteEditOffshore !== $offshore->proprixUnitVente) {
                $data ['proprixUnitVente'] = $this->input->prixUnitVenteEditOffshore;
            }
        }else {
            $error ['danger']['prixUnitVente'] = true;
        }

        if (isset($this->input->peremptionEditOffshore) AND !empty($this->input->peremptionEditOffshore)) {
            if ($this->input->peremptionEditOffshore !== $offshore->prodatePeremption) {
                $data ['prodatePeremption'] = $this->input->peremptionEditOffshore;
            }
        }else {
            $error ['danger']['peremption'] = true;
        }

        if (isset($this->input->familleEditOffshore) AND !empty($this->input->familleEditOffshore)) {
            if ($this->input->familleEditOffshore !== $offshore->profamille) {
                $data ['profamille'] = $this->input->familleEditOffshore;
            }
        }

        if (isset($this->input->uniteEditOffshore) AND !empty($this->input->uniteEditOffshore)) {
            if ($this->input->uniteEditOffshore !== $offshore->prounitemessure) {
                if ($this->input->uniteEditOffshore != 'aucun') {
                    $data ['prounitemessure'] = $this->input->uniteEditOffshore;
                }
            }

        }else {
            $error ['danger']['unitemesure'] = true;
        }

        if (isset($this->input->fournisseurEditOffshore) AND !empty($this->input->fournisseurEditOffshore)) {
            if ($this->input->fournisseurEditOffshore !== $offshore->profournisseur) {
                $data ['profournisseur'] = $this->input->fournisseurEditOffshore;
            }
        }

        if (isset($this->input->seuilEditOffshore) AND !empty($this->input->seuilEditOffshore)) {
            if ($this->input->seuilEditOffshore !== $offshore->proseuilalert) {
                $data ['proseuilalert'] = $this->input->seuilEditOffshore;
            }
        }

        $nameOffshore = ucfirst($offshore->prodesignation);
        if (empty($error)) {
            if (!empty($data)) {
                if ($this->Offshore->update($data, ['offid' => $this->input->idOffshoreEdit])) {
                    $modalFooter                    = '<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-primary" onclick="confirmEdit(0)">Ok</button></div>';
                    $response ['error']             = 0;
                    $response ['modalFooter']       = $modalFooter;
                    $this->offshores                 = $this->Offshore->all();
                    $response ['tbodyTableOffshore'] = $this->loadTable();
                    $offshore = $this->Offshore->get_by('offid', $this->input->idOffshoreEdit);
                }else {
                    $modalFooter = '<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-primary" onclick="confirmEdit(1)">Ok</button></div>';
                    $response ['error']       = 2;
                    $response ['modalFooter'] = $modalFooter;
                }
            }else {
                $modalFooter = '<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-primary" onclick="confirmEdit(1)">Ok</button></div>';
                $response ['error'] = 0;
                $response ['modalFooter'] = $modalFooter;
            }
        }else {
            $response ['error'] = 1;
            $response ['mssge'] = '<div class="alert alert-danger alert-dismissable">' .
                '<button type="button" class="close" data-dismiss="alert">&times;</button>' .
                '<stron>Attention !</stron> Veillez verifier les infos entrés' .
                '</div>';
        }

        $response ['nameOffshore'] = ucfirst($offshore->prodesignation);
        echo json_encode($response);
    }

    public function trieOffshore()
    {
        $offshores       = $this->Offshore->all([], 'offoid DESC', FALSE);
        $tmp            = [];
        $etat           = '';
        $this->offshores = [];

        if ($this->input->idTrie == 1) {
            foreach ($offshores as $offshore) {
                if ($this->stock_boutique_by_produit($offshore->offid) <= $offshore->proseuilalert AND $this->stock_boutique_by_produit($offshore->offid) != 0) {
                    $tmp [] = $offshore;
                }
            }
            $etat = 'en alerte';
        }elseif ($this->input->idTrie == 2) {
            foreach ($offshores as $offshore) {
                if ($this->stock_boutique_by_produit($offshore->offid) == 0) {
                    $tmp [] = $offshore;
                }
            }
            $etat = 'en rupture';
        }elseif ($this->input->idTrie == 3) {
            foreach ($offshores as $offshore) {
                if (
                    $this->stock_boutique_by_produit($offshore->offid) > $offshore->proseuilalert AND
                    $this->stock_boutique_by_produit($offshore->offid) > 0
                ) {
                    $tmp [] = $offshore;
                }
            }
            $etat = 'en stock';
        }else {
            $tmp = $offshores;
        }

        $this->offshores = $tmp;

        echo json_encode([
            'bodyTableOffshore' => $this->autoLoad(),
            'etat' => $etat
        ]);
    }

    public function showAllInfosOffshore()
    {
        $html = '';
        $offshore = $this->Offshore->get_by('offid', $this->input->idOffshore);
        $stock_boutique = '';
        $stock_magasin = '';
        $header_stock = '<span style="color: #008000; font-size: 15px; font-weight: bold;"> (Disponible)</span>';

        if ($this->stock_boutique_by_produit($offshore->offid) == 0) {
            $stock_boutique = '<span class="value" style="font-style: italic; color: #d9534f;">en rupture</span>';
            $header_stock = '<span style="color: #d9534f; font-size: 15px; font-weight: bold;">(En rupture)</span>';
        }elseif ($this->stock_boutique_by_produit($offshore->offid) <= $offshore-> proseuilalert) {
            $stock_boutique = '<span class="value" style="font-style: italic; color: #f0ad4e;">alert (' . $this->stock_boutique_by_produit($offshore->offid) . ')</span>';
            $header_stock = '<span style="color: #f0ad4e; font-size: 15px; font-weight: bold;">(En alerte (Disponible))</span>';
        }else {
            $stock_boutique = '<span class="value" style="font-style: italic;">' . $this->stock_boutique_by_produit($offshore->offid) . '</span>';
        }

        if ($this->stock_magasin_by_produit($offshore->offid) == 0) {
            $stock_magasin = '<span class="value" style="font-style: italic; color: #d9534f;">en rupture</span>';
        }elseif ($this->stock_magasin_by_produit($offshore->offid) <= $offshore-> proseuilalert) {
            $stock_magasin = '<span class="value" style="font-style: italic; color: #f0ad4e;">alert (' . $this->stock_magasin_by_produit($offshore->offid) . ')</span>';
        }else {
            $stock_magasin = '<span class="value" style="font-style: italic;">' . $this->stock_magasin_by_produit($offshore->offid) . '</span>';
        }

        $date = '';

        $date = $offshore->prodatePeremption;
        $days = calculDate($date);
        $dateperemption = $offshore->prodatePeremption;

        if ($days < 0 || $days == 0) {
            $days = -1 * $days;

            if ($days / 30 > 12) {
                $dateperemption = 'Expiré (il y\'a plus de ' . ceil(ceil($days / 30)) / 12 . ' ans)';
            }elseif ($days / 30 >= 1 AND $days < 12) {
                $dateperemption = 'Expiré (il y\'a ' . ceil($days / 30) . ' mois et ' . $days % 30 . ' jours)';
            }elseif ($days / 30 < 1) {
                $dateperemption = '<span class="text-danger">Expiré</span> (il y\'a ' . $days . ' jours)';
            }
        }else {

            if ($days / 30 >= 1 AND $days / 30 <= 3 ) {
                $dateperemption = $date . '<span class="text-warning" style="font-style: italic; font-size: 11px;"> (va expiré dans ' . ceil($days / 30) . ' mois et ' . $days % 30 . ' jours )</span>';
            }elseif ($days / 30 < 1) {
                $dateperemption = $date . '<span style="font-style: italic; font-size: 11px;"> (va expiré dans ' . $days . ' jours)</span>';
            }

        }

        $html = '<div class="produit" style="padding-bottom: 30px;">' .
            '<div class="line"><span class="label">Désignation: </span><span class="value">' . $offshore->prodesignation . '</span></div>' .
            '<div class="line"><span class="label">Responsable </span><span class="value">' . $offshore->famille . '</span></div>' .
            '<div class="line"><span class="label">Début: </span><span class="value">' . $dateperemption . '</span></div>' .
            '<div class="line"><span class="label">Jours restant </span><span class="value">' . $offshore->unite . '</span></div>' .
            '<div class="line"><span class="label">Prix en blog de vente: </span><span class="value" style="font-style: italic">' . number_format($offshore->proprixblogVente, 2, ',', ' ') . ' frcfa</span></div>' .
            '<div class="line"><span class="label">Nombre de produit par blog: </span><span class="value">' . $offshore->pronbproduitBlog . '</span></div>';
        
        $html .= '<div class="line"><span class="label">Stock en mouvement: </span>' . $stock_boutique;
        if (!empty($offshore->pronbproduitBlog) AND $offshore->pronbproduitBlog !== '/' AND $offshore->pronbproduitBlog !== 0) {
            $html .= ' (' . $this->stock_boutique_by_produit($offshore->offid) / $offshore->pronbproduitBlog . ' ' . $offshore->embalage . ')';
        }
        
        $html .= '</div>';

        if (in_array('405', $_SESSION['stkdroits'])) {
            $unite = $offshore->uniabv;
            if ($offshore->unite == 'Aucun') {
                $unite = '';
            }
            $html .= '<div class="line"><span class="label">Stock en magasin: </span>' . $stock_magasin  . ' ' . $unite;
            if ($this->stock_magasin_by_produit($offshore->offid) >= 0 && $offshore->pronbproduitBlog > 0) {
                $html .= ' (' . $this->stock_magasin_by_produit($offshore->offid) / $offshore->pronbproduitBlog . ' ' . $offshore->embalage . ')';
            }
            
            $html .= '</div>';
        }

        $html .= '<div class="line"><span class="label">Fournisseur: </span><span class="value">' . ucfirst($offshore->founom) . ' ' . ucfirst($offshore->fouprenom) . '</span></div>' .
            '<div class="line"><span class="label">Seuil d\'alerte: </span><span class="value">' . $offshore->proseuilalert . '</span></div>' .
            '</div>';

        echo json_encode([
            'infosOffshore' => $html,
            'header_stock' => $header_stock
        ]);
    }

    public function imprimer()
    {
        $pdf       = new \App\Core\PDF();
        $offshores      = $this->Offshore->all([], 'offid ASC');

        $this->layout->assign('pdf', $pdf);
        $this->layout->assign('stock_function', $this);
        $this->layout->assign('pdf', $pdf);
        $this->layout->assign('offshores', $offshores);
        $this->layout->render('offshore' . DS . 'impression' . DS . 'stock', TRUE);
    }

    #chargement des element de la table qui contient la liste des produit
    private function loadTable($script = false)
    {
        $offshore = $this->offshores;
        $html     = '';
        $i = 1;

        /*foreach ($offshores as $offshore) {
            $html .= '<tr>' .
                '<td>' . $offshore->offid . '</td>' .
                '<td>' . $offshore->prodesignation . '</td>' .
                '<td>' . $offshore->famille . '</td>' .
                '<td><span style="font-style: italic;">' . $offshore->proprixUnitVente . '</span></td>' .
                '<td><span style="font-style: italic;">' . $offshore->proprixblogVente . '</span></td>';

            $html .= '<td>';
             if ($this->stock_boutique_by_produit($offshore->offid) == 0) {
                $html .=  '<span style="font-style: italic; font-size: 12px;">rupture</span>';
             }else {
                $html .= $this->stock_boutique_by_produit($offshore->offid) . '</td>';
             }
             $html .= '<td>';
             if (in_array('107', $_SESSION['stkdroits'])) {
                 $html .= '<a data-toggle="modal" href="#showAllInfosOffshore" onclick="showAllInfosOffshore(' . $offshore->offid . '); return false" title="voir plus" class="table-action more-infos text-primary"><i class="fa fa-eye" style="color: #36AFEC;"></i></a>&nbsp;';
             }else {
                 $html .= '<a href="javascript: void(0)" class="disabled btn abtn abtn-primary" title="voir plus"><i class="fa fa-eye-slash"></i></a>&nbsp;';
             }
             if (in_array('103', $_SESSION['stkdroits'])) {
                 $html .= '<a data-toggle="modal" href="#editeOffshore" onclick="laodForEditeOffshore(' . $offshore->offid . '); return false" title="editer" class="table-action edit-produit text-success"><i class="fa fa-pencil" style="color: #05AE0E;"></i></a>';
             }else {
                 $html .= '<a href="javascript: void(0)" class="disabled btn abtn abtn-success" title="editer"><i class="fa fa-pencil"></i></a>';
             }
            if (in_array('102', $_SESSION['stkdroits'])) {
                $html .= '<a data-toggle="modal" href="#deletProuit" onclick="laodDataForDeleteOffshore(' . $offshore->offid . '); return false;" title="supprimer" class="table-action remove-produit text-danger"><i class="fa fa-trash-o " style="color: #FF3B30;"></i></a>';
             } else {
                $html .= '<a href="javascript: void(0)" class="disabled btn abtn abtn-danger" title="editer"><i class="fa fa-trash-o "></i></a>';
            }
            $html .= '</td>' .
                    '</tr>';
        }*/

        $this->layout->assign('script', $script);
        $this->layout->assign('offshores', $offshore);
        $this->layout->assign('stock_function', $this);
        $html = $this->layout->ajax('offshore' . DS . 'ajax' . DS . 'table');
        return $html;
    }

    private function loadTable2()
    {
        $offshores = $this->Offshore->all([], 'offid DESC');
        $html     = '';
        $i = 1;
        foreach ($offshores as $offshore) {
            $html .= '<tr>' .
                '<td>' . $i++ . '</td>' .
                '<td>' . $offshore->prodesignation . '</td>' .
                '<td>' . $offshore->famille . '</td>';

            if (in_array('107', $_SESSION['stkdroits'])) {
                $html .= '<td><span style="font-style: italic;">'  . '</span></td>';
            }

            $html .= '<td><span style="font-style: italic;">' .  '</span></td>';

            if (in_array('107', $_SESSION['stkdroits'])) {
                $html .= '<td><span style="font-style: italic;">' .  '</span></td>';
            }

            if (in_array('107', $_SESSION['stkdroits'])) {
                $html .= '<td>';
                if($this->stock_magasin_by_produit($offshore->offid) == 0) {
                    $html .= '<span style="font-style: italic; font-size: 12px;">rupture</span>';
                }else {
                    $html .= $this->stock_magasin_by_produit($offshore->offid) . '</td>';
                }
            }

            $html .= '<td>';
            if ($this->stock_boutique_by_produit($offshore->offid) == 0) {
                $html .=  '<span style="font-style: italic; font-size: 12px;">rupture</span>';
            }else {
                $html .= $this->stock_boutique_by_produit($offshore->offid) . '</td>';
            }
            $html .= '<td>';
            if (in_array('103', $_SESSION['stkdroits'])) {
                $html .= '<a data-toggle="modal" href="#editeOffshore" onclick="laodForEditeOffshore(' . $offshore->offid . '); return false" title="editer"><i class="fa fa-edit" style="color: #05AE0E;"></i></a>';
            }else {
                $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-edit"></i></a>';
            }
            if (in_array('102', $_SESSION['stkdroits'])) {
                $html .= '<a data-toggle="modal" href="#deletProuit" onclick="laodDataForDeleteOffshore(' . $offshore->offid . '); return false;" title="supprimer"><i class="fa fa-remove" style="color: #FF3B30;"></i></a>';
            } else {
                $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-remove"></i></a>';
            }
            $html .= '</td>' .
                '</tr>';
        }
        return $html;
    }
    
    private function autoLoad () {
        
        $offshores = $this->offshores;
        $tmps     = [];
        $i = 1;
        foreach ($offshores as $offshore) {
            $p = [
                    'id'              => $offshore->offid,
                    'name'            => $offshore->prodesignation,
                    'family'          => $offshore->famille,
                    'prixUnitVente'   => '<span style="font-style: italic;">' . number_format($offshore->proprixUnitVente, 2, ',', ' ') . '</span>',
                    'prixGlobalVente' => '<span style="font-style: italic;">' . number_format($offshore->proprixblogVente, 2, ',', ' ') . '</span>',
                    'stockboutique'   => $offshore->proprixblogVente
                 ];
            if ($this->stock_boutique_by_produit($offshore->offid) == 0) {
                $p ['stockboutique'] = '<span style="font-style: italic; font-size: 12px;">rupture</span>';
            } else {
                $p ['stockboutique'] = $this->stock_boutique_by_produit($offshore->offid);
            }
            $tmps [] = $p;
        }
        
        return $tmps;
    }
    
    
}