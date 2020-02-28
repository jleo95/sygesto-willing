<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 31/10/2018
 * Time: 17:34
 */

namespace App\Controllers;


use App\Core\Controller;

class BackupController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }


    public function index()
    {
        $this->layout->assign('unites', $this->Unite->all([], 'uniid DESC'));
        $this->layout->setTitle('Backup');
        $this->layout->setTitle('Gestion des données', 'v');
        $this->layout->render('backup' . DS . 'index');
    }

}