<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 28/10/2018
 * Time: 02:08
 */

namespace App\Controllers;


use App\Core\Controller;
use App\Core\Router;

class ConnexionController extends Controller
{

    public function index()
    {
        if ($_POST) {
            if (isset($this->input->username) AND isset($this->input->pwd)) {
                if ($this->Connexion->authentification($this->input->username, $this->input->pwd)) {
                    #enregerister le temps d'expiration de la session
                    $_SESSION['stktimeout'] = time() + TIME_OUT;
                    $this->keepTrack();
                    if (isset($this->session->stkactiveurl) AND !empty($this->session->stkactiveurl)) {
//                        die('jai une url ne stock');
                        header("Location:" . $this->session->stkactiveurl);
                    }else {
//                        echo var_dump(BASEURL);
//                        die('jai pas une url en stock');
                        header("Location:" . BASEURL);
                    }
                }else {
                    $this->layout->assign('error', true);
                }
            }
        }
        $this->layout->render('connexion' . DS . 'connexion', true);
    }

    public function deconnexion()
    {
        if (!isset($this->session->sygesto)) {
            header('Location:' . Router::url('connexion'));
        }

        $datefin = null;
        $deconnexion = '';

        if ($this->session->stktimeout <= time()) { #on teste si la session a été expirée
            $datefin = date('Y-m-d H:i:s', $this->session->timeout);
            $deconnexion = 'Session expirée';
        }else { #si la session n'est pas expirée on ferme la session normalement
            $datefin = date('Y-m-d H:i:s', time());
            $deconnexion = 'Session fermée correctement';
            unset($_SESSION['stkactiveurl']);
            unset($_SESSION['vente']);
            unset($_SESSION['vente_detail']);
            unset($_SESSION['vente_detail_db']);
            unset($_SESSION['endProcessVente']);
            unset($_SESSION['ventes_print']);
        }
        $this->Connexion->updateConnexion($this->session->stkidconnexion, 'Connexion réussi', $datefin, $deconnexion);

        unset($_SESSION['stkiduser']);
        unset($_SESSION['stkusername']);
        unset($_SESSION['stkdroits']);
        unset($_SESSION['stkdroits']);
        unset($_SESSION['stkidconnexion']);
        unset($_SESSION['stktimeout']);
//        session_destroy();
        header('Location:' . Router::url('connexion'));

    }
    /**
     * garder les traces de la connexions
     */
    public function keepTrack()
    {
        $ipsource = $this->input->server('REMOTE_ADDR');
        $machine = gethostbyaddr($ipsource);
        $this->Connexion->save($_SESSION['stkiduser'], date("Y-m-d H:i:s", time()), $machine, $ipsource, 'Session en cours');
    }
}