<?php

namespace App\Controllers;

use App\Models\Database;
use App\Models\MainModel;



class perfilController
{
    private $MainModel;

    public function __construct()
    {
        // Crear una instancia de la clase Database y obtener la conexión PDO
        $conexion = (new Database())->getConnection();
        $this->MainModel = new MainModel($conexion);
    }


    public function getPerfiles($search = '')
    {
        if ($search == '') {
            // Si se encontró el perfiles, lo devuelve, de lo contrario, retorna un array vacío.
            $perfiles = $this->MainModel->consultar('perfiles');
            return $perfiles ? $perfiles : [];

        } else {
            $datos =
                [
                    'nombre_perfil' => '%' . $search . '%'
                ];

            $perfiles = $this->MainModel->consultar('perfiles', $datos);
            return $perfiles ? $perfiles : [];

        }

    }
    public function getPerfil($perfil_id)
    {
        $datos =
                [
                    'perfil_id' => $perfil_id
                ];

        // Si se encontró el perfil, lo devuelve, de lo contrario, retorna un array vacío.
        $perfiles = $this->MainModel->consultar('perfiles',$datos);
        return $perfiles ? $perfiles : [];



    }

    public function crearPerfilNuevo()
    {

        // Validar que todos los campos requeridos estén presentes
        if (empty($_POST['nombre_perfil'])) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta); // Terminar ejecución con alerta de error
        }

        $nombre_perfil = $_POST['nombre_perfil'];
        $usuario_id = usuario_session();
        $estado = 0;

        // verificar que el perfil no exista
        $perfil_existe = $this->MainModel->consultar('perfiles', ['nombre_perfil' => $nombre_perfil]);
        $perfil_existe = count($perfil_existe);

        if ($perfil_existe > 0) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Error al registrar",
                "texto" => "El perfil $nombre_perfil ya existe",
                "icono" => "error"
            ];
            return json_encode($alerta); // Terminar ejecución con alerta de error

        }
        $resultado = $this->MainModel->agregarPerfil($nombre_perfil, $usuario_id, $estado);

        // Verificar si la inserción fue exitosa
        if ($resultado != '') {
            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "Perfil registrado",
                "texto" => "El perfil $nombre_perfil se ha registrado correctamente.",
                "icono" => "success",
                "url" => BASE_URL . 'perfil'
            ];
        }
        return json_encode($alerta); // Retornar alerta en formato JSON
    }

    public function modificarPerfil()
    {
        // Validar que todos los campos requeridos estén presentes
        if (empty($_POST['nombre_perfil']) || empty($_POST['perfil_id'])) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta); // Terminar ejecución con alerta de error
        }

        $nombre_perfil = $_POST['nombre_perfil'];
        $perfil_id = $_POST['perfil_id'];
        $usuario_id = usuario_session();
        $estado = 0;

        // verificar que el perfil no exista
        $perfil_existe = $this->MainModel->consultar('perfiles', ['nombre_perfil' => $nombre_perfil]);
        $perfil_existe = count($perfil_existe);

        if ($perfil_existe > 0) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Error al registrar",
                "texto" => "El perfil $nombre_perfil ya existe",
                "icono" => "error"
            ];
            return json_encode($alerta); // Terminar ejecución con alerta de error
        }

        $datos= ['nombre_perfil' => $nombre_perfil];
        $filtro= ['perfil_id' => $perfil_id];
        $resultado = $this->MainModel->actualizar('perfiles',$datos,$filtro);
        // Verificar si la inserción fue exitosa
        if ($resultado != '') {
            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "Perfil modificado",
                "texto" => "El perfil $nombre_perfil se ha modificado correctamente.",
                "icono" => "success",
                "url" => BASE_URL . 'perfil'
            ];
        }

        return json_encode($alerta); // Retornar alerta en formato JSON

    }

    public function  mostrarPermisosPerfil($perfil_id){

        return $this->MainModel->mostrarPermisosPerfil($perfil_id);
        
    }

    public function modificarEstadoPermiso(){       

        $perfil_id =base64_decode($_GET["dato1"]);
	    $permiso_id =base64_decode($_GET["dato2"]);       

        $datos= [
            'perfil_id'=>$perfil_id,
            'permiso_id'=>$permiso_id
        ];
        $perfil = $this->MainModel->consultar('perfiles_permisos',$datos);
        $estado = $perfil[0]['estado'];
         $tabla='perfiles_permisos';         
         $filtro = ['perfil_id' => $perfil_id,'permiso_id'=>$permiso_id];

         if ($estado > 0) {
            $datos = [
                'estado' => 0
                
            ];              

            $resultado = $this->MainModel->actualizar($tabla,$datos,$filtro);
            if ($resultado>0){
                $alerta = [
                    "tipo" => "redireccionar",
                    "url" => BASE_URL . 'editarP?datos='. base64_encode($perfil_id)
                ];

            }else{
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "Ocurrio un error",
                    "texto" => "No se pudo modificar el perfil, intente nuevamente.",
                    "icono" => "error",
                    "url" => BASE_URL . 'editarP?datos='. base64_encode($perfil_id)
                ];
            }           
        } else {
            $datos = [
                'estado' => 1,
                'usuario_u' => usuario_session()
                
            ];               

            $resultado = $this->MainModel->actualizar($tabla,$datos,$filtro);
            if ($resultado>0){
                $alerta = [
                    "tipo" => "redireccionar",
                    "url" => BASE_URL . 'editarP?datos='. base64_encode($perfil_id)
                ];
            }else{
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "Ocurrio un error",
                    "texto" => "No se pudo modificar el perfil, intente nuevamente.",
                    "icono" => "error",
                    "url" => BASE_URL . 'editarP?datos='. base64_encode($perfil_id)
                ];
            }

        }
        return json_encode($alerta); // Retornar alerta en formato JSON

    }

      // Método genérico para obtener y filtrar permisos
      public function obtenerPermisos($perfil_id, $query = '') {
        $lista = $this->mostrarPermisosPerfil($perfil_id);

        if (!empty($query)) {
            $query = strtolower($query);
            $lista = array_filter($lista, function ($item) use ($query) {
                return strpos(strtolower($item['permiso']), $query) !== false ||
                       strpos(strtolower($item['descripcion']), $query) !== false ||
                       strpos(strtolower($item['permiso_id']), $query) !== false;
            });
        }
        

        return $lista;
    }




}