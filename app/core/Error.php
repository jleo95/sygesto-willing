<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 13/08/2018
 * Time: 21:34
 */

namespace App\Core;


class Error
{

    private $message;

    private $type;

    private $heading;

    private $file;

    private $defaultPath;

    private $arrError;

    public function __construct()
    {
        $this->arrError = [
            403 => ['title' => '403 Forbiden', 'message' => "Vous n'avez pas le droits d'accès au contenu de cet url"],
            404 => ['title' => '404 Not Found', 'message' => "L'url indiquée n'est pas correcte ou la ressource n'exite pas"]
        ];

        $this->type = 404;
        $this->message = $this->arrError[$this->type]['message'];
        $this->heading = $this->arrError[$this->type]['title'];
        $this->file = '404.php';
        $this->defaultPath = APP . DS . 'views' . DS . 'errors';
    }

    public function show()
    {
        $path = $this->defaultPath . DS . $this->file;
        $heading = $this->heading;
        $message = $this->message;
        require $path;
        die();
    }

    /**
     * @param string $message
     */
    public function setMessage($message)
    {
        $this->message = $message;
    }

    /**
     * @param array $arr ['type' => 404, 'message' => 'acces refuser', 'Not Found', ...]
     */
    public function setArr($arr = [])
    {
        if (isset($arr['type']) AND $arr['type'] !== 0) {
            if (isset($arr['type'])) {
                $this->type = $arr['type'];
            }

            if (isset($arr['message'])) {
                $this->message = $arr['message'];
            }else{
                if (isset($arr['type'])) {
                    $this->message = $this->arrError[$this->type]['message'];
                }
            }

            if (isset($arr['heading'])) {
                $this->heading = $arr['heading'];
            }else{
                if (isset($arr['type'])) {
                    $this->heading = $this->arrError[$this->type]['title'];
                }
            }
        }else {
            if (isset($arr['message'])) {
                $this->message = $arr['message'];
            }

            if (isset($arr['heading'])) {
                $this->heading = $arr['heading'];
            }
        }
    }

}