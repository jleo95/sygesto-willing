<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 28/10/2018
 * Time: 17:47
 */

namespace App\Controllers;


use App\Core\Controller;

class ProduitController extends Controller
{

    private $produits = [];

    public function __construct()
    {
        parent::__construct();
        
//        $this->calledMethodesChild();

        $this->produits = $this->Produit->all([], 'proid DESC');
//        var_dump($this->stock_magasin_by_produit(409));
      
    }


    public function index()
    {
        $this->layout->setTitle('Produits');
        $this->layout->setTitle('Liste des produits', 'v');
        $this->layout->assign('produits', $this->Produit->all([], 'proid DESC'));
        $this->layout->assign('fournisseurs', $this->Fournisseur->all());
        $this->layout->assign('unites', $this->Unite->all());
        $this->layout->assign('familles', $this->Famille->all());
        $this->layout->assign('mouvement', $this->Mouvement);
        $this->layout->assign('tbodyProduit', $this->loadTable());
        $this->layout->setJS('produit' . DS . 'index');
        $this->layout->setStyle('produit' . DS . 'produit');
        $this->layout->render('produit' . DS . 'index');
        
    }

    #ajout d'un produit
    public function add()
    {
        $data = [];
        $error = [];

        if (isset($this->input->designAddProduit) AND !empty($this->input->designAddProduit)) {
            $data ['prodesignation'] = $this->input->designAddProduit;
        }else {
            $error ['danger']['designation'] = true;
        }


        if (isset($this->input->nbblogAddProduit) AND !empty($this->input->nbblogAddProduit)) {
            $data ['pronbproduitBlog'] = $this->input->nbblogAddProduit;
        }

       

        if (isset($this->input->peremptionAddProduit) AND !empty($this->input->peremptionAddProduit)) {
            $data ['prodatePeremption'] = $this->input->peremptionAddProduit;
        }else {
            $error ['danger']['peremption'] = true;
        }

        if (isset($this->input->familleAddProduit) AND !empty($this->input->familleAddProduit)) {
            $data ['profamille'] = $this->input->familleAddProduit;
        }

        if (isset($this->input->uniteAddProduit) AND !empty($this->input->uniteAddProduit)) {
            $data ['prounitemessure'] = $this->input->uniteAddProduit;
        }else {
            $error ['danger']['unitemesure'] = true;
        }

        if (isset($this->input->fournisseurAddProduit) AND !empty($this->input->fournisseurAddProduit)) {
            $data ['profournisseur'] = $this->input->fournisseurAddProduit;
        }

        if (isset($this->input->seuilAddProduit) AND !empty($this->input->seuilAddProduit)) {
            $data ['proseuilalert'] = $this->input->seuilAddProduit;
        }

        $response = [];


        if (empty($error)) {
            $data['prodatecreation'] = date('Y-m-d H:i:s', time());
            $data['prorealiserpar'] = $this->session->stkiduser;
            if ($this->Produit->insert($data)) {

                $produit = $this->Produit->get_by('proid', $this->Produit->lastInsert());

                $produitHtml = [
                    'id' => $produit->proid,
                    'name' => $produit->prodesignation,
                    'family' => $produit->famille,
                    'stockboutique' => ''
                ];

                $response['produit']           = $produitHtml;
                $response ['error']            = 0;
                $this->produits                = $this->Produit->all();
                $html                          = $this->loadTable();
                $response ['bodyTableProduit'] = $html;
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
        $this->layout->assign('unites', $this->Unite->all());
        $this->layout->assign('familles', $this->Famille->all());
        $this->layout->assign('mouvement', $this->Mouvement);
        echo $this->layout->ajax('produit' . DS . 'ajax' . DS . 'add');
    }

    #chargement des element pour la suppression
    public function laodDataForDeleteProduit()
    {
        $produit = $this->Produit->get_by('proid', $this->input->idProduit);
        $html = '<div>' .
                '<span>Voulez-vous supprimer le produit: </span>' . ucfirst($produit->prodesignation) . ' ?' .
                '</div>';

        echo json_encode(['modalBody' => $html, 'nameProduit' => ucfirst($produit->prodesignation)]);
    }

    #suppression d'un produit
    public function delete()
    {
        if ($this->Produit->delete_by('proid', $this->input->idProduit)) {
            $html = $this->loadTable(true);
            echo json_encode(['succes' => 0, 'tbodyProduit' => $html]);
        }else {
            echo json_encode(['succes' => 1]);
        }
    }

    #chargement des donnee pour l'edition
    public function laodForEditeProduit()
    {
        $this->layout->assign('produit', $this->Produit->get_by('proid', $this->input->idProduit));
        $this->layout->assign('fournisseurs', $this->Fournisseur->all());
        $this->layout->assign('familles', $this->Famille->all());
        $this->layout->assign('unites', $this->Unite->all());
        $contentAjax = $this->layout->ajax('produit' . DS . 'ajax' . DS . 'edit');
        echo json_encode(['modalBody' => $contentAjax]);
    }

    #editer un produit
    public function edit()
    {
        $produit = $this->Produit->get_by('proid', $this->input->idProduitEdit);
        $data    = [];
        $error   = [];

        if (isset($this->input->designEditProduit) AND !empty($this->input->designEditProduit)) {
            if ($produit->prodesignation !== $this->input->designEditProduit) {
                $data ['prodesignation'] = $this->input->designEditProduit;
            }
        }else {
            $error ['danger']['designation'] = true;
        }

        

        if (isset($this->input->nbblogEditProduit) AND !empty($this->input->nbblogEditProduit)) {
            if ($this->input->nbblogEditProduit !== $produit->pronbproduitBlog) {
                $data ['pronbproduitBlog'] = $this->input->nbblogEditProduit;
            }
        }

        

        if (isset($this->input->peremptionEditProduit) AND !empty($this->input->peremptionEditProduit)) {
            if ($this->input->peremptionEditProduit !== $produit->prodatePeremption) {
                $data ['prodatePeremption'] = $this->input->peremptionEditProduit;
            }
        }else {
            $error ['danger']['peremption'] = true;
        }

        if (isset($this->input->familleEditProduit) AND !empty($this->input->familleEditProduit)) {
            if ($this->input->familleEditProduit !== $produit->profamille) {
                $data ['profamille'] = $this->input->familleEditProduit;
            }
        }

        if (isset($this->input->uniteEditProduit) AND !empty($this->input->uniteEditProduit)) {
            if ($this->input->uniteEditProduit !== $produit->prounitemessure) {
                if ($this->input->uniteEditProduit != 'aucun') {
                    $data ['prounitemessure'] = $this->input->uniteEditProduit;
                }
            }

        }else {
            $error ['danger']['unitemesure'] = true;
        }

        if (isset($this->input->fournisseurEditProduit) AND !empty($this->input->fournisseurEditProduit)) {
            if ($this->input->fournisseurEditProduit !== $produit->profournisseur) {
                $data ['profournisseur'] = $this->input->fournisseurEditProduit;
            }
        }

        if (isset($this->input->seuilEditProduit) AND !empty($this->input->seuilEditProduit)) {
            if ($this->input->seuilEditProduit !== $produit->proseuilalert) {
                $data ['proseuilalert'] = $this->input->seuilEditProduit;
            }
        }

        $nameProduit = ucfirst($produit->prodesignation);
        if (empty($error)) {
            if (!empty($data)) {
                if ($this->Produit->update($data, ['proid' => $this->input->idProduitEdit])) {
                    $modalFooter                    = '<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-primary" onclick="confirmEdit(0)">Ok</button></div>';
                    $response ['error']             = 0;
                    $response ['modalFooter']       = $modalFooter;
                    $this->produits                 = $this->Produit->all();
                    $response ['tbodyTableProduit'] = $this->loadTable();
                    $produit = $this->Produit->get_by('proid', $this->input->idProduitEdit);
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

        $response ['nameProduit'] = ucfirst($produit->prodesignation);
        echo json_encode($response);
    }

    public function trieProduit()
    {
        $produits       = $this->Produit->all([], 'proid DESC', FALSE);
        $tmp            = [];
        $etat           = '';
        $this->produits = [];

        if ($this->input->idTrie == 1) {
            foreach ($produits as $produit) {
                if ($this->stock_boutique_by_produit($produit->proid) <= $produit->proseuilalert AND $this->stock_boutique_by_produit($produit->proid) != 0) {
                    $tmp [] = $produit;
                }
            }
            $etat = 'en alerte';
        }elseif ($this->input->idTrie == 2) {
            foreach ($produits as $produit) {
                if ($this->stock_boutique_by_produit($produit->proid) == 0) {
                    $tmp [] = $produit;
                }
            }
            $etat = 'en rupture';
        }elseif ($this->input->idTrie == 3) {
            foreach ($produits as $produit) {
                if (
                    $this->stock_boutique_by_produit($produit->proid) > $produit->proseuilalert AND
                    $this->stock_boutique_by_produit($produit->proid) > 0
                ) {
                    $tmp [] = $produit;
                }
            }
            $etat = 'en stock';
        }else {
            $tmp = $produits;
        }

        $this->produits = $tmp;

        echo json_encode([
            'bodyTableProduit' => $this->autoLoad(),
            'etat' => $etat
        ]);
    }

    public function showAllInfosProduit()
    {
        $html = '';
        $produit = $this->Produit->get_by('proid', $this->input->idProduit);
        $header_stock = '<span style="color: #008000; font-size: 15px; font-weight: bold;"> (Disponible)</span>';

        $html = '<div class="produit" style="padding-bottom: 30px;">' .
            '<div class="line"><span class="label">Designation: </span><span class="value">' . $produit->prodesignation . '</span></div>' .
            '<div class="line"><span class="label">Famille: </span><span class="value">' . $produit->famille . '</span></div>' .
//            '<div class="line"><span class="label">Date de peremptioin: </span><span class="value">' . $dateperemption . '</span></div>' .
            '<div class="line"><span class="label">Unité de mésure: </span><span class="value">' . $produit->unite . '</span></div>' .
            
        
            '<div class="line"><span class="label">Nombre de produit par blog: </span><span class="value">' . $produit->pronbproduitBlog . '</span></div>';
        
        $html .= '</div>';

        $html .= '<div class="line"><span class="label">Fournisseur: </span><span class="value">' . ucfirst($produit->founom) . ' ' . ucfirst($produit->fouprenom) . '</span></div>' .
            '<div class="line"><span class="label">Seuil d\'alerte: </span><span class="value">' . $produit->proseuilalert . '</span></div>' .
            '</div>';

        echo json_encode([
            'infosProduit' => $html,
            'header_stock' => $header_stock
        ]);
    }

    public function imprimer()
    {
        $pdf       = new \App\Core\PDF();
        $produits      = $this->Produit->all([], 'proid ASC');

        $this->layout->assign('pdf', $pdf);
        $this->layout->assign('stock_function', $this);
        $this->layout->assign('pdf', $pdf);
        $this->layout->assign('produits', $produits);
        $this->layout->render('produit' . DS . 'impression' . DS . 'stock', TRUE);
    }

    #chargement des element de la table qui contient la liste des produit
    private function loadTable($script = false)
    {
        $produits = $this->produits;
        $html     = '';
        $i = 1;

        foreach ($produits as $produit) {
            $html .= '<br><tr>' .
                '<td>' . $produit->proid . '</td>' .
                '<td>' . $produit->prodesignation . '</td>' .
                '<td>' . $produit->famille . '</td>' .

             $html .= '<td>';
             if (in_array('107', $_SESSION['stkdroits'])) {
                 $html .= '<a data-toggle="modal" href="#showAllInfosProduit" onclick="showAllInfosProduit(' . $produit->proid . '); return false" title="voir plus" class="table-action more-infos text-primary"><i class="fa fa-eye" style="color: #36AFEC;"></i></a>&nbsp;';
             }else {
                 $html .= '<a href="javascript: void(0)" class="disabled btn abtn abtn-primary" title="voir plus"><i class="fa fa-eye-slash"></i></a>&nbsp;';
             }
             if (in_array('103', $_SESSION['stkdroits'])) {
                 $html .= '<a data-toggle="modal" href="#editeProduit" onclick="laodForEditeProduit(' . $produit->proid . '); return false" title="editer" class="table-action edit-produit text-success"><i class="fa fa-pencil" style="color: #05AE0E;"></i></a>';
             }else {
                 $html .= '<a href="javascript: void(0)" class="disabled btn abtn abtn-success" title="editer"><i class="fa fa-pencil"></i></a>';
             }
            if (in_array('102', $_SESSION['stkdroits'])) {
                $html .= '<a data-toggle="modal" href="#deletProuit" onclick="laodDataForDeleteProduit(' . $produit->proid . '); return false;" title="supprimer" class="table-action remove-produit text-danger"><i class="fa fa-trash-o " style="color: #FF3B30;"></i></a>';
             } else {
                $html .= '<a href="javascript: void(0)" class="disabled btn abtn abtn-danger" title="editer"><i class="fa fa-trash-o "></i></a>';
            }
            $html .= '</td>' .
                    '</tr>';
        }

        $this->layout->assign('script', $script);
        $this->layout->assign('produits', $produits);
        $this->layout->assign('stock_function', $this);
        $html = $this->layout->ajax('produit' . DS . 'ajax' . DS . 'table');
        return $html;
    }

    private function loadTable2()
    {
        $produits = $this->Produit->all([], 'proid DESC');
        $html     = '';
        $i = 1;
        foreach ($produits as $produit) {
            $html .= '<tr>' .
                '<td>' . $i++ . '</td>' .
                '<td>' . $produit->prodesignation . '</td>' .
                '<td>' . $produit->famille . '</td>';

            

            $html .= '<td>';
            if (in_array('103', $_SESSION['stkdroits'])) {
                $html .= '<a data-toggle="modal" href="#editeProduit" onclick="laodForEditeProduit(' . $produit->proid . '); return false" title="editer"><i class="fa fa-edit" style="color: #05AE0E;"></i></a>';
            }else {
                $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-edit"></i></a>';
            }
            if (in_array('102', $_SESSION['stkdroits'])) {
                $html .= '<a data-toggle="modal" href="#deletProuit" onclick="laodDataForDeleteProduit(' . $produit->proid . '); return false;" title="supprimer"><i class="fa fa-remove" style="color: #FF3B30;"></i></a>';
            } else {
                $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-remove"></i></a>';
            }
            $html .= '</td>' .
                '</tr>';
        }
        return $html;
    }
    
    private function autoLoad () {
        
        $produits = $this->produits;
        $tmps     = [];
        $i = 1;
        foreach ($produits as $produit) {
            $p = [
                    'id'              => $produit->proid,
                    'name'            => $produit->prodesignation,
                    'family'          => $produit->famille
                    
                   
                 ];
            
            $tmps [] = $p;
        }
        
        return $tmps;
    }
    
    
}