<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 19/08/2018
 * Time: 11:32
 */

namespace App\Core;


class Log
{

    /**
     * path folder to contnet file log
     * @var string
     */
    private $path;

    public function __construct()
    {

        $this->path = APP . DS . 'logs';
    }

    public function write($message)
    {
        if (ENV === 'PRODUCTION') {
            $path = $this->path . DS . date('Y-m-d') . '.txt';

            if (is_dir($this->path)) {
                if (!file_exists($path)) {
                    $file = fopen($path, 'a+') OR die('Erreure fatal !');
                    $contentFile = 'Time : ' . date('H:i:s') . "\r\n" . $message . "\r\n";
                    fwrite($file, $contentFile);
                    fclose($file);
                }else {
                    $this->edit($path, $message);
                }
            }else{
                if (mkdir($this->path, 0777) === true) {
                    $this->write($message);
                }

            }
        }
    }

    private function edit($path, $message)
    {
        $contentLog = file_get_contents($path);
        $newContentLog = 'Time : ' . date('H:i:s') . "\r\n" . $message . "\r\n\r\n" . $contentLog;
        file_put_contents($path, $newContentLog);
    }

}