<?php
namespace App\Router;

class Router {

    private $url;
    private $routes = [];
    private $namedRoutes = [];
    /*
     * Routeur constructeur
     * @param Mixte $url
     */
    public function __construct($url)
    {
        $this->url = $url;
    }
    
    /*
     * Cette permet de traiter les URL de la methode GET
     * @param Mixte $path
     * @param String $callable
     * @param String $name
     * 
     */
    public function get($path, $callable, $name = null)
    {
        return $this->add($path, $callable, $name, 'GET');
    }
    
     /*
     * Cette permet de traiter les URL de la methode POST
     * @param Mixte $path
     * @param String $callable
     * @param String $name
     * 
     */
    public function post($path, $callable, $name = null)
    {
        return $this->add($path, $callable, $name, 'POST');
    }
    
     /*
     * Cette permet de traiter les URL de la methode DELETE
     * @param Mixte $path
     * @param String $callable
     * @param String $name
     * 
     */
    
    public function delete($path, $callable, $name = null)
    {
        return $this->add($path, $callable, $name, 'DELETE');
    }
    
    /*
     * permet de creer une route
     * @param Mixte $path
     * @param String $callable
     * @param String $name
     * @param String $method
     * @return Mixte $route
     */

    private function add($path, $callable, $name, $method)
    {

        $route = new Route($path, $callable);
         //var_dump($route); die;
        $this->routes[$method][] = $route;
        if(is_string($callable) && $name === null){
            $name = $callable;
        }
        if($name){
            $this->namedRoutes[$name] = $route;
        }
        return $route;
    }

    /*
     * Permet de vérifier si l'url saisie correspond à l'une des routes 
     */
    public function run()
    {
        if(!isset($this->routes[$_SERVER['REQUEST_METHOD']])){
            throw new RouterException('REQUEST_METHOD does not exist');
        }
        foreach($this->routes[$_SERVER['REQUEST_METHOD']] as $route){
            if($route->match($this->url)){
                return $route->call();
            }
        }
        
        throw new RouterException('No matching routes !!!');
    }

    /*
     * permet de creer une URL
     * @param String $name
     * @param Array $params
     * @return Mixte url
     */
    public function url($name, $params = [])
    {
        if(!isset($this->namedRoutes[$name])){
            throw new RouterException('No route matches this name');
        }
        return $this->namedRoutes[$name]->getUrl($params);
    }

}

