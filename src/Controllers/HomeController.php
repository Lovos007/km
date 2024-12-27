<?php

namespace App\Controllers;

use App\Views\View;

class HomeController
{
    // Si quieres usar $vista como propiedad de la clase
    private $vista;

    public function __construct($vista) 
    {
        // Asignar la vista por defecto o la pasada al constructor
        $this->vista = $vista;
        if ($vista==""){
            $vista="/home";
        }
    }


    public function index()
    {
        // Datos que quieres pasar a la vista
        $data = [
            'fecha_actual_larga' => date('Y-m-d H:i:s'),
            'fecha_actual_corta' => date('Y-m-d')
        ];

        // Renderizar la vista

        if ($this->vista=="/"){
            View::render('home', $data);
        }else{
            View::render($this->vista, $data);
        }
    }
}
