<?php
/**
 * Created by PhpStorm.
 * User: LEOBA
 * Date: 21/01/2019
 * Time: 23:51
 */

namespace App\Core\Router;


use Psr\Http\Message\ServerRequestInterface;

class Router
{

    /**
     * @var string
     */
    private $url;

    private $routes;

    private $nameRoutes = [];

    public function __construct(string $url)
    {
        $this->url = $url;
    }

    public function get(string $path, $callable, string $name = null, $params = [])
    {
        return $this->add($path, $callable, $name, 'GET');
    }

    public function post(string $path, $callable, string $name = null, $params = [])
    {
        return $this->add($path, $callable, $name, 'POST');
    }

    private function add(string $path, $callable, string $name = null, string $method)
    {
        $route = new Route($path, $callable);
        $this->routes[$method][] = $route;

        if (is_string($callable) && $name === null) {
            $name = $callable;
        }

        if ($name) {
            $this->nameRoutes[$name] = $route;
        }

        return $route;
    }

    public function run()
    {
        if (!isset($this->routes[$_SERVER['REQUEST_METHOD']])) {

            throw new RouterException('Not Found', 'REQUEST_METHODE does not exist');
        }
        foreach ($this->routes[$_SERVER['REQUEST_METHOD']] as $route) {
            if ($route->match($this->url)) {
                return $route->call();
            }
        }

        throw new RouterException('Forbiden','No matching route this url "' . $this->url . '"');
    }

    public function url(string $name, array $params = [])
    {
        if (!isset($this->nameRoutes[$name])) {
            throw new RouterException('Forbiden', 'No route matches this name');
        }
        return $this->nameRoutes[$name]->getUrl($params);
}
    
}