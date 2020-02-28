<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 07/07/2018
 * Time: 02:31
 */

namespace App\Core;


class Inputs
{

    private $input;

    public function __construct()
    {
        $this->input = new Security();

        if (isset($_POST) AND !empty($_POST)){
            foreach ($_POST as $k => $v) {
                $this->$k = $this->input->post($k);
            }
        }

        if (isset($_GET) AND !empty($_GET)){
            foreach ($_GET as $k) {
                $this->$k = $this->input->post($k);
            }
        }

        if (isset($_FILES) AND !empty($_FILES)){
            foreach ($_FILES as $k => $files) {
                $this->$k = $this->input->file($k);
            }
        }
    }

    public function server($k)
    {
        return $this->input->server($k);
    }

}