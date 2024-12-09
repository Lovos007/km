<?php

namespace App\Controllers;

use App\Models\Database;
use App\Models\perfilModel;


class perfilController 
{
    private $perfilModel;

    public function __construct()
    {
        // Crear una instancia de la clase Database y obtener la conexión PDO
        $db = (new Database())->getConnection();
        
        // Pasar la conexión al modelo perfilModel
        $this->perfilModel = new perfilModel($db);
    }


    public function getPerfiles() {
        $usuarios = $this->perfilModel-> getPerfiles();    
        // Si se encontró el usuario, lo devuelve, de lo contrario, retorna un array vacío.
        return $usuarios? $usuarios : [];

    }

}