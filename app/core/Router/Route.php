<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 21/01/2019
 * Time: 23:55
 */

namespace App\Core\Router;


class Route
{

    /**
     * @var string
     */
    private $path;
    /**
     * @var callable
     */
    private $callable;

    private $matches = [];

    private $params = [];

    public function __construct(string $path, $callable)
    {
        $this->path = trim($path, '/');
        $this->callable = $callable;
    }

    public function match($url)
    {
        $url = trim($url, '/');
        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);
        $regex = "#^$path$#i";

        if (!preg_match($regex, $url, $matches)) {
            return false;
        }

        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    private function paramMatch($match)
    {
        if (isset($this->params[$match[1]])) {
            return '(' . $this->params[$match[1]] . ')';
        }
        return '([^/]+)';
    }

    public function with($params, $regex)
    {
        $this->params[$params] = str_replace('(', '(?:', $regex);
        return $this;
    }

    public function call()
    {
        if (is_string($this->callable)) {
            $params = explode('#', $this->callable);
            $controller = "App\\Controllers\\" . ucfirst($params[0]) . "Controller";
            $controller = new $controller();
            return call_user_func_array([$controller, $params[1]], $this->matches);
        }
        return call_user_func_array($this->callable, $this->matches);
    }

    public function getUrl($params)
    {
        $path = $this->path;
        foreach ($params as $k => $v) {
            $path = str_replace(":$k", $v, $path);
        }
        return $path;
    }

}