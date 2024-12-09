<?php

namespace App\Models;

use PDO;

class perfilModel
{
    private $db;

    public function __construct(PDO $db)
    {
        $this->db = $db;
    }


    public function getPerfiles()
    {
        // Consulta para obtener todos los perfiles
        $stmt = $this->db->prepare("
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