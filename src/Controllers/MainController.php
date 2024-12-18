<?php

namespace App\Controllers;

use App\Models\Database;
use App\Models\MainModel;

final class MainController 
{
    private $MainModel;

    public function __construct()
    {
        // Crear una instancia de la clase Database y obtener la conexión PDO
        $conexion = (new Database())->getConnection();
        $this->MainModel = new MainModel($conexion);
    }

    public function getEmpresas($search = '')
    {
        if ($search == '') {
            // Si se encontró el perfiles, lo devuelve, de lo contrario, retorna un array vacío.
            $empresas = $this->MainModel->consultar('empresas');
            return $empresas ? $empresas : [];

        } else {
            $datos =
                [
                    'empresa' => '%' . $search . '%'
                ];

            $vechiculos = $this->MainModel->consultar('empresas', $datos);
            return $vechiculos ? $vechiculos : [];

        }
    }
}