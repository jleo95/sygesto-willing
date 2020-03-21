<?php
/**
 * Created by PhpStorm.
 * User: LEOBA/Willing
 * Date: 13/08/2018
 * Time: 17:34
 */

define('DS', DIRECTORY_SEPARATOR);
define('ROOT', dirname(dirname(dirname(__FILE__))));
define('APP', ROOT . DS . 'app');
define('BASEURL', 'http://sygesto.local/');

define('TIME_OUT', 5600);
define("LOGO", "ipw.png");
define('ENV', 'DEVELOPPEMENT');


/** database */
define('DBNAME', 'sygesto');
define('DBHOST', 'localhost');
define('DBPASSWORD', '');
define('DBUSER', 'root');