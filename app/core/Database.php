<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 14/08/2018
 * Time: 01:02
 */

namespace App\Core;


class Database
{
    /**
     * database name
     * @var string
     */
    private $dbname;

    /**
     * database user
     * @var String
     */
    private $dbuser;

    /**
     * database host (path or ip)
     * @var String
     */
    private $dbhost;

    /**
     * database password, default valeur par defaut vide
     * @var String or Integer
     */
    private $dbpassword;


    /**
     * instance garde une instance de cette classe (Database)
     * @var Database
     */
    private static $_dbinstance;

    /**
     * instance de l'objet PDO
     * @var \PDO
     */
    private static $pdo;

    private $error;

    /**
     * Database constructor.
     * @param string $dbname
     * @param String $dbuser
     * @param String $dbhost
     * @param String $dbpassword
     */
    public function __construct($dbname, $dbuser, $dbhost, $dbpassword)
    {
        $this->dbname = $dbname;
        $this->dbuser = $dbuser;
        $this->dbhost = $dbhost;
        $this->dbpassword = $dbpassword;
    }


    /**
     * Returne une instance de la classe Database
     * @return Database
     */
    public static function getInstance()
    {
        if (is_null(self::$_dbinstance)) {
            self::$_dbinstance = new Database(DBNAME, DBUSER, DBHOST, DBPASSWORD);
        }

        return self::$_dbinstance;
    }

    /**
     * Connexion a la base de donnee
     * @return \PDO returne une instance de l'objet PDO
     */
    private function getPDO()
    {

        try{
            if (is_null(self::$pdo)) {
                $pdo = new \PDO(
                    'mysql:dbname=' . $this->dbname . ';host=' . $this->dbhost, $this->dbuser, $this->dbpassword,
                    array(\PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8")
                );
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
                self::$pdo = $pdo;
            }
            return self::$pdo;
        }catch (\PDOException $ex) {
            $heading = 'SQLSTATE[HY000] [' . $ex->getCode() . ']';
            $message = $ex->getMessage();
            $this->logException($heading, $message);
        }
    }


    /**
     * requette non preparee
     * @param string $statement requtte (en chaine de caractere)
     * @param bool $one vrai si on veut une ligne du resultat de la requete (la premiere ligne par defaut)
     * @return array|bool|mixed|\PDOStatement resultat returne
     */
    public function statementQuery($statement, $one = FALSE)
    {
        try{
            $res = $this->getPDO()->query($statement);

            if (strpos($statement, 'UDPATE') === 0 || strpos($statement, 'INSERT') === 0 || strpos($statement, 'DELETE') === 0) {
                return TRUE;
            }

            $res->setFetchMode(\PDO::FETCH_OBJ);

            if ($one) {
                $res = $res->fetch();
            }else{
                $res = $res->fetchAll();
            }

            return $res;

        }catch (\PDOException $ex) {
            $error = new Error();
            $arr = $ex->getTrace();
            $posElement = count($arr);
            $element = $arr[$posElement - 5];
//            var_dump($ex);
            $heading = 'SQLSTATE [' . $ex->getCode() . ']';
            $message = '<strong>SQLSTATE [' . $ex->getCode() . '] : </strong>Erreur de syntaxe SQL à ligne ' . $element['line'] .
                '<br> <strong>Message d\'erreur : </strong>' . str_replace('à la ligne 1', '', $ex->getMessage()) .
                '<br><strong>Fichier : </strong>' . $element['file'] . ' <br><strong>Classe : </strong>' . $element['class'] .
                '<br><strong>Methode : </strong>' . $element['function'] .
                '<br><strong>Ligne : </strong>' . $element['line'];
            $this->logException($heading, $message);
        }
    }


    /**
     * requette preparee 
     * @param string $statement requete (en chaine)
     * @param array $arr argument a transmettre a la requette
     * @param bool $one if it want one or many line result
     * @return array|bool|mixed|\PDOStatement resultat de la requette
     */
    public function statementPrepare($statement, $arr, $one = FALSE)
    {
        try{

            $res = $this->getPDO()->prepare($statement);
            $result = $res->execute($arr);

            if(strpos($statement, "UPDATE") === 0 || strpos($statement, "DELETE") === 0 || strpos($statement, "INSERT") === 0){

                return $result;

            }
            $res->setFetchMode(\PDO::FETCH_OBJ);

            if ($one) {
                $res = $res->fetch();
            }else {
                $res = $res->fetchAll();
            }

            return $res;

        }catch (\PDOException $ex) {
            $arr = $ex->getTrace();
            $posElement = count($arr);
            $element = $arr[$posElement - 5];
            $elementCalled = $arr[$posElement - 4];
//            var_dump($ex);
            $message = '<strong>SQLSTATE [' . $ex->getCode() . '] : </strong>Erreur de syntaxe SQL à ligne ' . $element['line'] .
                '<br> <strong>Message d\'erreur : </strong>' . str_replace('à la ligne 1', '', $ex->getMessage()) .
                '<br><strong>Fichier : </strong>' . $element['file'] . ' <br><strong>Classe : </strong>' . $elementCalled['class'] .
                '<br><strong>Methode : </strong>' . $elementCalled['function'] .
                '<br><strong>Ligne : </strong>' . $element['line'];
            $heading = 'SQLSTATE [' . $ex->getCode() . ']';

            $this->logException($heading, $message);
        }
    }

    /**
     * gestion des exception 
     * @param type $heading le titre du message
     * @param type $message message d'erreur
     */
    private function logException($heading, $message)
    {
        $error = new Error();
        $error->setArr([
            'heading' => $heading,
            'message' => $message
        ]);

        $mssge = $heading . '\r\n' . $message;
        
        #garder les traces des erreurs
        $log = new Log();
        $log->write($mssge);

        $error->show();
    }


}