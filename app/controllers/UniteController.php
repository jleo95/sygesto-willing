<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 31/10/2018
 * Time: 17:34
 */

namespace App\Controllers;


use App\Core\Controller;

class UniteController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        $this->layout->assign('unites', $this->Unite->all([], 'uniid DESC'));
        $this->layout->setTitle('Unité de mesure');
        $this->layout->setTitle('Unité de mesure des produits', 'v');
        $this->layout->setJS('unite' . DS . 'index');
        $this->layout->render('unite' . DS . 'index');
    }

    public function add()
    {
        $data  = [];
        $error = [];

        if (isset($this->input->designationAddUnite) AND !empty($this->input->designationAddUnite)) {
            $data ['unilibelle'] = $this->input->designationAddUnite;
        }else {
            $error ['designation'] = true;
        }

        if (isset($this->input->abvAddUnite) AND !empty($this->input->abvAddUnite)) {
            $data ['uniabv'] = $this->input->abvAddUnite;
        }else {
            $error ['abv'] = true;
        }

        if (empty($error)) {
            $data['unidatecreation'] = date('Y-m-d H:i:s', time());
            $data['unirealiserpar'] = $this->session->stkiduser;
            if ($this->Unite->insert($data)) {
                $uniteCheck = $this->Unite->get_by('uniid',  $this->Unite->lastInsert());
                $unite ['id']                  = $uniteCheck->uniid;
                $unite ['name']                = ucfirst($uniteCheck->unilibelle);
                $unite ['abv']                 = $uniteCheck->uniid;
                $response ['unite']            = $unite;
                $response ['error']            = 0;
                $html                          = $this->loadTable();
                $response ['bodyTableUnite']   = $html;
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

    public function laodDataForDeleteUnite()
    {
        $unite = $this->Unite->get_by('uniid', $this->input->idUnite);
        $html = '<div>' .
            '<span>Voulez-vous supprimer l\'unité de mesure: </span>' . ucfirst($unite->unilibelle) . ' ?' .
            '</div>';

        echo json_encode([
            'modalBody' => $html,
            'nameUnite' => ucfirst($unite->unilibelle)
        ]);
    }

    public function delete()
    {
        if ($this->Unite->delete_by('uniid', $this->input->idUnite)) {
            $html = $this->loadTable();
            echo json_encode(['succes' => 0, 'tbodyUnite' => $html]);
        }else {
            echo json_encode(['succes' => 1]);
        }
    }

    #chargement des donnee pour l'edition
    public function laodForEditUnite()
    {
        $this->layout->assign('unite', $this->Unite->get_by('uniid', $this->input->idUnite));
        $contentAjax = $this->layout->ajax('unite' . DS . 'ajax' . DS . 'edit');
        echo json_encode(['modalBody' => $contentAjax]);
    }

    #editer une unite de mesure
    public function edit()
    {
        $unite = $this->Unite->get_by('uniid', $this->input->idUniteEdit);
        if ($unite) {
            $data    = [];
            $error   = [];

            if (isset($this->input->designationEditUnite) AND !empty($this->input->designationEditUnite)) {
                if ($unite->unilibelle !== $this->input->designationEditUnite) {
                    $data ['unilibelle'] = $this->input->designationEditUnite;
                }
            }else {
                $error ['danger']['designation'] = true;
            }

            if (isset($this->input->abvEditUnite) AND !empty($this->input->abvEditUnite)) {
                if ($this->input->abvEditUnite !== $unite->uniabv) {
                    $data ['uniabv'] = $this->input->abvEditUnite;
                }
            }else {
                $error ['danger']['prixUnitAchat'] = true;
            }

            $response ['mydata'] = $data;
            if (empty($error)) {

                if (!empty($data)) {
                    if ($this->Unite->update($data, ['uniid' => $this->input->idUniteEdit])) {
                        $modalFooter                    = '<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-primary" onclick="confirmEdit(0)">Ok</button></div>';
                        $response ['error']             = 0;
                        $response ['modalFooter']       = $modalFooter;
                        $response ['tbodyTableUnite'] = $this->loadTable();
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

        echo json_encode($response);
    }

    #chargement de la liste de unite de mesure
    private function loadTable()
    {
        $unites = $this->Unite->all([], 'uniid DESC');
        $i      = 1;
        $html   = '';

        foreach ($unites as $unite) {
            $html .= '<tr>' .
                        '<td>' . $i ++ . '</td>' .
                        '<td>' . $unite->unilibelle . '</td>' .
                        '<td>' . $unite->uniabv . '</td>' .
                        '<td>';
            if (in_array('113', $this->session->stkdroits)) {
                $html .= '<a data-toggle="modal" href="#editeUnite" onclick="laodForEditUnite(' . $unite->uniid . '); return false" title="editer"><i class="fa fa-edit" style="color: #05AE0E;"></i></a>';
            } else {
                $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-edit"></i></a>';
            }
            if (in_array('112', $this->session->stkdroits)) {
                $html .= '<a data-toggle="modal" href="#deletUnite" onclick="laodDataForDeleteUnite(' . $unite->uniid . '); return false;" title="supprimer"><i class="fa fa-remove" style="color: #FF3B30;"></i></a>';
            }else {
                $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-remove"></i></a>';
            }
            $html .= '</td>' .
                    '</tr>';
        }

        return $html;
    }

}