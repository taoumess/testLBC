<?php

namespace App\Controllers;

interface ControllerInterface
{
    /**
     * Methode pour page d'accueil
     */
    public function index($id = 0);

    /**
     * Methode pour page de creation
     * @param Int $id
     */
    public function add($id = 0);

    /**
     * Methode pour page de modification
     * @param Int $id
     */
    public function edit($id);

    /**
     * Methode pour page de suppression
     * @param Int $id
     */
    public function delete($id);

    /**
     * @param array $data
     *
     * @return array
     */
    public function sanitize(array $data = []): array;

    /**
     * Methode pour page de creation
     */
    public function create();
    
    
}