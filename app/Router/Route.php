<?php
namespace App\Router;

/*
 * Cette classe va contenir l'ensemble des routes 
 */

class Route {

    private $path;
    private $callable;
    private $matches = [];
    private $params = [];

    public function __construct($path, $callable)
    {
        $this->path = trim($path, '/');  // On retire les / inutils
        $this->callable = $callable;
    }

    /**
    * Permettra de capturer l'url avec les paramètre et dire si la route match ou pas 
    * @param Mixte $url
    * @return bool 
    **/
   public function match($url)
   {
        $url = trim($url, '/');
        $path = preg_replace_callback('#:([\w]+)#', [$this, 'paramMatch'], $this->path);
        $regex = "#^$path$#i";
        if(!preg_match($regex, $url, $matches)){
            return false;
        }
        array_shift($matches);
        //:id
        $this->matches = $matches;
        return true;
   }

    /*
     * permet de retourner une expression regulière
     * @param String $match
     * @return REGEX 
     * 
     * 
     */

    private function paramMatch($match)
    {
        if(isset($this->params[$match[1]])){
            return '(' . $this->params[$match[1]] . ')';
        }
        return '([^/]+)';
    }

    /*
     * permet d'ajouter une vérification (contraintes) sur les parametres
     * @param String $param
     * @param REGEX $regex
     * @return Object 
     */
    public function with($param, $regex)
    {
        $this->params[$param] = str_replace('(', '(?:', $regex);
        return $this; // On retourne tjrs l'objet pour enchainer les arguments
    }

    /*
     * Permet de creer URL
     * @param Array $params
     * @return Mixte $path
     */
    public function getUrl($params)
    {
        $path = $this->path;
        foreach($params as $k => $v){
            $path = str_replace(":$k", $v, $path);
        }
        return $path;
    }
    
    /*
     * permet d'appeler et executer les callables avec les parametres 
     */
    
    public function call()
    {
        if(is_string($this->callable)){
            $params = explode('#', $this->callable);
            $controller = "App\\Controllers\\" . $params[0] . "Controller";

            $controller = new $controller();
            return call_user_func_array([$controller, $params[1]], $this->matches);
        } else {
            return call_user_func_array($this->callable, $this->matches);
        }
    }

}