<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 14/11/2018
 * Time: 14:36
 */

namespace App\Controllers;


use App\Core\Controller;
use App\Core\Router;

class UserController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->users = $this->User->all([], 'useid DESC');
    }

    public function index()
    {
        $this->layout->assign('users', $this->User->all([], 'useid DESC'));
        $this->layout->assign('employes', $this->Employe->all([], 'empnom'));
        $this->layout->assign('profiles', $this->Profile->all());
        $this->layout->setTitle('Utilisateur');
        $this->layout->setTitle('Utilisateur', 'v');
        $this->layout->setStyle('user' . DS . 'user');
        $this->layout->setJS('user' . DS . 'index');
        $this->layout->render('user' . DS . 'index');
    }

    public function add()
    {
        $data = [];
        $error = [];
        if ($_POST) {
            $data = [];

            if (isset($this->input->nomAddUser) AND !empty($this->input->nomAddUser)) {
                $data ['uselogin'] = $this->input->nomAddUser;
            }else {
                $error ['login'] = true;
            }

            if (isset($this->input->profileAddUser) AND !empty($this->input->profileAddUser)) {
                $data ['useprofile'] = $this->input->profileAddUser;
            }else {
                $error ['profile'] = true;
            }

            if (isset($this->input->employerAddUser) AND !empty($this->input->employerAddUser)) {
                $data ['useemploye'] = $this->input->employerAddUser;
            }else {
                $error ['employe'] = true;
            }

            if (isset($this->input->mdpAddUser) AND !empty($this->input->mdpAddUser AND $this->input->mdp2AddUser) AND !empty($this->input->mdp2AddUser)) {
                if ($this->input->mdp2AddUser == $this->input->mdpAddUser) {
                    $data ['usemdp'] = cryptPwd($this->input->mdpAddUser);
                }
            }else {
                $error ['mdp'] = true;
            }

            if (empty($error)) {
                $data ['usedatecreation'] = date('Y-m-d H:i:s', time());
                $data ['userealiserpar'] = $this->session->stkiduser;
                $data ['usedroits'] = $this->Profile->get_by('prfid', $data['useprofile'])->prfdroitacces;
                if ($this->User->insert($data)) {
                    $response ['error']            = 0;
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
        }else {
            $response ['error'] = 1;
            $response ['mssge'] = '<div class="alert alert-warning alert-dismissable">' .
                '<button type="button" class="close" data-dismiss="alert">&times;</button>' .
                '<stron>Attention !</stron> Aucune donnée postée' .
                '</div>';
        }

        echo json_encode($response);
    }

    public function laodDataForDeleteUser()
    {
        $user = $this->User->get_by('useid', $this->input->idUser);
        $html = '<div>' .
            '<span>Voulez-vous verouiller l\'utilisateur: </span>' . $user->uselogin . '?' .
            '</div>';

        echo json_encode([
            'modalBody' => $html,
            'nameUser' => $user->uselogin
        ]);
    }

    public function delete()
    {
        if ($this->User->update(['useverouiller' => 1], ['useid' => $this->input->idUser])) {
            $this->users = $this->User->all('', 'useid DESC');
            echo json_encode([
                'succes' => 0,
                'tbodyUser' => $this->autoLoad()
            ]);
        }else {
            echo json_encode([
                'succes' => 1
            ]);
        }
    }

    public function loadForContent()
    {
        $this->layout->assign('users', $this->User->all([], 'useid DESC'));
        $this->layout->assign('employes', $this->Employe->all([], 'empnom'));
        $this->layout->assign('profiles', $this->Profile->all());
        echo $this->layout->ajax('user' . DS . 'ajax' . DS . 'add');
    }

    public function loadForEditUser()
    {
        $this->layout->assign('employes', $this->Employe->all([], 'empnom'));
        $this->layout->assign('profiles', $this->Profile->all());
        $this->layout->assign('user', $this->User->get_by('useid', $this->input->idUser));
        echo $this->layout->ajax('user' . DS . 'ajax' . DS . 'edit');
    }

    public function equalMdp()
    {
        $user = $this->User->get_by('useid', $this->input->idUser);
        $pwd = $this->input->pwd;

        if (equalPwd($pwd, $user->usemdp)) {
            $response ['error'] = 0;
        }else {
            $response ['error'] = 1;
        }

        echo json_encode($response);
    }

    public function edit()
    {
        $data = [];
        $error = [];
        $user  = $this->User->get_by('useid', $this->input->hiddenIdUserEdit);

        if (isset($this->input->nomEditUser) AND !empty($this->input->nomEditUser)) {
            if ($user->uselogin !== $this->input->nomEditUser) {
                $data ['uselogin'] = $this->input->nomEditUser;
            }

        }else {
            $error ['login'] = true;
        }

        if (isset($this->input->profileEditUser) AND !empty($this->input->profileEditUser)) {
            if ($user->useprofile !== $this->input->profileEditUser) {
                $data ['useprofile'] = $this->input->profileEditUser;
            }
        }else {
            $error ['profile'] = true;
        }

        if (isset($this->input->employerEditUser) AND !empty($this->input->employerEditUser)) {
            if ($user->useemploye !== $this->input->employerEditUser) {
                $data ['useemploye'] = $this->input->employerEditUser;
            }
        }else {
            $error ['employe'] = true;
        }


        if (isset($this->input->mdpEditUser) AND !empty($this->input->mdpEditUser) AND isset($this->input->mdp2EditUser) AND !empty($this->input->mdp2EditUser)) {
            if ($this->input->mdpEditUser == $this->input->mdp2EditUser) {
                $data ['usemdp'] = cryptPwd($this->input->mdpEditUser);
            }else {
                $error ['mdp'] = true;
            }
        }else {
            $error ['mdp'] = true;
        }

        if (empty($error)) {

            if (!empty($data)) {
                if ($this->User->update($data, ['useid' => $this->input->hiddenIdUserEdit])) {
                    $user                           = $this->User->get_by('useid', $this->input->hiddenIdUserEdit);
                    $modalFooter                    = '<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-primary" onclick="confirmEdit(0)">Ok</button></div>';
                    $response ['error']             = 0;
                    $response ['modalFooter']       = $modalFooter;
                    $response ['tbodyTableEmploye'] = $this->loadTable();
                    $response ['nomUser']           = $user->uselogin;
                    $_SESSION['stkedit'] = true;
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
                '<stron>Attention !</stron> Veillez verifier les infos entrées' .
                '</div>';
        }

        echo json_encode($response);

    }

    private function loadTable()
    {
        $users = $this->users;
        $html  = '';

        foreach ($users as $user) {
            $html = '<tr>' .
                        '<td>' . $user->useid . '</td>' .
                        '<td>' . $user->uselogin . '</td>' .
                        '<td>' . $user->prflibelle . '</td>' .
                        '<td>';
                    if (in_array('611', $_SESSION['stkdroits'])) :
                        $html .= '<a data-toggle="modal" href="#editeUser" onclick="laodForEditUser(' . $user->useid . '); return false" title="editer"><i class="fa fa-edit" style="color: #05AE0E;"></i></a>';
                    else:
                        $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-edit"></i></a>';
                    endif;
                    if (in_array('610', $_SESSION['stkdroits'])) :
                        $html .= '<a data-toggle="modal" href="#deletUser" onclick="laodDataForDeleteUser(' . $user->useid . '); return false;" title="supprimer"><i class="fa fa-remove" style="color: #FF3B30;"></i></a>';
                    else:
                        $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-remove"></i></a>';
                    endif;
                    $html .= '</td>' .
                        '</tr>';
        }

        return $html;
    }

    private function autoLoad()
    {
        $tmps = $this->users;
        $users  = '';

        foreach ($tmps as $user) {
            if ($user->useverouiller == 1) {
                $state = '<i class="fa fa-lock text-danger"></i>';
            }else {
                $state = '<i class="fa fa-key text-success"></i>';
            }
            $users [] = [
                    'id' => $user->useid,
                    'name' => $user->uselogin,
                    'profile' => $user->prflibelle,
                    'state' => $state
                ];
        }

        return $users;
    }

    public function connexion()
    {
        $connexions = $this->Connexion->all([], 'id DESC');
        $this->layout->assign('connexions', $connexions);

        $this->layout->setTitle('Mes connexions');
        $this->layout->setTitle('Mes connexions', 'p');
        $this->layout->setJS('user' . DS . 'index');
        $this->layout->render('user' . DS . 'connexion');
    }

}