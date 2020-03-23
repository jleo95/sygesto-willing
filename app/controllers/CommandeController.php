<?php
/**
 * Created by PhpStorm.
 * User: antol
 * Date: 3/11/2020
 * Time: 1:17 AM
 */

namespace App\Controllers;


use App\Core\Controller;
use App\Core\Router;

class CommandeController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->commandes = $this->Offre->all([], 'offdate DESC');
    }

    /**
     * Page d'accueil, affiche la liste de toutes les commandes passees
     */
    public function index()
    {
        $this->layout->assign('commandes', $this->loadTable());
        $this->layout->setJS('commande' . DS . 'index');
        $this->layout->render('commande' . DS . 'index');
    }

    /**
     * Ajouter une nouvelle commande
     */
    public function ajout()
    {
        if (!isset($_SESSION['listProduitInCommnade']))
            $_SESSION['listProduitInCommnade'] = [];

        if ($_POST) {

            $error = [];

            if (isset($this->input->paiement) && !empty($this->input->paiement))
                $offre['offpaiement'] = $this->input->paiement;
            else
                $error['paiement'] = true;

            if (isset($this->input->offshore) && !empty($this->input->offshore))
                $offre['offshore'] = $this->input->offshore;
            else
                $error['offshore'] = true;


            $offre['offdate'] = (isset($this->input->dateCommande) && !empty($this->input->dateCommande)) ? $this->input->dateCommande : date('Y-m-d H:i', time());

            if (!empty($error)) { #gestion de erreura
                #a revoir la gestion des erreur
                $this->layout->assign('errors', $error);
            }else{ #si on a aucune erreur on passe a l'insertion des donnees la base de donnees
                $offre['offrealiserpar'] = $this->session->stkiduser;
                if ($this->Offre->insert($offre)) { #insertion de la commande dans la table offrea (si elle se passe bien on insert maintenant ces detail dans la table offre_detail)
                    $offid = $this->Offre->lastInsert();
                    $commandes = $_SESSION['listProduitInCommnade'];

                    #insertion des detail de la commande
                    foreach ($commandes as $produit) {
                        $this->Offredetail->insert([
                            'produit' => $produit['produit'],
                            'offre' => $offid,
                            'quantite' => $produit['quantite']
                        ]);
                    }
                    #on cres une variable de session pour les message de succes
                    $_SESSION['commandeAddIsSuccess'] = 1;

                    #on supprime toute les variables de session a l'exception de $_SESSION['commandeAddIsSuccess']
                    unset($_SESSION['listProduitInCommnade']);
                    unset($_SESSION['process']);
                    unset($error);
                    header('Location:' . Router::url('commande'));
                }
            }
        }

        $this->layout->assign('offshores', $this->Offshore->all());
        $this->layout->assign('clients', $this->Client->all());
        $this->layout->assign('paiements', $this->Paiement->all());
        $this->layout->setTitle('Nouvelle commande', 'v');
        $this->layout->setTitle('Nouvelle commande');
        $this->layout->render('commande' . DS . 'ajout');
    }


    /**
     * Selectionner un produit pour la commande
     */
    public function selectproduit()
    {
        $this->layout->assign('produits', $this->Produit->all());
        $this->layout->setTitle('Selectionner un produit', 'v');
        $this->layout->setTitle('Selectionner un produit');
        $this->layout->render('commande' . DS . 'selectproduit');
    }


    /**
     * Ajout d'un produit dans liste de commande
     */
    public function addProduitToCommande()
    {

        $type = intval($this->input->type);

        if ($type == 1) {
            $_SESSION['listProduitInCommnade'][] = [
                'produit' => intval($this->input->idProduit),
                'designation' => intval($this->input->idProduit),
                'famille' => $this->input->famille,
                'unite' => $this->input->unite,
                'quantite' => intval($this->input->quantite)
            ];
            echo json_encode([
                'response' => 'success'
            ]);
        }elseif($type == 2) {
            $_SESSION['process'] = true;
            echo json_encode([
                'response' => 'success'
            ]);
        }


    }

    public function deletProduitFromCommande()
    {
        if (count($_SESSION['listProduitInCommnade']) == 1)
            $_SESSION['listProduitInCommnade'] = [];
        else
            unset($_SESSION['listProduitInCommnade'][intval($this->input->line) - 1]);
        echo json_encode([
            'response' => 'success'
        ]);
    }


    /**
     * Voir les detail d'une commande donnee
     * @param $id int l'identifiant de la commande qu'on veut voir ses details
     */
    public function voir($id)
    {
//        $groupByFamille = $this->Offredetail->show_produit_by_offre_groupBy_famille($id);
        $details = $this->Offredetail->show_offre_detail_by_offre($id);
        $groupByfamille = [];

        foreach ($details as $detail) {
            foreach ($details as $d) {
                if ($detail->famille_id == $d->famille_id) {
                    $groupByfamille[$detail->famille][] = $d;
                }
            }
        }

        $argColors = [
            '#efefefe',
            '#FFA07A',
            '#CD5C5C',
            '#FF7F50',
            '#FFA500',
            '#FFFACD',
            '#BDB76B',
            '#32CD32',
            '#008000',
            '#3CB371',
            '#AFEEEE'
        ];

        $commande = $this->Offre->get_by($nameFields = 'off.offid', $value = intval($id));
        $this->layout->assign('commande', $commande);
        $this->layout->assign('argColors', $argColors);
        $this->layout->assign('groupByfamille', $groupByfamille);
        $this->layout->setTitle('Voir Commande');
        $this->layout->setTitle('Demande d\'achat #' . $id, 'v');
        $this->layout->render('commande' . DS . 'voir');
    }

    #imprimer une commande
    public function imprimer($id)
    {
        $pdf       = new \App\Core\PDF();

        $details = $this->Offredetail->show_offre_detail_by_offre($id);
        $groupByfamille = [];

        foreach ($details as $detail) {
            foreach ($details as $d) {
                if ($detail->famille_id == $d->famille_id) {
                    $groupByfamille[$detail->famille][] = $d;
                }
            }
        }


        $table = '<table class="table table-striped table-responsive table-bordered" border="1" cellspacing="0" cellpadding="3">' .
                '<tr>' .
                    '<th>Produit</th>' .
                    '<th>Designation</th>' .
                    '<th>Quantité</th>' .
                    '<th>Unité</th>' .
                '</tr>';

        foreach ($groupByfamille as $k => $item) {
            $j = 1;
            $itemLen = count($item);
            $table .= '<tr>';
            if ($itemLen > 1)
                $table .= '<td rowspan="' . $itemLen . '">' . $k . '</td>';
            else
                $table .= '<td>' . $k . '</td>';
            foreach ($item as $d) {
                $table .= '<td>' . $d->produit . '</td>' .
                    '<td>' . $d->quantite . '</td>' .
                    '<td>' . $d->unite . '</td>';
                if (count($item) > 1){
                    if ($j < count($item)) {
                        $table .= '</tr><tr>';
                    }else{
                        $table .= '</tr>';
                    }
                }
                $j ++;
            }
            $table .= '</tr>';
        }

        $table .= '</table>';

        $commande = $this->Offre->get_by($nameFields = 'off.offid', $value = intval($id));
        $this->layout->assign('commande', $commande);
        $this->layout->assign('groupByfamille', $groupByfamille);
        $this->layout->assign('table', $table);
        $this->layout->assign('pdf', $pdf);
        $this->layout->render('commande' . DS . 'impression' . DS . 'commande', TRUE);
    }


    /**
     * Construction de la table html pour l'affichage des commandes
     * @return string renvoi une table html
     */
    private function loadTable()
    {
        $commandes = $this->commandes;

        $this->layout->assign('commandes', $commandes);
        return $this->layout->ajax('commande' . DS . 'table');
    }

}