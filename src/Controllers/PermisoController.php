<?php

namespace App\Controllers;

use App\Models\Database;
use App\Models\User;



class PermisoController
{
    private $userModel;

    public function __construct()
    {
        // Crear una instancia de la clase Database y obtener la conexión PDO
        $db = (new Database())->getConnection();

        // Pasar la conexión al modelo User
        $this->userModel = new User($db);
    }


    // SI ES 2 NO ES RESPUESTA API SI ES 1 SI ES RESPUESTA API JS
    public function getPermiso($usuario_id, $permiso_id, $referencia, $api = 2)
    {
        $permiso = $this->userModel->obtenerPermiso($usuario_id, $permiso_id);
        if ($permiso) {
            $this->userModel->crearBitacora($permiso_id, $usuario_id, $referencia);
            return true;

        } else {
            if ($api == 1) {
                return false;
            }

            alerta_redireccionar("Necesita el permiso # $permiso_id", "home");
            return false;

        }

    }

    // solo retorna si tiene o no tiene el permiso

    public function getPermiso_SB($usuario_id, $permiso_id)
    {
        $permiso = $this->userModel->obtenerPermiso($usuario_id, $permiso_id);
        if ($permiso) {
            return true;
        } else {
            return false;
        }

    }



}



