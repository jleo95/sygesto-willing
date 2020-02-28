<?php
/**
 * Created by PhpStorm.
 * User: LEOBA / Willing
 * Date: 15/11/2018
 * Time: 13:16
 */

namespace App\Controllers;


use App\Core\Controller;

class FamilleController extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        $this->layout->assign('familles', $this->Famille->all([], 'famid DESC'));
        $this->layout->setTitle('Famille');
        $this->layout->setTitle('Famille des produits', 'v');
        $this->layout->setJS('famille' . DS . 'index');
        $this->layout->render('famille' . DS . 'index');
    }

    public function add()
    {
        $data  = [];
        $error = [];

        if (isset($this->input->designationAddFamille) AND !empty($this->input->designationAddFamille)) {
            $data ['famlibelle'] = $this->input->designationAddFamille;
        }else {
            $error ['designation'] = true;
        }

        if (empty($error)) {
            $data['famdatecreation'] = date('Y-m-d H:i:s', time());
            $data['famrealiserpar'] = $this->session->stkiduser;
            if ($this->Famille->insert($data)) {
                $familleCheck                  = $this->Famille->get_by('famid', $this->Famille->lastInsert());
                $famille['id']                 = $familleCheck->famid;
                $famille['name']               = $familleCheck->famlibelle;
                $response ['famille']          = $famille;
                $response ['error']            = 0;
                $html                          = $this->loadTable();
                $response ['bodyTableFamille'] = $html;
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

    public function loadDataForDeleteFamille()
    {
        $famille = $this->Famille->get_by('famid', $this->input->idFamille);
        $html = '<div>' .
            '<span>Voulez-vous supprimer la famille des produits: </span>' . ucfirst($famille->famlibelle) . ' ?' .
            '</div>';

        echo json_encode([
            'modalBody' => $html,
            'nameFamille' => ucfirst($famille->famlibelle)
        ]);
    }

    public function delete()
    {
        if ($this->Famille->delete_by('famid', $this->input->idFamille)) {
            $html = $this->loadTable();
            echo json_encode(['succes' => 0, 'tbodyFamille' => $html]);
        }else {
            echo json_encode(['succes' => 1]);
        }
    }

    public function loadForEditFamille()
    {
        $this->layout->assign('famille', $this->Famille->get_by('famid', $this->input->idFamille));
        $contentAjax = $this->layout->ajax('famille' . DS . 'ajax' . DS . 'edit');
        echo json_encode(['modalBody' => $contentAjax]);
    }

    #editer une famille
    public function edit()
    {
        $famille = $this->Famille->get_by('famid', $this->input->idFamilleEdit);
        if ($famille) {
            $data    = [];
            $error   = [];

            if (isset($this->input->designationEditFamille) AND !empty($this->input->designationEditFamille)) {
                if ($famille->famlibelle !== $this->input->designationEditFamille) {
                    $data ['famlibelle'] = $this->input->designationEditFamille;
                }
            }else {
                $error ['danger']['designation'] = true;
            }

            $response ['mydata'] = $data;
            if (empty($error)) {

                if (!empty($data)) {
                    if ($this->Famille->update($data, ['famid' => $this->input->idFamilleEdit])) {
                        $modalFooter                    = '<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-primary" onclick="confirmEdit(0)">Ok</button></div>';
                        $response ['error']             = 0;
                        $response ['modalFooter']       = $modalFooter;
                        $response ['tbodyTableFamille'] = $this->loadTable();
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

    private function loadTable()
    {
        $familles = $this->Famille->all([], 'famid DESC');
        $i      = 1;
        $html   = '';

        foreach ($familles as $famille) {
            $html .= '<tr>' .
                '<td>' . $famille->famid . '</td>' .
                '<td>' . $famille->famlibelle . '</td>' .
                '<td>';
            if (in_array('113', $this->session->stkdroits)) {
                $html .= '<a data-toggle="modal" href="#editeFamille" onclick="laodForEditFamille(' . $famille->famid . '); return false" title="editer"><i class="fa fa-edit" style="color: #05AE0E;"></i></a>';
            } else {
                $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-edit"></i></a>';
            }
            if (in_array('112', $this->session->stkdroits)) {
                $html .= '<a data-toggle="modal" href="#deletFamille" onclick="laodDataForDeleteFamille(' . $famille->famid . '); return false;" title="supprimer"><i class="fa fa-remove" style="color: #FF3B30;"></i></a>';
            }else {
                $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-remove"></i></a>';
            }
            $html .= '</td>' .
                '</tr>';
        }

        return $html;
    }



}