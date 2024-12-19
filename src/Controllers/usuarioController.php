<?php

namespace App\Controllers;

use App\Models\Database;
use App\Models\User;
use App\Models\MainModel;


class usuarioController
{
    private $userModel;
    private $MainModel;

    public function __construct()
    {
        // Crear una instancia de la clase Database y obtener la conexión PDO
        $db = (new Database())->getConnection();

        // Pasar la conexión al modelo User
        $this->userModel = new User($db);
        $this->MainModel = new MainModel($db);
    }

    public function getUsuario($id)
    {
        $id = $this->MainModel->limpiarCadena($id);
        $usuario = $this->userModel->getUsuario($id);

        // Si se encontró el usuario, lo devuelve, de lo contrario, retorna un array vacío.
        return $usuario ? $usuario : [];
    }

    public function getUsuarios($search)
    {
        $search = $this->MainModel->limpiarCadena($search);
        $usuarios = $this->userModel->getUsuarios($search);

        // Si se encontró el usuario, lo devuelve, de lo contrario, retorna un array vacío.
        return $usuarios ? $usuarios : [];
    }

    public function crearUsuario()
    {
        // Validar que todos los campos requeridos estén presentes
        if (empty($_POST['nombre_usuario']) || empty($_POST['nombre']) || empty($_POST['password']) || empty($_POST['perfil'])) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta); // Terminar ejecución con alerta de error
        }


        // Asignar variables desde POST
        $nombre_usuario = $_POST['nombre_usuario'];
        $nombre_apellido = $_POST['nombre'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar la contraseña
        $perfil = $_POST['perfil'];

        $usuario_c = $_SESSION['user_id'];

        // verificar que no exista el usuario
        $usuario_existe = $this->MainModel->consultar('usuarios', ['nombre_usuario' => $nombre_usuario]);
        $usuario_existe = count($usuario_existe);
        // return json_encode (var_dump(count($usuario_existe)));

        if ($usuario_existe > 0) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Error al registrar",
                "texto" => "El usuario $nombre_usuario ya existe",
                "icono" => "error"
            ];
            return json_encode($alerta); // Terminar ejecución con alerta de error

        }

        $permiso = new PermisoController();
        //PERMISO ID 3 VER CREAR USUARIOS
        $numero_permiso = 3;
        $v_permiso = $permiso->getPermiso($_SESSION['user_id'], $numero_permiso, $nombre_usuario, 1);
        // SI NO TIENE PERMISO

        if ($v_permiso == false) {
            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "Error de permisos",
                "texto" => "Necesitas el permiso # " . $numero_permiso,
                "icono" => "error",
                "url" => BASE_URL . 'usuario'
            ];
            return json_encode($alerta); // Terminar ejecución con alerta de error

        }

        // Crear los datos para insertar
        $datos = [
            'nombre_usuario' => $nombre_usuario,
            'nombre_apellido' => $nombre_apellido,
            'password' => $password,
            'perfil_id' => $perfil,
            'estado' => 1,
            'usuario_c' => $usuario_c

        ];

        $datos = $this->MainModel->limpiarArray($datos);

        // Especificar la tabla
        $tabla = 'usuarios';

        // Intentar insertar los datos en la base de datos
        $resultado = $this->MainModel->insertar($tabla, $datos);

        // Verificar si la inserción fue exitosa
        if ($resultado > 0) {

            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "Usuario registrado",
                "texto" => "El usuario $nombre_usuario se ha registrado correctamente.",
                "icono" => "success",
                "url" => BASE_URL . 'usuario'
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Error al registrar",
                "texto" => "No se pudo registrar el usuario, intente nuevamente.",
                "icono" => "error"
            ];
        }

        return json_encode($alerta); // Retornar alerta en formato JSON
    }


    public function editarUsuario()
    {

        // Validar que todos los campos requeridos estén presentes
        if (empty($_POST['nombre_usuario']) || empty($_POST['usuario_id']) || empty($_POST['nombre']) || empty($_POST['password']) || empty($_POST['perfil'])) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta); // Terminar ejecución con alerta de error
        }


        // Asignar variables desde POST

        $usuario_id = $_POST['usuario_id'];
        $nombre_usuario = $_POST['nombre_usuario'];
        $nombre_apellido = $_POST['nombre'];
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptar la contraseña
        $perfil = $_POST['perfil'];
        $usuario_c = $_SESSION['user_id'];
        //Armando datos para consulta
        $datos = [
            'nombre_apellido' => $nombre_apellido,
            'password' => $password,
            'perfil_id' => $perfil,
            'usuario_u' => usuario_session()

        ];
        $datos = $this->MainModel->limpiarArray($datos);

        $filtro = ['usuario_id' => $usuario_id];

        $permiso = new PermisoController();
        //PERMISO ID 4 EDITAR USUARIOS
        $numero_permiso = 4;
        $v_permiso = $permiso->getPermiso($_SESSION['user_id'], $numero_permiso, $nombre_usuario, 1);
        // SI NO TIENE PERMISO

        if ($v_permiso == false) {
            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "Error de permisos",
                "texto" => "Necesitas el permiso # " . $numero_permiso,
                "icono" => "error",
                "url" => BASE_URL . 'usuario'
            ];
            return json_encode($alerta); // Terminar ejecución con alerta de error

        }


        $resultado = $this->MainModel->actualizar('usuarios', $datos, $filtro);
        // Verificar si la modificacion fue exitosa
        if ($resultado > 0) {

            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "Usuario modificado",
                "texto" => "El usuario $nombre_usuario se ha modificado correctamente.",
                "icono" => "success",
                "url" => BASE_URL . 'usuario'
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Error al modificar usuario",
                "texto" => "No se pudo modificar el usuario, intente nuevamente.",
                "icono" => "error"
            ];
        }

        return json_encode($alerta); // Retornar alerta en formato JSON


    }

    public function modificarEstadoUsuario($usuario_id){
        $usuario = $this->getUsuario($usuario_id);
        $estado = $usuario['estado'];
        $nombre_usuario = $usuario['nombre_usuario'];
         
         $tabla='usuarios';
         
         $filtro = ['usuario_id' => $usuario_id];

         if ($estado > 0) {
            $datos = [
                'estado' => 0
                
            ];    

            $permiso = new PermisoController();
            //PERMISO ID 5 ACTIVAR Y DESACTIVAR USUARIOS
            $numero_permiso = 5;
            $v_permiso = $permiso->getPermiso($_SESSION['user_id'], $numero_permiso, "Desactivo ".$nombre_usuario, 1);
            // SI NO TIENE PERMISO
    
            if ($v_permiso == false) {
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "Error de permisos",
                    "texto" => "Necesitas el permiso # " . $numero_permiso,
                    "icono" => "error",
                    "url" => BASE_URL . 'usuario'
                ];
                return json_encode($alerta); // Terminar ejecución con alerta de error
    
            }

            $resultado = $this->MainModel->actualizar($tabla,$datos,$filtro);
            if ($resultado>0){
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "Usuario modificado",
                    "texto" => "El usuario se desactivo",
                    "icono" => "success",
                    "url" => BASE_URL . 'usuario'
                ];

            }else{
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "Ocurrio un error",
                    "texto" => "No se pudo modificar el usuario, intente nuevamente.",
                    "icono" => "error",
                    "url" => BASE_URL . 'usuario'
                ];
            }


           
        } else {
            $datos = [
                'estado' => 1,
                'usuario_u' => usuario_session()
                
            ];    
            $permiso = new PermisoController();
            //PERMISO ID 5 ACTIVAR Y DESACTIVAR USUARIOS
            $numero_permiso = 5;
            $v_permiso = $permiso->getPermiso($_SESSION['user_id'], $numero_permiso, "Activo ".$nombre_usuario, 1);
            // SI NO TIENE PERMISO
    
            if ($v_permiso == false) {
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "Error de permisos",
                    "texto" => "Necesitas el permiso # " . $numero_permiso,
                    "icono" => "error",
                    "url" => BASE_URL . 'usuario'
                ];
                return json_encode($alerta); // Terminar ejecución con alerta de error
    
            }

            $resultado = $this->MainModel->actualizar($tabla,$datos,$filtro);
            if ($resultado>0){
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "Usuario modificado",
                    "texto" => "El usuario se activo",
                    "icono" => "success",
                    "url" => BASE_URL . 'usuario'
                ];

            }else{
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "Ocurrio un error",
                    "texto" => "No se pudo modificar el usuario, intente nuevamente.",
                    "icono" => "error",
                    "url" => BASE_URL . 'usuario'
                ];
            }
        }
        return json_encode($alerta); // Retornar alerta en formato JSON


    }


}



