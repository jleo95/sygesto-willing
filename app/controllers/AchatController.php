<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 04/11/2018
 * Time: 12:21
 */

namespace App\Controllers;


use App\Core\Controller;
use App\Core\Router;

class AchatController extends Controller
{

    private $offres = [];
    public function __construct()
    {
        parent::__construct();

        $this->offres = $this->Offre->all([], 'offid DESC');
    }


    public function index()
    {
        $this->layout->assign('commandes', $this->Offre->all());
        $this->layout->assign('tbodyTable', $this->loadTable());

        $this->layout->setTitle('Achat');
        $this->layout->setTitle('Liste de tous les achat', 'v');
        $this->layout->setJS('achat' . DS . 'index');
        $this->layout->setStyle('achat' . DS . 'commande');
        $this->layout->render('achat' . DS . 'index');
    }

    public function laodForShowCommande()
    {
        $offre_detail = $this->Offredetail->show_produit_by_offre($this->input->idOffre);
        $offre = $this->Offre->get_by('offid', $this->input->idOffre);
        $html         = '';
        $prixTotal    = 0;
        $i            = 0;


        $html       .= '<div class="detail">' .
                        '<div><span class="label">Fournisseur: </span>' . $offre->founom . ' ' . $offre->fouprenom . '</div>' .
                        '<div><span class="label">Date: </span>' . $offre->offdate . '</div>' .
                        '</div>';

        $html       .= '<div class="produits scrollbar-deep-purple" style="overflow-y: auto">';
        foreach ($offre_detail as $detail) {
            if ($i == count($offre_detail) - 1) {
                $html       .= '<div class="produit end">' .
                                '<div class="name"><span class="label">Produit: </span>' . $detail->prodesignation . '</div>' .
//                                '<div class="prix"><span class="label">Prix unitaire: </span>' . $detail->proprixUnitVente . '</div>' .
                                '<div class="quantite"><span class="label">Quantité: </span>' . $detail->quantite . '</div>' .
//                                '<div class="quantite"><span class="label">Prix total: </span> <span style="font-style: italic;">' . $detail->proprixUnitVente * $detail->quantite . '</span></div>' .
                                '</div>';
            }else {
                $html       .= '<div class="produit">' .
                                '<div class="name"><span class="label">Produit: </span>' . $detail->prodesignation . '</div>' .
//                                '<div class="prix"><span class="label">Prix unitaire: </span>' . $detail->proprixUnitVente . '</div>' .
                                '<div class="quantite"><span class="label">Quantité: </span>' . $detail->quantite . '</div>' .
//                                '<div class="quantite"><span class="label">Prix total: </span> <span style="font-style: italic;">' . $detail->proprixUnitVente * $detail->quantite . '</span></div>' .
                                '</div>';
            }
            $i ++;


//            $prixTotal += $detail->proprixUnitVente * $detail->quantite;
        }

        $html .= '</di>';
//        $html .= '<div class="prixTatal"><span class="label">Prix totaux: </span><span class="value">' . number_format($prixTotal, 2, ',', ' ') . ' fcfa</span></div>';
        echo json_encode([
            'bodyModal' => $html,
            'headerModal' => 'Commande (' . $offre->offid . ')'
        ]);
    }

    public function add()
    {
        if ($_POST) {

            if (isset($_POST['btnBeginProcessOffre'])) {

                $data  = [];
                $error = [];

                if (isset($this->input->fournisseurOffre) AND !empty($this->input->fournisseurOffre)) {
                    $data ['offfournisseur'] = $this->input->fournisseurOffre;
                }else {
                    $error ['fournisseurOffre'] = true;
                }
//                if (isset($this->input->dateOffre) AND !empty($this->input->dateOffre)) {
//                    $data ['offdate'] = $this->input->dateOffre;
//                }else {
//                    $error ['dateOffre'] = true;
//                }

                if (isset($this->input->dateLivraisonOffre) AND !empty($this->input->dateLivraisonOffre)) {
                    $data ['offdateLivraison'] = $this->input->dateLivraisonOffre;
                }else {
                    $error ['dateLivraisonOffre'] = true;
                }
//                if (isset($this->input->etat) AND !empty($this->input->etat)) {
//                    $data ['offvalidite'] = $this->input->etat;
//                }else {
//                    $error ['etat'] = true;
//                }

                if (empty($error) AND !empty($data)) {
                    $_SESSION['offre'] = $data;
                    header('Location:' . Router::url('achat', 'add'));
                }else {
                    $this->layout->assign('error', false);
                }
            }elseif (isset($_POST['btnEndProcessOffre'])) {
                $data = [];
                $offre = $this->session->offre;
                $offre['offrealiserpar'] = $this->session->stkiduser;
                $offre['offpaiement'] = $this->input->modePayement;
//
//                var_dump($offre);
//                die();
                if ($this->Offre->insert($offre)) {
                    $lastId = $this->Offre->lastInsert();

                    foreach ($this->session->offre_detail_db as $detail) {
                        $detail ['offre'] = $lastId;
//                        $detail['date'] = date('Y-m-d H:i:s', time());
                        $this->Offredetail->insert($detail);
                    }
                }
                unset($_SESSION['offre']);
                unset($_SESSION['offre_detail']);
                unset($_SESSION['offre_detail_db']);
                header('Location:' . Router::url('achat'));
            }
        }

        $idOffre = $this->Offre->lastInsert() + 1;
        $this->layout->assign('idOffre', $idOffre);
        $this->layout->assign('fournisseurs', $this->Fournisseur->all([], 'founom'));
        $this->layout->assign('paiements', $this->Paiement->all());

        $this->layout->setTitle('Commande');
        $this->layout->setTitle('Nouvelle commande (' . $idOffre . ')', 'v');
        $this->layout->setJS('achat' . DS . 'index');
        $this->layout->setStyle('achat' . DS . 'commande');
        $this->layout->render('achat' . DS . 'add');
    }

    public function loadProduitOffre()
    {
        $produits = $this->Produit->all([], 'proid DESC');
        $stock = $this;

        $this->layout->assign('produits', $produits);
        $html = $this->layout->ajax('achat' . DS . 'ajax' . DS . 'produits');

        echo json_encode([
            'bodyModal' => $html
        ]);
    }

    public function loadProduitForAddOffre()
    {
        $produit = $this->Produit->get_by('proid', $this->input->idProduit);
        $this->layout->assign('produit', $produit);
        $this->layout->assign('famille', ucfirst($this->Famille->get_by('famid', $produit->profamille)->famlibelle));

//        $html    = '<div class="addProduitOffre">';
//
//        $html   .= '<input type="hidden" name="hiddenIdProduitCommande" id="hiddenIdProduitCommande"><br>';
//        $html   .= '<div><span class="label">Produit: </span>' . ucfirst($produit->prodesignation) . '</div><br>';
//        $html   .= '<div><span class="label">Famille: </span>' . ucfirst($this->Famille->get_by('famid', $produit->profamille)->famlibelle) . '</div><br>';
//        $html   .= '<div><span class="label">Quantité: </span> <input type="number" name="qtiteProduitOffre" id="qtiteProduitOffre"></div>';
//        $html   .= '<div class="clear"><div class="btn-group-sm right"><input type="button" data-dismiss="modal" value="Ajouter" class="btn btn-primary" onclick="addProduitInCommand(' . $produit->proid . ')"></div></div>';
//
//        $html   .= '</div>';

        echo json_encode([
            'bodyModal' => $this->layout->ajax('achat' . DS . 'ajax' . DS . 'modalAddProduit'),
            'headerModal' => ucfirst($produit->prodesignation)
        ]);
    }

    public function addProduitInCommand()
    {
        $produit = $this->Produit->get_by('proid', $this->input->idProduit);
        $_SESSION['offre_detail_db'][$produit->proid] = [
          'produit' => $produit->proid,
          'offre' => $this->input->idOffre,
          'quantite' => $this->input->quantite
        ];
        $_SESSION['offre_detail'][$produit->proid] = [
          'idProduit' => $produit->proid,
          'produit' => ucfirst($produit->prodesignation),
          'famille' => ucfirst($this->Famille->get_by('famid', $produit->profamille)->famlibelle),
//          'prix' => $produit->proprixUnitAchat,
          'quantite' => $this->input->quantite
        ];

        $html    = '<tr>';
        $html   .= '<td>' . ucfirst($produit->prodesignation) . '</td>' .
                   '<td>' . ucfirst($this->Famille->get_by('famid', $produit->profamille)->famlibelle) . '</td>' .
//                   '<td>' . $produit->proprixUnitAchat . '</td>' .
                   '<td>' . $this->input->quantite . '</td>' .
                   '<td>' .
                   '<a href="javascript: removeProduitOffre(' . $produit->proid . ')"><i class="fa fa-remove" style="color: #EE0000;"></i></a>' .
                   '</td>';
        $html   .= '</tr>';

        echo json_encode([
            'lineTable' => $html
        ]);
    }

    public function removeProduitOffre()
    {
        $tmp = $_SESSION['offre_detail'];
        $_SESSION['offre_detail'] = [];

        foreach ($tmp as $k => $v) {
            if ($k !== intval($this->input->idProduit)) {
                $_SESSION['offre_detail'][$k] = $v;
            }
        }
        $tmp = $_SESSION['offre_detail_db'];
        $_SESSION['offre_detail_db'] = [];

        foreach ($tmp as $k => $v) {
            if ($k !== intval($this->input->idProduit)) {
                $_SESSION['offre_detail_db'][$k] = $v;
            }
        }

        echo json_encode(['error' => 0]);
    }

    public function livres()
    {
        $this->offres = $this->Offre->get_by('offvalidite', 1, [], 'offid DESC', FALSE);
        $this->layout->assign('tbodyTable', $this->loadTable());

        $this->layout->setTitle('Achat livrés');
        $this->layout->setTitle('Liste des achats livrés', 'v');
        $this->layout->render('achat' . DS . 'livres');
    }

    public function trieAchat()
    {
        $idTrie = intval($this->input->idTrie);

        if ($idTrie == 1) {
            $this->offres = $this->Offre->get_by('offvalidite', 1, [], 'offid DESC', FALSE);
        }elseif ($idTrie == 2) {
            $this->offres = $this->Offre->get_by('offvalidite', 0, [], 'offid DESC', FALSE);
        }elseif ($idTrie == 3) {
            $this->offres = $this->Offre->get_by('offpaiement', 2, [], 'offid DESC', FALSE);
        }

        echo json_encode([
            'bodyTableOffre' => $this->autoLoad()
        ]);
    }
    
    public function loadForEdit() {
        $offre = $this->Offre->get_by('offid', $this->input->idOffre);
        $tmp = [
            'id' => $offre->offid,
            'date' => date('Y-m-d', strtotime($offre->offdate)),
            'dateLivraison' => $offre->offdateLivraison,
            'validite' => $offre->offvalidite,
            'fournisseur' => $offre->offfournisseur,
            'paiment' => $offre->offpaiement
        ];
        
        $this->layout->assign('fournisseurs', $this->Fournisseur->all());
        $this->layout->assign('offre', $tmp);
        $content = $this->layout->ajax('achat' . DS . 'ajax' . DS . 'editOffre');
        
        echo json_encode([
            'bodyModal' => $content,
            'offre' => $tmp,
            'idOffre' => $this->input->idOffre,
                ]);
    }
    
    public function edit () {
        $data = [];
        $error = 1;
        $offre = $this->Offre->get_by('offid', $this->input->hiddenIdOffreForEdit);
        if ($offre) {
            if (isset($this->input->fournisseurOffre) AND !empty($this->input->fournisseurOffre) AND $this->input->fournisseurOffre != $offre->offfournisseur) {
                $data ['offfournisseur'] = $this->input->fournisseurOffre;
            }
            if (isset($this->input->dateOffre) AND !empty($this->input->dateOffre) AND $this->input->dateOffre != date('Y-m-d', strtotime($offre->offdate))) {
                $data ['offdate'] = date('Y-m-d H:i:s', strtotime($this->input->dateOffre));
            }
            if (isset($this->input->dateLivraisonOffre) AND !empty($this->input->dateLivraisonOffre) AND $this->input->dateLivraisonOffre != $offre->offdateLivraison) {
                $data ['offfournisseur'] = $this->input->fournisseurOffre;
            }
            if (isset($this->input->statusOffre) AND !empty($this->input->dateLivraisonOffre)) {
                $status = 1;
                if ($this->input->statusOffre == 1) {
                    $status = 0;
                }
                
                if ($this->input->statusOffre != $offre->offvalidite) {
                    $data ['offvalidite'] = $status;
                }
            }
            
            if (!empty($data)) {
                $this->Offre->update($data, ['offid' => $this->input->hiddenIdOffreForEdit]);
                $error = 0;
                $_SESSION['offre']['edit'] = true;
            }
        }
        
        echo json_encode([
                    'error' => $error
                ]); 
    }

    private function loadTable()
    {
        $commandes = $this->offres;
        $html      = '';

        foreach ($commandes as $commande) {
            $fournisseur = $this->Fournisseur->get_by('fouid', $commande->offfournisseur);
            $html .= '<tr>' .
                          '<td>' . ucfirst($commande->offid) . '</td>' .
                          '<td>' . ucfirst($fournisseur->founom) . ' ' . ucfirst($fournisseur->fouprenom) . '</td>' .
                          '<td>' . $commande->offdate . '</td>' .
                          '<td><span style="font-style: italic; font-size: 12px">';
                    if(intval($commande->offvalidite) == 1) {
                        $html .= 'validé';
                    }else {
                        $html .= 'en cours';
                    }
                        $html .= '</span></td>';
                    if (intval($commande->offvalidite) == 1) {
                        $html .= '<td>' . ucfirst($this->Paiement->get_by('paiid', $commande->offpaiement)->pailibelle) . '</td>';
                    }else {
                        $html .= '<td><span style="font-style: italic; font-size: 12px;">/</span></td>';
                    }
                        $html .= '<td>';
                    if (in_array('703', $this->session->stkdroits)) {
                        $html .= '<a data-toggle="modal" href="#showCommande" onclick="laodForShowCommande(' . $commande->offid . '); return false" title="voir"><i class="fa fa-eye" style="color: #36AFEC;"></i></a>';
                    } else {
                        $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-shower"></i></a>';
                    }
                    if (in_array('703', $this->session->stkdroits)) {
                        $html .= '<a data-toggle="modal" href="#editecommande" onclick="laodForEditcommande(' . $commande->offid . '); return false" title="editer"><i class="fa fa-edit" style="color: #05AE0E;"></i></a>';
                    }else {
                        $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-edit"></i></a>';
                    }
                    if (in_array('704', $this->session->stkdroits)) {
                        $html .= '<a data-toggle="modal" href="#deletcommande" onclick="laodDataForDeletecommande(' . $commande->offid . '); return false;" title="supprimer"><i class="fa fa-remove" style="color: #FF3B30;"></i></a>';
                    } else {
                        $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-remove"></i></a>';
                    }
                        $html .= '</td>';
            $html .= '</tr>';
        }

        return $html;
    }
    
    private function autoLoad() {
        $tmps = $this->offres;
        $offres = [];
        $validite = '';
        $paiement = '';
        
        foreach ($tmps as $offre) {
            $fournisseur = $this->Fournisseur->get_by('fouid', $offre->offfournisseur);   
            
            $validite = '<span style="font-style: italic; font-size: 12px">';
            if(intval($offre->offvalidite) == 1) {
                $validite .= 'validé';
            }else {
                $validite .= 'en cours';
            }
            $validite .= '</span>';
            if (intval($offre->offvalidite) == 1) {
                $paiement = ucfirst($this->Paiement->get_by('paiid', $offre->offpaiement)->pailibelle);
            }else {
                $paiement = '<span style="font-style: italic; font-size: 12px;">/</span>';
            }
            $offres [] = [
                'num' => $offre->offid,
                'contact' => ucfirst($fournisseur->founom) . ' ' . ucfirst($fournisseur->fouprenom),
                'date' => $offre->offdate,
                'state' => $validite,
                'payement' => $paiement,
            ];
        }
        
        return $offres;
    }

}