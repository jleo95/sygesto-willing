<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 31/10/2018
 * Time: 21:02
 */

namespace App\Controllers;


use App\Core\Controller;

class MarqueController extends Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->layout->setJS('marque' . DS . 'index');
    }

    #page d'acueil
    public function index()
    {
        $this->layout->assign('marques', $this->Marque->all([], 'marid DESC'));
        $this->layout->setTitle('Marque des produits');
        $this->layout->setTitle('Marque des produits', 'v');
        $this->layout->render('marque' . DS . 'index');
    }

    #ajout d'une marque
    public function add()
    {
        $data  = [];
        $error = [];

        if (isset($this->input->designationAddMarque) AND !empty($this->input->designationAddMarque)) {
            $data ['marlibelle'] = $this->input->designationAddMarque;
        }else {
            $error ['libelle'] = true;
        }

        if (isset($this->input->descriptionAddMarque) AND !empty($this->input->descriptionAddMarque)) {
            $data ['mardescription'] = $this->input->descriptionAddMarque;
        }else {
            $error ['description'] = true;
        }

        if (empty($error)) {
            $data['mardatecreation'] = date('Y-m-d H:i:s', time());
            $data['marrealiserpar'] = $this->session->stkiduser;
            if ($this->Marque->insert($data)) {
                $response ['error']            = 0;
                $html                          = $this->loadTable();
                $response ['bodyTableMarque'] = $html;
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

    #chargement des donnee pour la modification
    public function laodForEditeMarque()
    {
        $marque = $this->Marque->get_by('marid', $this->input->idMarque);
       $this->layout->assign('marque', $marque);
       echo json_encode([
           'modalBody' => $this->layout->ajax('marque' . DS . 'ajax' . DS . 'edit'),
           'nameMarque' => $marque->marlibelle
       ]);
    }

    #modification d'une marque
    public function edit()
    {
        $marque = $this->Marque->get_by('marid', $this->input->idMarqueEdit);
        $data = [];
        if ($marque) {
            $data    = [];
            $error   = [];

            if (isset($this->input->designationEditMarque) AND !empty($this->input->designationEditMarque)) {
                if ($marque->marlibelle !== $this->input->designationEditMarque) {
                    $data ['marlibelle'] = $this->input->designationEditMarque;
                }
            }else {
                $error ['danger']['designation'] = true;
            }

            if (isset($this->input->descriptionEditMarque) AND !empty($this->input->descriptionEditMarque)) {
                if ($this->input->descriptionEditMarque !== $marque->uniabv) {
                    $data ['mardescription'] = $this->input->descriptionEditMarque;
                }
            }

            if (empty($error)) {

                if (!empty($data)) {
                    if ($this->Marque->update($data, ['marid' => $this->input->idMarqueEdit])) {
                        $modalFooter                    = '<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-primary" onclick="confirmEdit(0)">Ok</button></div>';
                        $response ['error']             = 0;
                        $response ['modalFooter']       = $modalFooter;
                        $response ['tbodyTableMarque'] = $this->loadTable();
                    }else {
                        $modalFooter = '<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-primary" onclick="confirmEdit(1)">Ok</button></div>';
                        $response ['error']       = 2;
                        $response ['modalFooter'] = $modalFooter;
                    }
                }else {
                    $modalFooter = '<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-primary" onclick="confirmEdit(1)">Ok</button></div>';
                    $response ['error'] = 2;
                    $response ['modalFooter'] = $modalFooter;
                }
            }else {
                $response ['error'] = 1;
                $response ['mssge'] = '<div class="alert alert-danger alert-dismissable">' .
                    '<button type="button" class="close" data-dismiss="alert">&times;</button>' .
                    '<stron>Attention !</stron> Veillez verifier les infos entrés' .
                    '</div>';
            }
        }else {
            $modalFooter = '<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-primary" onclick="confirmEdit(1)">Ok</button></div>';
            $response ['error'] = 0;
            $response ['modalFooter'] = $modalFooter;
        }

        $response['mydata'] = $this->input->idMarqueEdit;
        echo json_encode($response);
    }

    #chargement des donnee pour la suppression
    public function laodDataForDeleteMarque()
    {
        $marque = $this->Marque->get_by('marid', $this->input->idMarque);
        $html = '<div>' .
            '<span>Voulez-vous supprimer la marque: </span>' . ucfirst($marque->marlibelle) . ' ?' .
            '</div>';

        echo json_encode([
            'modalBody' => $html,
            'nameMarque' => ucfirst($marque->marlibelle)
        ]);
    }

    #suppression d'une marque
    public function delete()
    {
        if ($this->Marque->delete_by('marid', $this->input->idMarque)) {
            $html = $this->loadTable();
            echo json_encode([
                'succes' => 0,
                'tbodyMarque' => $html
            ]);
        }else {
            echo json_encode(['succes' => 1]);
        }
    }

    #chargement des element du table de la liste de marque
    private function loadTable()
    {
        $marques = $this->Marque->all([], 'marid DESC');
        $i      = 1;
        $html   = '';

        foreach ($marques as $marque) {
           $html .= '<tr>' .
                        '<td>' . $i ++ . '</td>' .
                        '<td>' .  $marque->marlibelle . '</td>' .
                        '<td>' . $marque->mardescription . '</td>' .
                        '<td>';
           if (in_array('113', $this->session->stkdroits)) {
               $html .= '<a data-toggle="modal" href="#editeMarque" onclick="laodForEditeMarque(' . $marque->marid . '); return false;" title="editer"><i class="fa fa-edit" style="color: #05AE0E;"></i></a>';
           }else {
               $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-edit"></i></a>';
           }
           if (in_array('112', $this->session->stkdroits)) {
                $html .= '<a data-toggle="modal" href="#deletMarque" onclick="laodDataForDeleteMarque(' . $marque->marid . '); return false;" title="supprimer"><i class="fa fa-remove" style="color: #FF3B30;"></i></a>';
           }else {
               $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-remove"></i></a>';
           }
            $html .= '</td></tr>';
        }

        return $html;
    }

}