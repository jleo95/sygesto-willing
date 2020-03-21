<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 14/08/2018
 * Time: 00:05
 */

namespace App\Core;


class Controller
{
    protected $layout;

    protected $input;

    protected $session;

    protected static $render;

    public function __construct()
    {
        date_default_timezone_set('Africa/Douala');
        $this->session = new Session();
        $this->layout = new Layout();
        $this->input = new Inputs();

        #verification du temps session
        $this->resetTimeOut();

        $uri = trim($_SERVER['REQUEST_URI'], '/');
        $uri = explode('/', $uri);
        if (!$this->isLogged() AND $uri[0] !== 'connexion') {
            header('Location:' . Router::url('connexion'));
        }

        #save url
        if ($uri[0] !== 'connexion') {
            $_SESSION['stkactiveurl'] = $_SERVER['REQUEST_URI'];
        }

        #load menus
        if ($this->isLogged()) {
            $this->layout->getMenu();
        }

        #load Model
        $this->loadModel('connexion');
        $this->loadModel('user');
        $this->loadModel('menu');
        $this->loadModel('groupemenu');
        $this->loadModel('role');
        $this->loadModel('profile');
        $this->loadModel('produit');
        $this->loadModel('offshore');
        $this->loadModel('fournisseur');
        $this->loadModel('unite');
        $this->loadModel('famille');
        $this->loadModel('mouvement');
        $this->loadModel('marque');
        $this->loadModel('client');
        $this->loadModel('employe');
        $this->loadModel('commande');
        $this->loadModel('offre');
        $this->loadModel('offredetail');
        $this->loadModel('commandedetail');
        $this->loadModel('paiement');
        $this->loadModel('livraison');

        if (isset($this->session->stkiduser)) {
            $droits = json_decode($this->User->get_by('useid', $this->session->stkiduser)->usedroits);
            Router::setDroits($droits);
            Router::setActiveUrl($this->Menu->get_by_droits($droits));
        }
//        $this->calledMethodesChild();
    }
    
    
    protected function calledMethodesChild() {
        $class_name = get_called_class();
//        $class_name = new $class_name();
        $methodes = get_class_methods($class_name);
        var_dump($methodes);
        die();
    }


    /**
     * verirfy if user is connected
     * @return bool true if user connected false else
     */
    protected function isLogged()
    {
        if (isset($_SESSION['stkusername']) AND !empty($_SESSION['stkusername'])) {
            return TRUE;
        }

        return FALSE;
    }

    private function loadModel($nameModel)
    {
        $nameModel = ucfirst($nameModel);
        $nameClasse = 'App\\Models\\' . $nameModel . 'Model';
        $this->$nameModel = new $nameClasse ();
    }

    private function getBreacumb($uri)
    {
        if (isset($uri[1]) AND !empty($uri[1])) {
            $this->layout->setBreacum($uri[0], $uri[1], BASEURL . $uri[0]);
        }else {
            if ($uri[0] === '') {
                $uri[0] = 'accueil';
            }
            $this->layout->setBreacum($uri[0]);
        }
    }

    private function resetTimeOut() {
        if ($this->isLogged()) {
            if ($this->session->stktimeout > time()) {
                //lui accorder 10 minute de plus
                $_SESSION['stktimeout'] = time() + TIME_OUT; //TIME_OUT Defini dans public/index.php
            } else {
                //il a deborder ces 10 minute de grave sans activite, le deconected
                //unset($_SESSION['user']);
                header("Location:" . Router::url("connexion", "deconnexion"));
            }
        }
    }

}