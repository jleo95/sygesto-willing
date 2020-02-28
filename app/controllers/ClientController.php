<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 02/11/2018
 * Time: 02:26
 */

namespace App\Controllers;


use App\Core\Controller;

class ClientController extends Controller
{

    public function __construct()
    {
        parent::__construct();

        $this->layout->setJS('client' . DS . 'index');
        $this->layout->setTitle('Clients');
        $this->layout->setTitle('Liste des clients', 'v');
    }

    public function index()
    {
        $this->layout->assign('clients', $this->Client->all([], 'cliid DESC'));
        $this->layout->assign('tableClient', $this->loadTable());
        $this->layout->render('client' . DS . 'index');
    }

    #ajouter un client
    public function add()
    {
        $data  = [];
        $error = [];

        if (isset($this->input->nomAddClient) AND !empty($this->input->nomAddClient)) {
            $data ['clinom'] = $this->input->nomAddClient;
        }else {
            $error ['nomAddClient'] = true;
        }

        if (isset($this->input->prenomAddClient) AND !empty($this->input->prenomAddClient)) {
            $data ['cliprenom'] = $this->input->prenomAddClient;
        }

        if (isset($this->input->telephone1AddClient) AND !empty($this->input->telephone1AddClient)) {
            $data ['clitelephone'] = $this->input->telephone1AddClient;
        }else {
            $error ['telephoneAddClient'] = true;
        }

        if (isset($this->input->telephone2AddClient) AND !empty($this->input->telephone2AddClient)) {
            $data ['cliportable'] = $this->input->telephone2AddClient;
        }

        if (isset($this->input->villeAddClient) AND !empty($this->input->villeAddClient)) {
            $data ['cliville'] = $this->input->villeAddClient;
        }else {
            $error ['villeAddClient'] = true;
        }


        if (empty($error)) {
            $data['clidatecreation'] = date('Y-m-d H:i:s', time());
            $data['clirealiserpar'] = $this->session->stkiduser;
            if ($this->Client->insert($data)) {
                $response ['error']            = 0;
                $response ['bodyTableClient'] = $this->loadTable();
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

    #charegement des donnee pour la suppression d'un client
    public function laodDataForDeleteClient()
    {
        $client = $this->Client->get_by('cliid', $this->input->idClient);
        $html = '<div>' .
            '<span>Voulez-vous supprimer le client: </span>' . ucfirst($client->clinom) . ' ' . ucfirst($client->cliprenom) . ' ?' .
            '</div>';

        echo json_encode([
            'modalBody' => $html,
            'nameClient' => ucfirst($client->clinom) . ' ' . ucfirst($client->cliprenom)
        ]);
    }

    #suppression d'un client
    public function delete()
    {
        if ($this->Client->delete_by('cliid', $this->input->idClient)) {
            echo json_encode([
                'succes' => 0,
                'tbodyClient' => $this->loadTable()
            ]);
        }else {
            echo json_encode([
                'succes' => 1
            ]);
        }
    }

    #chargement des donnee pour la modification
    public function laodForEditClient()
    {
        $client = $this->Client->get_by('cliid', $this->input->idClient);
        $this->layout->assign('client', $client);

        echo json_encode([
            'modalBody' => $this->layout->ajax('client' . DS . 'ajax' . DS . 'edit'),
            'nameClient' => ucfirst($client->clinom) . ' ' . ucfirst($client->cliprenom)
        ]);
    }

    #modifier un client
    public function edit()
    {
        $data        = [];
        $error       = [];
        $client = $this->Client->get_by('cliid', $this->input->hiddenIdClientEdit);

        if (isset($this->input->nomEditClient) AND !empty($this->input->nomEditClient)) {
            if ($this->input->nomEditClient !== $client->clinom) {
                $data ['clinom'] = $this->input->nomEditClient;
            }
        }else {
            $error ['nomEditClient'] = true;
        }

        if (isset($this->input->prenomEditClient) AND !empty($this->input->prenomEditClient)) {
            if ($this->input->prenomEditClient !== $client->cliprenom) {
                $data ['cliprenom'] = $this->input->prenomEditClient;
            }
        }

        if (isset($this->input->telephone1EditClient) AND !empty($this->input->telephone1EditClient)) {
            if ($this->input->telephone1EditClient !== $client->clitelephone) {
                $data ['clitelephone'] = $this->input->telephone1EditClient;
            }
        }else {
            $error ['telephoneEditClient'] = true;
        }

        if (isset($this->input->telephone2EditClient) AND !empty($this->input->telephone2EditClient)) {
            if ($this->input->telephone2EditClient !== $client->cliportable) {
                $data ['cliportable'] = $this->input->telephone2EditClient;
            }
        }

        if (isset($this->input->villeEditClient) AND !empty($this->input->villeEditClient)) {
            if ($this->input->villeEditClient !== $client->cliville) {
                $data ['cliville'] = $this->input->villeEditClient;
            }
        }else {
            $error ['villeEditClient'] = true;
        }

        $response ['nomClient']    = ucfirst($client->clinom) . ' ' . ucfirst($client->cliprenom);

        if (empty($error)) {
            if (!empty($data)) {
                if ($this->Client->update($data, ['cliid' => $this->input->hiddenIdClientEdit])) {
                    $client                    = $this->Client->get_by('cliid', $this->input->hiddenIdClientEdit);
                    $modalFooter                    = '<div class="modal-footer"><button type="button" data-dismiss="modal" class="btn btn-primary" onclick="confirmEdit(0)">Ok</button></div>';
                    $response ['error']             = 0;
                    $response ['modalFooter']       = $modalFooter;
                    $response ['tbodyTableClient'] = $this->loadTable();
                    $response ['nomClient']    = ucfirst($client->clinom) . ' ' . ucfirst($client->cliprenom);
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

    #chargement des element du tableau
    private function loadTable()
    {
        $i = 1;
        $html = '';
        $remise = '';
        $clients = $this->Client->all([], 'cliid DESC');

        foreach ($clients as $client) {
            if ($client->cliremise !== 0) {
                $remise = $client->cliremise;
            } else {
                $remise = $client->catremise;
            }
            $html .= '<tr>' .
                         '<td>' . $i ++ . '</td>' .
                         '<td>' . ucfirst($client->clinom) . ' ' . ucfirst($client->cliprenom) . '</td>' .
                         '<td>' . $client->clitelephone ;
            if ($client->cliportable != '') {
                $html .= ' / ' . $client->cliportable;
            }
            $html .= '</td>' .
                         '<td>' . $client->cliville . '</td>' .
                         '<td>' . $remise . '</td>' .
                         '<td>';
            if (in_array('803', $_SESSION['stkdroits'])) {
                $html .= '<a data-toggle="modal" href="#editeClient" onclick="laodForEditClient(' . $client->cliid . '); return false;" title="editer"><i class="fa fa-edit" style="color: #05AE0E;"></i></a>';
            }else {
                $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-edit"></i></a>';
            }

            if (in_array('804', $_SESSION['stkdroits'])) {
//                $html .= '<a data-toggle="modal" href="#deletClient" onclick="laodDataForDeleteClient(' . $client->cliid . '); return false;" title="supprimer"><i class="fa fa-remove" style="color: #FF3B30;"></i></a>';
            }else {
//                $html .= '<a href="javascript: void(0)" class="disabled" title="editer"><i class="fa fa-remove"></i></a>';
            }
            $html .= '</td></tr>';
        }

        return $html;
    }

}