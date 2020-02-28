<?php

/**
 * Description of IndexController
 *
 * @author LEOBA
 */

namespace App\Controllers;

use \App\Core\Controller;

class IndexController extends Controller {
    
    public function index() {

        $produits = $this->Produit->all();

        foreach ($produits as $produit) {
            $produit = $this->Produit->get_by('proid', $produit->proid);
            
        }
//        dd($stocks);
//
//        die();

        $path = ROOT . DS . 'app' . DS . 'backups';
        $log = $path . DS . date("Y-m-d_\a_H\hi", time()) . '.sql';

        if (file_exists($log)) {
            unlink($log) or die("Impossible de supprimer l'ancien fichier sauvegarde");
        }

        if (empty(DBPASSWORD)) {
//            $backupex = 'mysqldump -u ' . DBUSER . ' -h ' . DBHOST . ' ' . DBNAME . ' > ' . $log;
           $backupex = 'mysqldump --host=' . DBHOST . ' --user=' . DBUSER . ' --password=' . DBPASSWORD . ' ' . DBNAME . ' > ' . $log;
        }else {
            $backupex = 'mysqldump -u ' . DBUSER . ' -p' . DBPASSWORD . ' sygesto > ' . $log;
        }
        $this->layout->assign('menus', $this->Groupemenu->get_by_role($this->session->stkdroits));
        $this->layout->setTitle('Bienvenue, ' . $this->session->stkusername, 'v');
        $this->layout->render('index', true);
    }
}
