<?php

namespace App\Controllers;

use App\Controllers\ControllerInterface;
use InvalidArgumentException;
use Exception;

class ContactController extends MainController implements ControllerInterface {

    /** @var int $userId */
    protected $userId;

    /**
     * ContactController constructor.
     */
    public function __construct() 
    {
        parent::__construct();
        $this->userId = isset($_SESSION['auth']['id']) ? $_SESSION['auth']['id'] : null;
        
        if(!$this->userId){
            header("Location: ".$this->baseUrl()."login");
        }
        $this->loadModel('Contact');
    }

    /**
     * Affichage de la liste des contacts de l'utilisateur connecté
     * @param Int $id
     */
    public function index($id = 0) 
    {  
        $contacts = [];
        if (!empty($this->userId)) {
            $contacts = $this->Contact->getContactByUser($this->userId);
        }
        $router = new \App\Router\Router($_GET['url']);
        echo $this->twig->render('index.html.twig', ['contacts' => $contacts,'baseUrl'   =>$this->baseUrl()]);
    }

    /**
     * Ajout d'un contact
     * @param Int $id
     */
    public function add($id = 0) 
    {
        $error = false;
        if (!empty($_POST)) {
            $response = $this->sanitize($_POST);
            if (isset($response["response"]) && $response["response"]) {
                $result = $this->Contact->create([
                    'nom' => $response['nom'],
                    'prenom' => $response['prenom'],
                    'email' => $response['email'],
                    'userId' => $this->userId
                ]);
                if ($result) {
                    header("Location: ".$this->baseUrl()."home");
                }
            } else {
                $error = true;
                echo $this->twig->render('add.html.twig', ['error' => $response,'baseUrl'   =>$this->baseUrl(), 'data' => $_POST ]);
            }
        }else{
        echo $this->twig->render('add.html.twig', ['error' => $error,'baseUrl'   =>$this->baseUrl()]);
        }
    }

    /**
     * Modification d'un contact
     * @param Int $id
     */
    public function edit($id) 
    {
        $error = false;
        $id = intval($id);
        $userId = $_SESSION['auth']['id'];
        if (!empty($_POST)) {
            $response = $this->sanitize($_POST);
            if ($response["response"]) {
                $contact = $this->Contact->findById($id);
                $result = $this->Contact->update($id, [
                    'nom' => $response['nom'],
                    'prenom' => $response['prenom'],
                    'email' => $response['email'],
                    'userId' => $userId,
                ]);
                if ($result) {
                    header("Location: ".$this->baseUrl()."home");
                } else {
                    $error = true;
                    $this->twig->render('contactadd.html.twig', ["idContact" => $id, 'error' => $error,'baseUrl'   =>$this->baseUrl()]);
                }
            } else {

                $error = true;
                $this->twig->render('contactadd.html.twig', ["idContact" => $id, 'error' => $response,'baseUrl'   =>$this->baseUrl()]);
            }
        }

        $data = $this->Contact->findById($id);
        echo $this->twig->render('contactadd.html.twig', [
            'data' => $data,
            "userId" => $data->userId,
            'baseUrl'   =>$this->baseUrl()
        ]);
    }

    /**
     * Suppression d'un contact
     * @param Int $id
     */
    public function delete($id) 
    {
        $id = intval($id);
        $result = $this->Contact->delete($id);
        if ($result) {
            header("Location: ".$this->baseUrl()."home");
        }
    }

    /**
     * @param array $data
     * @return array
     * @throws Exception
     * @throws InvalidArgumentException
     */
    public function sanitize(array $data = []): array 
    {
        $errors = [];
        if (empty($data['nom'])) {
            //throw new Exception('Le nom est obligatoire');
            $errors['nom'] = 'Le nom est obligatoire';
        }

        if (empty($data['prenom'])) {
            $errors['prenom'] = 'Le prenom est obligatoire';
        }

        if (empty($data['email'])) {
            $errors['email'] = 'Le email est obligatoire';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Le format de l\'email est invalide';
        }

        $prenom = strtoupper($data['prenom']);
        $nom = strtoupper($data['nom']);
        $email = strtolower($data['email']);
        $isPalindrome = $this->apiClient('api/palindrome', ['name' => $data['nom']]);
        $isEmail = $this->apiClient('api/email-validation', ['email' => $data['email']]);

        if (!$isPalindrome->response && $isEmail->response && $prenom) {
            return [
                'response' => true,
                'email' => $email,
                'prenom' => $prenom,
                'nom' => $nom
            ];
        } else {
            $errors['response'] = false;
            return $errors;
        }
    }

    /**
     * Modification d'un contact
     */
    public function create() 
    {
        //@todo
    }
    
    /*
     * permet de vérifier si le mot est palindrome ou pas
     * @return bool
     */

    public function palindrome()
    {  
        header('Content-type: application/json');
        header('Access-Control-Allow-Origin: *');
        
        if(isset($_POST['name'])) {
            $word =  $_POST['name'];
        } else {
            $word = '';
        }
        
        $temp = '';
        $word = lcfirst($word);
        $length = strlen($word);
        
        for ($i = $length; $i >= 0; --$i) {
            @$temp .= $word[$i];
        }
        $return = ($word === $temp);
        
        echo json_encode(['response' => $return]);
    }
    
     /*
     * permet de vérifier si l'email est valide ou pas
     * @return bool
     */
    
    public function emailValidation()
    {
     
        header('Content-type: application/json');
        header('Access-Control-Allow-Origin: *');
        
        if (isset($_POST['email'])) {
            $email =  $_POST['email'];
        } else {
            $email = '';
        }
        
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
             $return = true; 
        } else {
             $return = false; 
        }
        echo json_encode(['response' => $return]);
    }
    

}
