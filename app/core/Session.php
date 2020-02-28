<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 06/07/2018
 * Time: 14:35
 */

namespace App\Core;


class Session
{
    private static $session;

    private $security;

    public function __construct()
    {
        self::start();
        $this->security = new Security();

        foreach ($_SESSION as $k => $v) {
            if ($this->security->session($k) !== '' OR $this->security->session($k) !== FALSE){
                if ($k == 'md_userauth') {
                    $key = 'userauth';
                    $this->$key = $this->security->session($k);
                }else {
                    $this->$k = $this->security->session($k);
                }


            }
        }
    }


    private static function start()
    {
        if (is_null(self::$session)) {
            self::$session = session_start();
        }
    }
}