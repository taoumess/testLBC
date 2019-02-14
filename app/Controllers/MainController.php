<?php

namespace App\Controllers;

use App;
use App\Controllers\Components\Auth\Auth;
use \Twig_Loader_Filesystem;
use \Twig_Environment;

class MainController
{
    /** @var Twig_Environment */
    protected $twig;

    /**
     * Controller constructor.
     */
    public function __construct()
    {
        //var_dump(App::getInstance()->getDatabase()); die;
        $this->loader = new \Twig_Loader_Filesystem(ROOT . '/app/Views');
        $this->twig   = new \Twig_Environment($this->loader);
        $this->auth   = new Auth(App::getInstance()->getDatabase());
        $this->twig->addGlobal('session', $_SESSION);
    }

    /**
     * Méthode de chargement de model
     * @param $model
     */
    protected function loadModel($model)
    {
        $this->$model = App::getInstance()->getModel($model);
    }

    /**
     * @param $methode
     * @param array $datas
     * @return mixed
     */
    public function apiClient($methode, $datas = [])
    {
        
        //var_dump($datas);
        $api = $this->baseUrl() . $methode;
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $api);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $datas);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $return = curl_exec($curl);
        //var_dump($return); die;
        curl_close($curl);
        return json_decode($return);
    }
    
    
    
     /**
     * Méthode qui permet de retourner la base url
     * @param 
     * @return String url
     */
    public function baseUrl()
    {
        $currentPath = $_SERVER['PHP_SELF'];  
        $pathInfo = pathinfo($currentPath); 
        $hostName = $_SERVER['HTTP_HOST']; 
        $protocol = strtolower(substr($_SERVER["SERVER_PROTOCOL"],0,5))=='https://'?'https://':'http://';
        return $protocol.$hostName.$pathInfo['dirname']."/";
    }
    
    
}