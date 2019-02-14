<?php

define('ROOT', dirname(__DIR__ . '/..'));
require ROOT . '/app/App.php';
App::load();

/*if (isset($_GET['p'])) {
    $page = $_GET['p'];
} else {
    $page = 'contact.index';
}

$page = explode('.', $page);
$controller = '\App\Controllers\\' . ucfirst($page[0]) . 'Controller';
$action = $page[1];
$controller = new $controller();
$controller->$action();
*/
$router = new App\Router\Router($_GET['url']); 
$router->get('/', "Contact#index"); 


$router->get('contact/add', "Contact#add"); 
$router->post('contact/add', "Contact#add"); 
$router->get('contact/edit/:id', "Contact#edit")->with('id', '[0-9]+');
$router->post('contact/edit/:id', "Contact#edit")->with('id', '[0-9]+'); 
$router->get('contact/delete/:id', "Contact#delete")->with('id', '[0-9]+');


// URLs login et home page
$router->get('login', "User#login"); 
$router->post('/login', "User#login");
$router->get('logout', "User#logout"); 
$router->get('home', "Contact#index"); 

//liste des URLs de l'adresse
$router->get('adresse/liste/:id',"Address#index")->with('id', '[0-9]+');
$router->get('adresse/edit/:id',"Address#edit")->with('id', '[0-9]+');
$router->post('adresse/edit/:id',"Address#edit")->with('id', '[0-9]+');
$router->get('adresse/delete/:id',"Address#delete")->with('id', '[0-9]+');
$router->get('adresse/add/:id',"Address#add")->with('id', '[0-9]+');
$router->post('adresse/add/:id',"Address#add")->with('id', '[0-9]+');

//api de vérification nom et email
$router->post('api/palindrome',"Contact#palindrome");
$router->post('api/email-validation',"Contact#emailValidation");
//$router->get('api/addpalindrome',"Contact#addpalindrome");


$router->run(); 
