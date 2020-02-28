<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 13/08/2018
 * Time: 17:33
 */

namespace App\Core;


class Autoloader
{
    /**
     * lancement des chargement des fichier
     * autoloader function
     */
    public static function autoloader()
    {
        spl_autoload_register([__CLASS__, 'loader']);
    }


    /**
     * chargement automatique des classe et fichier contenant ces classes
     * @param $class name class of loader
     */
    private static function loader($class)
    {
        $nameClasse = explode('\\', $class);
        $nameClasse = end($nameClasse);

        if (strpos($class, __NAMESPACE__ . '\\') === 0){
            $class = __DIR__ . DS . str_replace(__NAMESPACE__ . '\\', '', $class) . '.php';
        }else{
            if (file_exists(dirname(__DIR__) . DS . 'models' . DS . str_replace( 'App\\Models\\', '', $class) . '.php')){
                $class = dirname(__DIR__) . DS . 'models' . DS . str_replace( 'App\\Models\\', '', $class) . '.php';
            }elseif (file_exists(dirname(__DIR__) . DS . 'controllers' . DS . str_replace( 'App\\Controllers\\', '', $class) . '.php')){
                $class = dirname(__DIR__) . DS . 'controllers' . DS . str_replace( 'App\\Controllers\\', '', $class) . '.php';
            }else {
//                $class = dirname(__DIR__) . DS . 'lib' . DS . 'tcpdf' . DS . strtolower($class) . '.php';
            }

        }

        if (file_exists($class)){
            require $class;
        }else{
            #gestion des exception 
            require APP . DS . 'core' . DS . 'Error.php';
            $error = new \App\Core\Error();
            $error->setArr([
                'message' => 'La classe ' . $nameClasse . ' est introuvable'
            ]);
            
            #garder la trace de l'erreur dans le dossier app/logs
            $log = new Log();
            $log->write('La classe ' . $nameClasse . ' est introuvable ou l\'accès à cette classe est restrict');
            
            #afficher l'erreur
            $error->show();
        }
    }

}