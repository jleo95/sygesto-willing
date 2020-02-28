<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 09/06/2018
 * Time: 13:57
 */

namespace App\Core;

class Router
{

    /**
     * @var String url capter sur la barre des url
     */
    private $url;

    /**
     * le controller a execute
     * @var String controller a executer
     */
    private $controller;

    /**
     * action a execute
     * @var String
     */
    private $action;

    /**
     * default controller
     * @var String
     */
    private $defaultController;

    /**
     * default action
     * @var String
     */
    private $defautlAction;

    /**
     * error Controller
     * @var String
     */
    private $errorController;

    /**
     * error Action
     * @var String
     */
    private $errorAction;

    private $error;
    
    /**
     *liste des droits par utilisateur
     * @var Array
     */
    private static $droits;
    
    /**
     *liste des urls
     * @var Array
     */
    private static $activeurls;
    
    /**
     *liste des fonctionnalitees
     * @var Array
     */
    private $model;

    public function __construct()
    {
        $this->url = $_SERVER['REQUEST_URI'];
//        $this->error = new Error();
        $this->defaultController = 'index';
        $this->defautlAction = 'index';
        $this->errorController = 'error';
        $this->errorAction = 'index';
        $this->model = new \App\Models\MenuModel();
    }
    
    public static function setDroits($droits) {
        static::$droits = $droits;
    }
    public static function setActiveUrl($urls) {
        $arr = [];
        foreach ($urls as $url) {
           $arr [] = ['url' => $url->menhref, 'droit' => $url->menrole]; 
        }
        
        static::$activeurls = $arr;
    }
    
    public static function addDroit($droit) {
        array_push(static::$droits, $droit);
    }
    
    private function addDroit2($droit) {
        array_push(static::$droits, $droit);
    }
    
    public static function addUrl($url) {
        array_push(static::$activeurls, $url);
    }
    
    private function addUrl2($url) {
        array_push(static::$activeurls, $url);
    }
    
    private function findUrl($url) {
        $trouver = FALSE;
        foreach (self::$activeurls as $v) {
            if ($v['url'] == $url AND in_array($v['droit'], self::$droits)) {
                $trouver = TRUE;
            }
        }
        
        return $trouver;
    }

    /**
     * @return String
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * @return String
     */
    public function getDefaultController()
    {
        return $this->defaultController;
    }

    /**
     * @return String
     */
    public function getDefautlAction()
    {
        return $this->defautlAction;
    }

    /**
     * @return String
     */
    public function getErrorController()
    {
        return $this->errorController;
    }

    /**
     * @return String
     */
    public function getErrorAction()
    {
        return $this->errorAction;
    }


    private function formatUrl($url, $script)
    {
        $url = explode('/', $url);
        $script = explode('/', $script);

        for ($i = 0; $i < count($script); $i ++){
            if (isset($url[$i])){
                if ($script[$i] == $url[$i]){
                    unset($url[$i]);
                }
            }

        }

        return array_values($url);

    }

    private function cleaEmptyValu($array)
    {
        foreach ($array as $k => $v) {

            if (empty($v)){
                unset($array[$k]);
            }

        }
        return array_values($array);
    }

    /**
     * run url parse
     */
    public function run()
    {
        $url = $this->url;
        $script = $_SERVER['SCRIPT_NAME'];
        $urlarr = $this->formatUrl($url, $script);
        $urlarr = $this->cleaEmptyValu($urlarr);

        $this->controller = (!empty($url) AND !empty($urlarr[0])) ? $urlarr[0] : $this->defaultController;
        $this->action = (!empty($url) AND !empty($urlarr[1])) ? $urlarr[1] : $this->defautlAction;
     

        $class = '\\App\\Controllers\\' . ucfirst($this->controller) . 'Controller';
        if (class_exists($class)){
            $controller = new  $class();
            if (!in_array($this->action, get_class_methods($controller))){
                $error = new Error();
                $log = new Log();
                $log->write('Error: not found methode' . "\r\n" . 'The methode ' . $this->action . ' was not found');
                $error->setArr(['message' => 'The methode ' . $this->action . ' was not found', 'heading' => 'Error: not found methode']);
                $error->show();
            }
            /*
            if ($this->controller === 'index') {
                $this->addDroit('001');
                $this->addUrl(['url' => '', 'droit' => '001']);
                $url = '';
            }
            if (!$this->findUrl($url)) {
                $error = new Error();
                $log = new Log();
                $log->write('Error: Accès interdite' . "\r\n" . 'Vous n\'avez pas accès à cette url');
                $error->setArr(['message' => 'Accès interdite, vous n\'avez pas accès à cette url. Veillez consulter l\'administrateur', 'heading' => 'Error: Accès']);
                $error->show();
            }*/
            
            $action = $this->action;
            $controller->$action();
            
        }else{
            $error = new Error();
            //$log = new Log();
            //$log->write('Error: Not Found' . "\r\n" . 'This classe ' . $class . ' was not found');
            $error->setArr(['message' => 'This classe ' . $class . ' was not found', 'heading' => 'Error: Not Found']);
            $error->show();
        }
    }


    /**
     * @param string $controller controller
     * @param string $action action methode to lunch page or view
     * @param null $params argument that url can take
     * @return string url contruct
     */
    public static function url($controller = 'index', $action = '', $params = NULL)
    {
        $url = BASEURL . $controller;

        if (!empty($action)) {
            $url .= DS . $action;
        }

        if (!is_null($params)) {
            if (is_array($params)) {
                foreach ($params as $param) {
                    $url .= DS . $param;
                }
            }else {
                $url .= DS . $params;
            }
        }

        return $url;
    }
}

/** end class Router */