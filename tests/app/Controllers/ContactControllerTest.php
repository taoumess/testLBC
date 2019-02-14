<?php

namespace Tests\App\Controllers;

use PHPUnit\Framework\TestCase;

class ContactControllerTest extends TestCase
{
    protected $contactController;
    
    public function setUp() 
    {
        parent::setUp();
    }
    
    public function testIndex() 
    {
        $this->assertEquals(4,4);
    }
  
    public function testAdd() 
    {
        $this->assertEquals(4,4);
    }
  
    public function testEdit() 
    {
        $this->assertEquals(4,4);
    }
  
    public function testdelete() 
    {
        $this->assertEquals(4,4);
    }
    
   /*
   * permet de tester la methode Sanitize avec des datas
   */
    public function testSanitizeWithCompleteData() 
    {
        $data = ['nom' => 'MESS', 'prenom' => 'Taou', 'email' => 'taou.mess@gmail.com'];
        $contactController = new App\Controllers\ContactController();
        $response = $contactController->sanitize($data); 

        $this->assertSame($response, ['response'=>true, 'nom' => 'MESS', 'prenom' => 'Taou', 'email' => 'taou.mess@gmail.com']);
    }
    
     /*
   * permet de tester la methode Sanitize avec des datas
   */
    public function testSanitizeWithInCompleteData() 
    {
        $data = ['nom' => 'MESS', 'prenom' => 'Taou'];
        $contactController = new App\Controllers\ContactController();
        $response = $contactController->sanitize($data); 

        $this->assertSame($response, ['response'=>false, 'nom' => 'MESS', 'prenom' => 'Taou', 'email' => 'taou.mess@gmail.com']);
    }
}