<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 14/08/2018
 * Time: 00:25
 */

namespace App\Core;


use App\Models\MenuModel;
use App\Models\MenusModel;
use App\Models\ProfileModel;

class Layout
{

    /**
     * default layout
     * @var string
     */
    private $template = 'template';

    /**
     * view to render
     * @var string
     */
    private $view;

    /**
     * @var array data to inject for view
     */
    private $data = [];

    /**
     * path header page
     * @var string
     */
    private $header;

    private $footer;

    private $path;
    
    private $scriptes_js;
    
    private $styles_css;


    public function __construct()
    {
        $this->header = 'header';
        $this->footer = 'footer';
        $this->path = 'views';
        $this->data ['style']       = '';
        $this->data ['js']          = '';
        $this->data ['title_view']  = 'accueil';
        $this->data ['title_page']  = 'accueil';
        $this->data ['base_href']   = '<base <base href="' . BASEURL . '">';
        $this->data ['menus']       = '';
        $this->data ['breacumb']    = '<span class="parent">ACCUEIL</span>';
        $this->data ['iconeTitle']    = '<i class="fa fa-home"></i>';
        
        $this->styles_css ['main-style']['path'] = 'assets' . DS . 'css' . DS . 'styles';
        $this->styles_css ['view-style']['path'] = 'assets' . DS . 'css' . DS . 'style';
        $this->scriptes_js ['scripts_js']['path'] = 'assets' . DS . 'js' . DS . 'scripts';

        //$this->getHeader();
        //$this->getFooter();
    }

    /**
     * render views
     * @param string $view
     * @param bool $directoutput
     * @param array $vars if variable existe for views
     */
    public function render($view, $directoutput = FALSE, $vars = [])
    {
        $views = APP . DS . $this->path . DS . $view . '.php';

        if (!file_exists($views)) {
            $error = new Error();
            $error->setArr(['message' => 'Le fichier ' . $views . ' ne contient pas cette vue', 'heading' => 'Error : File not found']);
            $error->show();
        }

        extract($this->data);
        extract($vars);

        if (!$directoutput) {
            ob_start();
        }
        require $views;

        if (!$directoutput) {
            $content_for_views = ob_get_clean();
            require_once APP . DS . $this->path . DS . $this->template . '.php';
        }
    }

    /**
     * ajax methode
     * @param array $vars
     * @return string
     */
    public function ajax($views, $vars = [])
    {
        $views = APP . DS . 'views' . DS . $views . '.php';

        if (!file_exists($views)) {
            $error = new Error();
            $error->setArr(['message' => 'Le fichier ' . $views . ' ne contient pas cette vue']);
            $error->show();
        }

        extract($this->data);
        ob_start();
        require $views;
        $content_for_views = ob_get_clean();

        return $content_for_views;
    }

    /**
     * add style in header page
     * @param type $title title style
     * @param type $path path style
     */
    public function addStyles($title, $path) {
        $this->styles_css [$title] = $path;
    }
    
    public function writeExterneFile() {
        foreach ($this->styles_css as $k) {
            echo '<link rel="stylesheet" href="' . $k['path'] . '">';
        }
    }
    
    /**
     * @param $value
     * @param bool $type
     */
    public function setTitle($value, $type = FALSE)
    {
        if (!$type) {
            $this->data['title_page'] = $value;
        }else {
            $this->data['title_view'] = $value;
        }
    }

    /**
     * set template
     * @param string $template
     */
    public function setTemplate($template)
    {
        $this->template = $template;
    }

    /**
     * set view path
     * @param string $view
     */
    public function setView($view)
    {
        $this->view = $view;
    }

    /**
     * add arg to views
     * @param $key
     * @param $value
     */
    public function assign($key, $value)
    {
        $this->data[$key] = $value;
    }

    /**
     * @param string $header
     */
    public function setHeader($header)
    {
        $this->header = $header;
    }

    public function setBreacum($parent, $child = '', $link = 'http://syges.school.local')
    {
        if (!empty($child)) {
            $this->data ['breacumb'] = '<span class="parent"><a href="' . $link . '" title="page d\'accueil ' . $parent . '">' . strtoupper($parent) . '</a></span>' .
                '<span class="sep"><i class="fa fa-angle-right"></i></span>' .
                '<span class="child">' . strtoupper($child) . '</span>';
        }else {
            $this->data ['breacumb'] = '<span class="parent">' . strtoupper($parent) . '</span>';
        }
    }
    
    /**
     * set icone of title view
     * @param type $icone 
     */
    public function setIconeTitle($icone) {
        $this->data ['iconeTitle']    = $icone;
    }

    /**
     * get header page
     */
    private function getHeader()
    {
        extract($this->data);
        ob_start();
        require_once APP. DS . 'views' . DS . $this->header . '.php';
        $this->data ['header'] = ob_get_clean();
    }

    /**
     * get footer page
     */
    private function getFooter()
    {
        extract($this->data);
        ob_start();
        require_once APP. DS . $this->path . DS . $this->footer . '.php';
        $this->data ['footer'] = ob_get_clean();
    }

    /**
     * get styles views
     * @param $filename file content the styles
     */
    public function setStyle($filename)
    {
        if (file_exists(APP . DS . $this->path. DS . $filename . '.css')){
            $style = file_get_contents(APP . DS . $this->path . DS . $filename . '.css');
            $file = ROOT  . DS . 'assets' . DS . 'css' . DS . 'style.css';

            if (empty($this->data['style'])){
                fopen($file, 'w');
            }

            if (file_put_contents($file, $style, FILE_APPEND)){
                $this->data['style'] .= "<link href = 'assets" . DS . "css" . DS . "style.css' rel = 'stylesheet' type = 'text/css' />";
            }

        }else{
            $error = new Error();
            $error->setArr([
                'heading' => 'File Not Found',
                'message' => 'Le fichier qui contient la feuille de style ' . APP . DS . $this->path. DS . $filename . '.css' ,
                'type' => 404
            ]);
            $error->show();
        }
    }

    public function setStyle2($filename)
    {

        if (file_exists(APP . DS . $this->path. DS . $filename . '.css')){

            $style = APP . DS . $this->path . DS . $filename . '.css';
            $this->data['style'] .= "<link href = ' . $style . ' rel = 'stylesheet' type = 'text/css' />";

        }else{
            $error = new Error();
            $error->setArr([
                'heading' => 'File Not Found',
                'message' => 'Le fichier qui contient la feuille de style ' . APP . DS . $this->path. DS . $filename . '.css' ,
                'type' => 404
            ]);
            $error->show();
        }
    }

    /**
     * get script
     * @param $filename file content the script
     */
    public function setJS($filename)
    {
        if (file_exists(APP . DS . $this->path . DS . $filename . '.js')){
            $script = file_get_contents(APP . DS . $this->path . DS . $filename . '.js');
            $file = ROOT . DS . 'assets' . DS . 'js' . DS . 'clients.js';

            if (empty($this->data['js'])){
                fopen($file, 'w');
            }

            if (file_put_contents($file, $script, FILE_APPEND)){
                $client = '<script src="assets' . DS . 'js' . DS . 'clients.js"></script>';
                $this->data['js'] .= $client;
            }

        }else{
            $error = new Error();
            $error->setArr([
                'heading' => 'File Not Found',
                'message' => 'Le fichier qui le script ' . APP . DS . $this->path. DS . $filename . '.js n\'existe pas' ,
                'type' => 404
            ]);
            $log = new Log();
            $log->write('File Not Found' . "\r\n" . 'Le fichier qui le script ' . APP . DS . $this->path. DS . $filename . '.js n\'existe pas');
            $error->show();
        }
    }

    /**
     * Génère le menu
     */
    public function getMenu()
    {
        $models = new Model();
        $models->setTable('groupemenu');
        $modules = $models->all([], 'grporder ASC');

        $menu = '<ul class="menu">';
        $menuModel = new MenuModel();

        $activeurl = $_SERVER['REQUEST_URI'];
        $activeurl = trim($activeurl, '/');
        
        $activeClass = '';
        if ($activeurl == '') {
            $activeClass = ' active';
        }

        $menu .= '<li class="menu-item1' . $activeClass . '"><a href="' . BASEURL . '" >Acueil</a>';
//        var_dump($modules);
        foreach ($modules as $module) {
            $menus = $menuModel->get_by_module_droits($module->grpid, $_SESSION['stkdroits']);
            $activeClass = '';

            foreach ($menus as $v) {
                if ($v->menhref == $activeurl) {
                    $activeClass = ' active';
                }
            }
            if (count($menus) > 0) {
                $menu .= '<li class="menu-item' . $activeClass . '"><a href="#" >' . $module->grplibelle . '</a>';
                $menu .= '<ul class="submenu">';
                foreach ($menus as $m) {
                    $menu .= '<li><a href="' . $m->menhref . '"';
                    if ($activeurl == $m->menhref) {
                        $menu .= ' class="menuBlank"';
                    }
                    $menu .= '>' . $m->menlibelle . '</a></li>';
                }
                $menu .= '</ul>';
                $menu .= '</li>';
            }

        }
        $menu .= '</ul>';

        $this->data ['menus'] = $menu;
    }

}