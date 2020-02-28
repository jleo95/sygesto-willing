<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 01/11/2018
 * Time: 19:21
 */

namespace App\Controllers;


use App\Core\Controller;

class FournisseurController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $this->layout->assign('fournisseurs', $this->Fournisseur->all([], 'fouid DESC'));
        $this->layout->setTitle('fournisseur');
        $this->layout->setTitle('liste des fournisseurs', 'v');
        $this->layout->setJS('fournisseur' . DS . 'index');
        $this->layout->render('fournisseur' . DS . 'index');
    }

    #ajout d'un fournisseur
    public function add()
    {
        $data  = [];
        $error = [];

        if (isset($this->input->nomAddFournisseur) AND !empty($this->input->nomAddFournisseur)) {
            $data ['founom'] = $this->input->nomAddFournisseur;
        }else {
            $error ['nomAddFournisseur'] = true;
        }

        if (isset($this->input->prenomAddFournisseur) AND !empty($this->input->prenomAddFournisseur)) {
            $data ['fouprenom'] = $this->input->prenomAddFournisseur;
        }

        if (isset($this->input->telephone1AddFournisseur) AND !empty($this->input->telephone1AddFournisseur)) {
            $data ['foutelephone'] = $this->input->telephone1AddFournisseur;
        }else {
            $error ['telephoneAddFournisseur'] = true;
        }

        if (isset($this->input->telephone2AddFournisseur) AND !empty($this->input->telephone2AddFournisseur)) {
            $data ['fouportable'] = $this->input->telephone2AddFournisseur;
        }

        if (isset($this->input->villeAddFournisseur) AND !empty($this->input->villeAddFournisseur)) {
            $data ['fouville'] = $this->input->villeAddFournisseur;
        }else {
            $error ['villeAddFournisseur'] = true;
        }


        if (empty($error)) {
            $data['foudatecreation'] = date('Y-m-d H:i:s', time());
            $data['fourealiserpar'] = $this->session->stkiduser;
            if ($this->Fournisseur->insert($data)) {
                $response ['error']            = 0;
                $response ['bodyTableFournisseur'] = $this->loadTable();
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

    #chargement des donnee pour la suppression de fournisseur
    public function laodDataForDeleteFournisseur()
    {
        $fournisseur = $this->Fournisseur->get_by('fouid', $this->input->idFournisseur);
        $html = '<div>' .
            '<span>Voulez-vous supprimer le fournisseur: </span>' . ucfirst($fournisseur->founom) . ' ' . ucfirst($fournisseur->fouprenom) . ' ?' .
            '</div>';

        echo json_encode([
            'modalBody' => $html,
            'nameFournisseur' => ucfirst($fournisseur->founom) . ' ' . ucfirst($fournisseur->fouprenom)
        ]);
    }

    #suppression de fournisseur
    public function delete()
    {
        if ($this->Fournisseur->delete_by('fouid', $this->input->idFournisseur)) {
            echo json_encode([
                'succes' => 0,
                'tbodyFournisseur' => $this->loadTable()
            ]);
        }else {
            echo json_encode([
                'succes' => 1
            ]);
        }
    }

    #chargement des donnee pour la modification
    public function laodForEditFournisseur()
    {
        $fournisseur = $this->Fournisseur->get_by('fouid', $this->input->idFournisseur);
        $this->layout->assign('fournisseur', $fournisseur);
        echo json_encode([
            'modalBody' => $this->layout->ajax('fournisseur' . DS . 'ajax' . DS . 'edit'),
            'nameFournisseur' => ucfirst($fournisseur->founom) . ' ' . ucfirst($fournisseur->fouprenom)
        ]);
    }

    public function edit()
    {
        $data        = [];
        $error       = [];
        $fournisseur = $this->Fournisseur->get_by('fouid', $this->input->hiddenIdFournisseur);

        if (isset($this->input->nomEditFournisseur) AND !empty($this->input->nomEditFournisseur)) {
            if ($this->input->nomEditFournisseur !== $fournisseur->founom) {
                $data ['founom'] = $this->input->nomEditFournisseur;
            }
        }else {
            $error ['nomEditFournisseur'] = true;
        }

        if (isset($this->input->prenomEditFournisseur) AND !empty($this->input->prenomEditFournisseur)) {
            if ($this->input->prenomEditFournisseur !== $fournisseur->fouprenom) {
                $data ['fouprenom'] = $this->input->prenomEditFournisseur;
            }
        }

        if (isset($this->input->telephone1EditFournisseur) AND !empty($this->input->telephone1EditFournisseur)) {
            if ($this->input->telephone1EditFournisseur !== $fournisseur->foutelephone) {
                $data ['foutelephone'] = $this->input->telephone1EditFournisseur;
            }
        }else {
            $error ['telephoneEditFournisseur'] = true;
        }

        if (isset($this->input->telephone2EditFournisseur) AND !empty($this->input->telephone2EditFournisseur)) {
            if ($this->input->telephone2EditFournisseur !== $fournisseur->fouportable) {
                $data ['fouportable'] = $this->input->telephone2EditFournisseur;
            }
        }

        if (isset($this->input->villeEditFournisseur) AND !empty($this->input->villeEditFournisseur)) {
            if ($this->input->villeEditFournisseur !== $fournisseur->fouville) {
                $data ['fouville'] = $this->input->villeEditFournisseur;
            }
        }else {
            $error ['villeEditFournisseur'] = true;
        }

        $response ['nomFournisseur']    = ucfirst($fournisseur->founom) . ' ' . ucfirst($fournisseur->fouprenom);

        if (empty($error)) {
            if (!empty($data)) {
                if ($this->Fournisseur->update($data, ['fouid' => $this->input->hiddenIdFournisseur])) {
                    $fournisseur                    = $this->Fournisseur->get_by('fouid', $this->input->hiddenIdFournisseur);
                    $modalFooter                    = '<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-primary" onclick="confirmEdit(0)">Ok</button></div>';
                    $response ['error']             = 0;
                    $response ['modalFooter']       = $modalFooter;
                    $response ['tbodyTableFournisseur'] = $this->loadTable();
                    $response ['nomFournisseur']    = ucfirst($fournisseur->founom) . ' ' . ucfirst($fournisseur->fouprenom);
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

        echo json_encode($response);


    }

    #charement des liste des fournisseur
    private function loadTable()
    {
        $fournisseurs = $this->Fournisseur->all([], 'fouid DESC');
        $html         = '';
        $i            = 1;


        foreach ($fournisseurs as $fournisseur) {
            $html .= '<tr>' .
                          '<td>' . $i ++ . '</td>' .
                          '<td>' . ucfirst($fournisseur->founom) . ' ' . ucfirst($fournisseur->fouprenom) . '</td>' .
                          '<td>' . $fournisseur->foutelephone;
            if (!empty($fournisseur->fouportable)) {
                $html .= ' / ' . $fournisseur->fouportable;
            }
            $html .= '</td>' .
                '<td>' . $fournisseur->fouville . '</td>' .
                '<td>';

            if (in_array('703', $this->session->stkdroits)) {
                $html .= '<a data-toggle="modal" href="#editeFournisseur" onclick="laodForEditFournisseur(' . $fournisseur->fouid . '); return false;" title="editer"><i class="fa fa-edit" style="color: #05AE0E;"></i></a>';
            } else {
                $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-edit"></i></a>';
            }
            if (in_array('704', $this->session->stkdroits)) {
//                $html .= '<a data-toggle="modal" href="#deletFournisseur" onclick="laodDataForDeleteFournisseur(' . $fournisseur->fouid . '); return false;" title="supprimer"><i class="fa fa-remove" style="color: #FF3B30;"></i></a>';
            }else {
//                $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-remove"></i></a>';
            }
            $html .= '</td></tr>';
        }

        return $html;
    }

}