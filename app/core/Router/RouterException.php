<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 22/01/2019
 * Time: 00:16
 */

namespace App\Core\Router;


use App\Core\Error;
use App\Core\Log;
use Throwable;

class RouterException extends \Exception
{

    public function __construct(string $header, string $message = "")
    {
        $error = new Error();
        $log = new Log();
        $log->write($header . "\r\n" . $message);
        $error->setArr([
            'message' => '<strong>Message: </strong>' . $message . '<br><strong>File: </strong>' . $this->file . '<br><strong>Line: </strong>' . $this->line,
            'heading' => $header
        ]);
        $error->show();
    }

}