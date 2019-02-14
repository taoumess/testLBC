<?php

namespace App\Controllers;

use App\Controllers\ControllerInterface;

class AddressController extends MainController implements ControllerInterface
{
    /**
     * AddressController constructor.
     */
    public function __construct()
    {
        parent::__construct();
        
        if(!isset($_SESSION['auth']['id'])){
             header("Location: ".$this->baseUrl()."login");
        }
        $this->loadModel('Addresse');
        $this->loadModel('Contact');
    }

    /**
     * Affichage de la liste des adresses d'un Contact
     * @param Int $id
     */
    public function index($id = null)
    {
        $idContact = intval($id);
        $contact = $this->Contact->findById($idContact);
        $address = $this->Addresse->getByContact($idContact);
        echo $this->twig->render('addresslist.html.twig', [
            'addresses' => $address,
            'idContact' => $idContact,
            'contact'   => $contact,
            'baseUrl'   =>$this->baseUrl()
        ]);
    }

    /**
     * Ajout d'adresse pour un contact
     * @param Int $id
     */
    public function add($id=null)
    {
        $error = false;
        $id = intval($id);

        if (!empty($_POST)) {
            // Nettoyage
            $response = $this->sanitize($_POST);

            if ($response["response"]) {
                $idContact = $response['idContact'];
                $result = $this->Addresse->create([
                    'number'     => $response['number'],
                    'city'       => $response['city'],
                    'country'    => $response['country'],
                    'postalCode' => $response['postalCode'],
                    'street'     => $response['street'],
                    'idContact'  => $response['idContact']
                ]);

                if ($result) {
                    header("Location: ".$this->baseUrl()."adresse/liste/$idContact");
                } else {
                    $error = true;
                    $this->twig->render('addressadd.html.twig',
                        ["idContact" => $id,'error' => $error,'baseUrl'   =>$this->baseUrl()]);
                }
            } else {
                $error = true;
                $this->twig->render('addressadd.html.twig',
                    ["idContact" => $id,'error' => $error,'baseUrl'   =>$this->baseUrl()]);

            }
        }
        echo $this->twig->render('addressadd.html.twig',
            ["idContact" => $id,'error' => $error, 'baseUrl'   =>$this->baseUrl()]);
    }

    /**
     * Modification d'une adresse d'un contact
     * @param Int $id
     */
    public function edit($id)
    {
        $error = false;
        $id = intval($id);
        if (!empty($_POST)) {
            $response = $this->sanitize($_POST);

            if ($response["response"]) {
                $addresse = $this->Addresse->findById($id);
                $result = $this->Addresse->update($id,
                    [
                        'number'     => $response['number'],
                        'city'       => $response['city'],
                        'country'    => $response['country'],
                        'postalCode' => $response['postalCode'],
                        'street'     => $response['street'],
                    ]);
                if ($result) {
                    header("Location: ".$this->baseUrl()."adresse/liste/$addresse->idContact");
                } else {
                    $error = true;
                    $this->twig->render('addressadd.html.twig',
                        ["idContact" => $id,'error' => $error,'baseUrl'   =>$this->baseUrl()]);

                }
            } else {

                $error = true;
                $this->twig->render('addressadd.html.twig',
                    ["idContact" => $id,'error' => $error,'baseUrl'   =>$this->baseUrl()]);

            }
        }

        $data = $this->Addresse->findById($id);
        echo $this->twig->render('addressadd.html.twig',
            [
                'data'      => $data,
                "idContact" => $data->idContact,
                'baseUrl'   =>$this->baseUrl()
            ]);
    }

    /**
     * Suppression d'une adresse d'un contact
     * @param Int $id
     */
    public function delete($id)
    {
        $error = false;
        $id = intval($id);
        $addresse = $this->Addresse->findById($id);
        $result = $this->Addresse->delete($id);
         if ($result) {
                    header("Location: ".$this->baseUrl()."adresse/liste/$addresse->idContact");
                } else {
                    $error = true;
                    $this->twig->render('addressadd.html.twig',
                        ["idContact" => $id,'error' => $error,'baseUrl'   =>$this->baseUrl()]);

                }
       //@todo
    }


    /**
     * Vérifie les contrainte d'enregistrement
     *
     * @param array $data
     *
     * @return array
     */
    public function sanitize(array $data = []): array
    {
        $number     = $_POST['number'];
        $city       = strtoupper($_POST['city']);
        $country    = strtoupper($_POST['country']);
        $street     = strtoupper($_POST['street']);
        $idContact  = intval($_POST['idContact']);
        $postalCode = intval($_POST['postalCode']);

        if ($number && $city && $country && $postalCode && $street
            && $idContact
        ) {
            return [
                'response'   => true,
                'number'     => $_POST['number'],
                'city'       => strtoupper($_POST['city']),
                'country'    => strtoupper($_POST['country']),
                'postalCode' => $postalCode,
                'street'     => strtoupper($_POST['street']),
                'idContact'  => $_POST['idContact']
            ];
        } else {
            return ['response' => false];
        }
    }
    
     /**
     * Methode pour page de creation
     */
    public function create()
    {
        
    }
}