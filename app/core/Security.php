<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 06/07/2018
 * Time: 15:54
 */

namespace App\Core;


class Security
{
    /**
     * @param string $key element
     * @return array|string
     */
    public function post($key = '')
    {
        if (isset($_POST[$key]) AND !empty($_POST[$key])){
            if (is_array($_POST[$key])){
                $array = [];
                foreach ($_POST as $k => $v) {
                    if (!is_array($v)){
                        $array[$k] = htmlspecialchars($v);
                    }

                }

                return $array;
            }

            return htmlspecialchars($_POST[$key]);
        }
    }

    public function get($key = '')
    {
        if (isset($_GET[$key])){
            if (is_array($_GET[$key])){
                $array = [];

                foreach ($_GET as $k => $v) {
                    $array[$k] = htmlspecialchars($v);
                }

                return $array;
            }

            return htmlspecialchars($_GET[$key]);
        }
    }

    public function file($key = '')
    {
        if(isset($_FILES[$key]) AND !empty($_FILES[$key])){
            return $_FILES[$key];
        }

        return FALSE;
    }

    public function session($key)
    {
        if (isset($_SESSION[$key]) AND !empty($_SESSION[$key])) {
            return $_SESSION[$key];
        }else {
            return FALSE;
        }
    }

    public function server($key)
    {
        if ($_SERVER[$key]) {
            return htmlspecialchars($_SERVER[$key]);
        }

    }
}