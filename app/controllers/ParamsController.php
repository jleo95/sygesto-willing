<?php

namespace App\Controllers;

use App\Core\Controller;

class ParamsController extends Controller {
    
    
    public function __construct() {
        parent::__construct();
    }
    
    public function index() {
        $this->layout->setTitle('Paramètre', 'p');
        $this->layout->setTitle('Paramètre');
        $this->layout->render('params' . DS . 'index');
    }
    
}

