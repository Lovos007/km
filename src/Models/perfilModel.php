<?php

namespace App\Models;

use PDO;

class perfilModel extends MainModel
{
   // Constructor que recibe una conexión a la base de datos
   
   private $conexion;
   

   public function __construct($conexion)
   {
       $this->conexion = $conexion;
   }    


    public function getPerfiles()
    {       
        // Consulta para obtener todos los perfiles
        $stmt = $this->conexion->prepare("
            SELECT * 
            FROM perfiles
            ");
        $stmt->execute();

        // Recupera todas las filas como un array asociativo
        $perfiles = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Retorna los perfiles si existen, o un array vacío si no hay resultados
        return $perfiles ?: [];
    }

    

}