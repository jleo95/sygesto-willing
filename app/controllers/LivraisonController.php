<?php
/**
 * Created by PhpStorm.
 * User: antol
 * Date: 3/15/2020
 * Time: 12:24 AM
 */

namespace App\controllers;


use App\Core\Controller;
use App\Core\PDF;

class LivraisonController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->chargements = $this->Livraison->all([], $orderBy = 'livdate DESC');
    }

    public function index()
    {
        $chargements = '';
        $this->layout->assign('chargements', $this->table());
        $this->layout->setTitle('Liste de tous les chargements', 'v');
        $this->layout->setTitle('Chargement');
        $this->layout->setJS('livraison' . DS . 'index');
        $this->layout->render('livraison' . DS . 'index');
    }

    /**
     * Commencement de la procedure d'ajout d'une nouvelle livraison
     */
    public function ajout()
    {
        if (!isset($_SESSION['listProduitInLivraison']))
            $_SESSION['listProduitInLivraison'] = [];

        $this->layout->setTitle('Nouvelle livraison');
        $this->layout->setTitle('Nouvelle livraison', 'v');
        $this->layout->render('livraison' . DS . 'ajout');
    }

    /**
     * On selctionne les produits a livres
     */
    public function selectproduit()
    {
//        $detail = $this->Offredetail->show_offre_detail_by_offre_and_produit(1, 62);
//        var_dump($detail);
//        die();
//        $_SESSION['listProduitInLivraison'] = [];
//        var_dump($_SESSION['listProduitInLivraison'] );
//        die();
        $produits = $this->Offredetail->show_produit_byNotLivred();
        $tmp = $produits;
        $data = [];
        $it = 0;
        foreach ($produits as $produit) {
            foreach ($tmp as $p) {
                if ($produit->offre == $p->offre) {
                    $data[$produit->offre][] = $p;
                    unset($tmp[$it]);
                }
                $it ++;
            }
        }
        $this->layout->assign('produits', $data);
        $this->layout->setTitle('Selection produit');
        $this->layout->setTitle('Ajouter un produit dans liste de livraison', 'v');
        $this->layout->render('livraison' . DS . 'selectproduit');
    }

    public function addProduitToLivraison()
    {
        $response = 0;
        $offre = intval($this->input->offre);
        $idproduit = intval($this->input->idProduit);
        $detail = $this->Offredetail->show_offre_detail_by_offre_and_produit($offre, $idproduit);
        $quantite_livree = $this->input->quantite;
        $tmp = [];
        $chargement = [
            'produit_id' => $detail->produit_id,
            'produit' => $detail->produit,
            'offre' => $detail->offre,
            'offre_date' => $detail->offre_date,
            'offshore_id' => $detail->offshore_id,
            'offshore' => $detail->offshore,
            'offshore_fin' => $detail->offshore_datefin,
            'quantite' => $detail->quantite_restante,
            'unite_id' => $detail->unite_id,
            'unite' => $detail->unite
        ];

        if (empty($_SESSION['listProduitInLivraison']) && ($chargement['quantite'] - $quantite_livree) >= 0) {
            $chargement['quantite_restante'] = $chargement['quantite'] - $quantite_livree;
            $chargement['quantite_livree'] = $quantite_livree;
            $_SESSION['listProduitInLivraison'][] = $chargement;
            $response = 1;
        }else{
            $quantite_restante = 0;
            $trouver = false;
            $i = 0;
            while (!$trouver && $i < count($_SESSION['listProduitInLivraison'])) {
                $item = $_SESSION['listProduitInLivraison'][$i];
                if ($item['produit_id'] === $chargement['produit_id'] && $item['offre'] === $chargement['offre']) {
                    $quantite_livree += $item['quantite_livree'];
                    $quantite_restante = $item['quantite'] - $quantite_livree;
                    $trouver = true;
                }
                $i ++;
            }

            if ($trouver && $quantite_restante >= 0) {
                $chargement['quantite_restante'] = $quantite_restante;
                $chargement['quantite_livree'] = $quantite_livree;
                $_SESSION['listProduitInLivraison'][$i - 1] = $chargement;
                $response = 1;
            }elseif (!$trouver && ($chargement['quantite'] - $quantite_livree) >= 0){
                $chargement['quantite_restante'] = $chargement['quantite'] - $quantite_livree;
                $chargement['quantite_livree'] = $quantite_livree;
                $_SESSION['listProduitInLivraison'][] = $chargement;
                $response = 1;
            }
        }

        if ($response == 1)
            $_SESSION['successAddLivraison'] = true;

        echo json_encode([
            'success' => $response,
            'chargements' => $_SESSION['listProduitInLivraison']
        ]);
    }

    /**
     * On insert les element dans la base de donne (dans la table livraison)
     */
    public function addProduitToLivraisonEndProcess()
    {
        $data = [];
        foreach ($_SESSION['listProduitInLivraison'] as $item) {
            $data = [
                'livdate' => date('Y-m-d H:i:s', time()),
                'livproduit' => $item['produit_id'],
                'livquantite' => $item['quantite_livree'],
                'livrealiserpar' => $this->session->stkiduser,
                'livoffre' => $item['offre']
            ];
            $this->Livraison->insert($data);
        }
        unset($_SESSION['listProduitInLivraison']);
        echo json_encode(['success' => 1]);
    }

    /**
     * triner les livraison par date
     */
    public function trier()
    {
        $data = [];
        $idTrie = intval($this->input->idTrie);
        if ($idTrie === 1) {
            $dateDebut = getStartingDay();
            $dateFin = getLastDay();
        }elseif($idTrie === 2) {
            $dateDebut = getStartingDay();
            $date = new \DateTime($dateDebut);
            $date = $date->modify('+4 month -1 day')->format('Y-m-d');
            $dateFin= $date;
        }elseif($idTrie === 3) {
            $dateDebut = getStartingDay('01', date('Y', time()));
            $dateFin = date('Y', time()) . '-12' . '-31';
        }else {
            if (isset($this->input->dateLivraison)) {
                $date = $this->input->dateLivraison;
                $dateDebut = $date . ' 00:00';
                $dateFin = $date . ' 23:59';
            }else{
                $dateDebut = $this->input->dateDebut;
                $dateFin = $this->input->dateFin;
            }
        }
        $this->chargements = $this->Livraison->getByDate($dateDebut, $dateFin);
        echo json_encode([
            'data' => $this->table(),
            'date' => [
                'dateDebut' => $dateDebut,
                'dateFin' => $dateFin
            ],
            'idTrie' => $idTrie
        ]);
    }

    #imprimer les livraisons
    public function imprimer()
    {
        $data = [];
        $nonChoix = false;
        $idTrie = intval($this->input->idPrinter);
        if ($idTrie === 1) {
            $dateDebut = getStartingDay();
            $dateFin = getLastDay();
        }elseif($idTrie === 2) {
            $dateDebut = getStartingDay();
            $date = new \DateTime($dateDebut);
            $date = $date->modify('+4 month -1 day')->format('Y-m-d');
            $dateFin= $date;
        }elseif($idTrie === 3) {
            $dateDebut = getStartingDay('01', date('Y', time()));
            $dateFin = date('Y', time()) . '-12' . '-31';
        }elseif($idTrie == 4) {
            $nonChoix = true;
        }
        else{
            if (isset($this->input->dateImpression)) {
                $date = $this->input->dateImpression;
                $dateDebut = $date . ' 00:00';
                $dateFin = $date . ' 23:59';
            }else{
                $dateDebut = $this->input->dateDebutImpression;
                $dateFin = $this->input->dateFinImpression;
            }
        }
        if (!$nonChoix) {
            $this->chargements = $this->Livraison->getByDate($dateDebut, $dateFin);
        }

        $this->layout->assign('data', $this->table());
        $this->layout->assign('pdf', new PDF('L'));
        $this->layout->render('livraison' . DS . 'impression' . DS . 'livraisons', TRUE);
    }

    #construction de table
    private function table()
    {
        $chargements = $this->chargements;

        $this->layout->assign('chargements', $chargements);
        return $this->layout->ajax('livraison' . DS . 'table');
    }

}