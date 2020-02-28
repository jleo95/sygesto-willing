<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 02/11/2018
 * Time: 13:22
 */

namespace App\Controllers;


use App\Core\Controller;

class EmployeController extends Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->layout->setTitle('Employés');
        $this->layout->setTitle('Liste des employés', 'v');
        $this->layout->setJS('employe' . DS . 'index');
    }

    public function index()
    {
        $this->layout->assign('employes', $this->Employe->all([], 'empid DESC'));
        $this->layout->render('employe' . DS . 'index');
    }

    #ajouter un employe
    public function add()
    {
        $data  = [];
        $error = [];

        if (isset($this->input->nomAddEmploye) AND !empty($this->input->nomAddEmploye)) {
            $data ['empnom'] = $this->input->nomAddEmploye;
        }else {
            $error ['nomAddEmploye'] = true;
        }

        if (isset($this->input->prenomAddEmploye) AND !empty($this->input->prenomAddEmploye)) {
            $data ['empprenom'] = $this->input->prenomAddEmploye;
        }

        if (isset($this->input->telephone1AddEmploye) AND !empty($this->input->telephone1AddEmploye)) {
            $data ['emptelephone'] = $this->input->telephone1AddEmploye;
        }else {
            $error ['telephoneAddEmploye'] = true;
        }

        if (isset($this->input->telephone2AddEmploye) AND !empty($this->input->telephone2AddEmploye)) {
            $data ['empportable'] = $this->input->telephone2AddEmploye;
        }

        if (isset($this->input->residenceAddEmploye) AND !empty($this->input->residenceAddEmploye)) {
            $data ['empresidence'] = $this->input->residenceAddEmploye;
        }else {
            $error ['residenceAddEmploye'] = true;
        }
        if (isset($this->input->sexeAddEmploye) AND !empty($this->input->sexeAddEmploye)) {
            $data ['empsexe'] = $this->input->sexeAddEmploye;
        }else {
            $error ['sexeAddEmploye'] = true;
        }


        if (empty($error)) {
            $data['empdatecreation'] = date('Y-m-d H:i:s', time());
            $data['emprealiserpar'] = $this->session->stkiduser;
            if ($this->Employe->insert($data)) {
                $response ['error']            = 0;
                $response ['bodyTableEmploye'] = $this->loadTable();
                $modalFooter                   = '<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-primary" onempck="confirmAdd(0)">Ok</button></div>';
                $response ['modalFooter']      = $modalFooter;
            }else {
                $response ['error'] = 2;
                $modalFooter = '<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-primary" onempck="confirmAdd(1)">Ok</button></div>';
                $response ['modalFooter'] = $modalFooter;
            }
        }else {
            $response ['mydata'] = $error;
            $response ['error'] = 1;
            $response ['mssge'] = '<div class="alert alert-danger alert-dismissable">' .
                '<button type="button" class="close" data-dismiss="alert">&times;</button>' .
                '<stron>Attention !</stron> Veillez verifier les infos entrés' .
                '</div>';
        }

        echo json_encode($response);
    }

    #charegement des donnee pour la suppression d'un employe
    public function laodDataForDeleteEmploye()
    {
        $employe = $this->Employe->get_by('empid', $this->input->idEmploye);
        $html = '<div>' .
            '<span>Voulez-vous supprimer l\'employé: </span>' . ucfirst($employe->empnom) . ' ' . ucfirst($employe->empprenom) . ' ?' .
            '</div>';

        echo json_encode([
            'modalBody' => $html,
            'nameEmploye' => ucfirst($employe->empnom) . ' ' . ucfirst($employe->empprenom)
        ]);
    }

    #suppression d'un employe
    public function delete()
    {
        if ($this->Employe->delete_by('empid', $this->input->idEmploye)) {
            echo json_encode([
                'succes' => 0,
                'tbodyEmploye' => $this->loadTable()
            ]);
        }else {
            echo json_encode([
                'succes' => 1
            ]);
        }
    }

    #chargement des donnee pour la modification
    public function laodForEditEmploye()
    {
        $employe = $this->Employe->get_by('empid', $this->input->idEmploye);
        $this->layout->assign('employe', $employe);

        echo json_encode([
            'modalBody' => $this->layout->ajax('employe' . DS . 'ajax' . DS . 'edit'),
            'nameEmploye' => ucfirst($employe->empnom) . ' ' . ucfirst($employe->empprenom)
        ]);
    }

    #modifier un employe
    public function edit()
    {
        $data        = [];
        $error       = [];
        $employe = $this->Employe->get_by('empid', $this->input->hiddenIdEmployeEdit);

        if (isset($this->input->nomEditEmploye) AND !empty($this->input->nomEditEmploye)) {
            if ($this->input->nomEditEmploye !== $employe->empnom) {
                $data ['empnom'] = $this->input->nomEditEmploye;
            }
        }else {
            $error ['nomEditEmploye'] = true;
        }

        if (isset($this->input->prenomEditEmploye) AND !empty($this->input->prenomEditEmploye)) {
            if ($this->input->prenomEditEmploye !== $employe->empprenom) {
                $data ['empprenom'] = $this->input->prenomEditEmploye;
            }
        }

        if (isset($this->input->telephone1EditEmploye) AND !empty($this->input->telephone1EditEmploye)) {
            if ($this->input->telephone1EditEmploye !== $employe->emptelephone) {
                $data ['emptelephone'] = $this->input->telephone1EditEmploye;
            }
        }else {
            $error ['telephoneEditEmploye'] = true;
        }

        if (isset($this->input->telephone2EditEmploye) AND !empty($this->input->telephone2EditEmploye)) {
            if ($this->input->telephone2EditEmploye !== $employe->empportable) {
                $data ['empportable'] = $this->input->telephone2EditEmploye;
            }
        }

        if (isset($this->input->residenceEditEmploye) AND !empty($this->input->residenceEditEmploye)) {
            if ($this->input->residenceEditEmploye !== $employe->empresidence) {
                $data ['empresidence'] = $this->input->residenceEditEmploye;
            }
        }else {
            $error ['residenceEditEmploye'] = true;
        }
        if (isset($this->input->sexeEditEmploye) AND !empty($this->input->sexeEditEmploye)) {
            if ($this->input->sexeEditEmploye != $employe->empsexe) {
                $data ['empsexe'] = $this->input->sexeEditEmploye;
            }
        }else {
            $error ['sexeEditEmploye'] = true;
        }

        $response ['nomEmploye']    = ucfirst($employe->empnom) . ' ' . ucfirst($employe->empprenom);

        if (empty($error)) {
            if (!empty($data)) {
                if ($this->Employe->update($data, ['empid' => $this->input->hiddenIdEmployeEdit])) {
                    $employe                    = $this->Employe->get_by('empid', $this->input->hiddenIdEmployeEdit);
                    $modalFooter                    = '<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-primary" onclick="confirmEdit(0)">Ok</button></div>';
                    $response ['error']             = 0;
                    $response ['modalFooter']       = $modalFooter;
                    $response ['tbodyTableEmploye'] = $this->loadTable();
                    $response ['nomEmploye']    = ucfirst($employe->empnom) . ' ' . ucfirst($employe->empprenom);
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

    #chargement des element de la table
    private function loadTable()
    {
        $employes = $this->Employe->all([], 'empid DESC');
        $i        = 1;
        $html     = '';

        foreach ($employes as $employe) {
            $html .= '<tr>' .
                        '<td>' . $i ++ . '</td>' .
                        '<td>' . ucfirst($employe->empnom) . ' ' . ucfirst($employe->empprenom) . '</td>' .
                        '<td>' . $employe->emptelephone ;
                if ($employe->empportable != '') {
                     $html .= ' / ' . $employe->empportable;
                }
                $html .= '</td>' .
                         '<td>';
                if (intval($employe->empsexe) == 1) {
                    $html .= 'Homme';
                }else {
                    $html .='Femme';
                }
                $html .= '</td>' .
                         '<td>' . ucfirst($employe->empresidence) . '</td>' .
                         '<td>';
                if (in_array('803', $this->session->stkdroits)) {
                    $html .= '<a data-toggle="modal" href="#editeEmploye" onclick="laodForEditEmploye(' . $employe->empid . '); return false" title="editer"><i class="fa fa-edit" style="color: #05AE0E;"></i></a>';
                }else {
                    $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-edit"></i></a>';
                }
                if (in_array('804', $this->session->stkdroits)) {
                    $html .= '<a data-toggle="modal" href="#deletEmploye" onclick="laodDataForDeleteEmploye(' . $employe->empid . '); return false;" title="supprimer"><i class="fa fa-remove" style="color: #FF3B30;"></i></a>';
                }else {
                    $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-remove"></i></a>';
                }
                $html .= '</td>'.
                '</tr>';
        }

        return $html;
    }

}