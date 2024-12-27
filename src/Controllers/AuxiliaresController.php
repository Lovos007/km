<?php

namespace App\Controllers;
use App\Models\MainModel;
use App\Models\Database;



final class AuxiliaresController
{
    
    private $MainModel;

    public function __construct()
    {
        // Crear una instancia de la clase Database y obtener la conexión PDO
        $conexion = (new Database())->getConnection();
        $this->MainModel = new MainModel($conexion);
    }
    public function getAuxiliares($search = '',$filtro=[])
    {
        if ($search == '') {
            // Si se encontró el perfiles, lo devuelve, de lo contrario, retorna un array vacío.
            $auxiliares = $this->MainModel->consultar('auxiliares',$filtro);
            return $auxiliares ? $auxiliares : [];

        } else {
            $datos =
                [
                    'nombre' => '%' . $search . '%',
                    'cargo' => '%' . $search . '%',
                    'empresa' => '%' . $search . '%'
                    
                ];
                $datos = $this->MainModel->limpiarArray($datos);

            $auxiliares = $this->MainModel->consultar('auxiliares', $datos," OR ");
            return $auxiliares ? $auxiliares : [];

        }

    }
    public function getAuxiliar($auxiliar_id)
    {
        return $auxiliar = $this->MainModel->consultar('auxiliares', ['auxiliar_id' => $auxiliar_id]);

    }

    public function crearAuxiliar()
    {
        if (empty($_POST['nombre'])  || empty($_POST['empresa']))  {

            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        // Recibir datos del formulario
        $nombre = $_POST['nombre'];        
        $cargo = $_POST['cargo'] ?? null;
        $empresa = $_POST['empresa'];
        $usuario_c = usuario_session();
        $estado = 1;

    
        // Datos a insertar
        $datos = [
            'nombre' => $nombre,
            'cargo' => $cargo,
            'empresa' => $empresa,
            'estado' => $estado,
            'usuario_c' => $usuario_c
        ];
        $datos = $this->MainModel->limpiarArray($datos);
    
        // Insertar en la base de datos
        $resultado = $this->MainModel->insertar("auxiliares", $datos);
    
        if ($resultado > 0) {
            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "Registro exitoso",
                "texto" => "El auxiliar  $nombre se registró correctamente.",
                "icono" => "success",
                "url" => BASE_URL . 'auxiliares'
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "No se pudo registrar el auxiliar.",
                "icono" => "error"
            ];
        }
        return json_encode($alerta);
    }

    public function modificarEstadoAuxiliar($auxiliar_id){
        $auxliar = $this->getAuxiliar($auxiliar_id);
        
        $estado = $auxliar[0]['estado'];
         
         $tabla='auxiliares';
         
         $filtro = ['auxiliar_id' => $auxiliar_id];

         if ($estado > 0) {
            $datos = [
                'estado' => 0,
                'usuario_u' => usuario_session()                
            ];     
            $datos = $this->MainModel->limpiarArray($datos);          

            $resultado = $this->MainModel->actualizar($tabla,$datos,$filtro);
            if ($resultado>0){
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "auxliar modificado",
                    "texto" => "El auxliar se desactivo",
                    "icono" => "success",
                    "url" => BASE_URL . 'auxiliares'
                ];

            }else{
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "Ocurrio un error",
                    "texto" => "No se pudo modificar el auxliar, intente nuevamente.",
                    "icono" => "error",
                    "url" => BASE_URL . 'auxiliares'
                ];
            }
           
        } else {
            $datos = [
                'estado' => 1,
                'usuario_u' => usuario_session()               
            ];    
            $datos = $this->MainModel->limpiarArray($datos);

            $resultado = $this->MainModel->actualizar($tabla,$datos,$filtro);
            if ($resultado>0){
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "auxliar modificado",
                    "texto" => "El auxliar se activo",
                    "icono" => "success",
                    "url" => BASE_URL . 'auxiliares'
                ];

            }else{
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "Ocurrio un error",
                    "texto" => "No se pudo modificar el auxliar, intente nuevamente.",
                    "icono" => "error",
                    "url" => BASE_URL . 'auxiliares'
                ];
            }
        }
        return json_encode($alerta); // Retornar alerta en formato JSON
    }

    public function modificarAuxiliar(){
        if (empty($_POST['nombre'])  || empty($_POST['empresa'])|| empty($_POST['auxiliar_id']))  {

            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        // Recibir datos del formulario

       $auxiliar_id = $_POST['auxiliar_id']; 
       $nombre = $_POST['nombre'];        
       $cargo = $_POST['cargo'] ?? null;
       $empresa = $_POST['empresa'];
       $usuario_u = usuario_session();
       $estado = 1;

   
       // Datos a insertar
       $datos = [
           'nombre' => $nombre,
           'cargo' => $cargo,
           'empresa' => $empresa,
           'estado' => $estado,
           'usuario_u' => $usuario_u
       ];
       $datos = $this->MainModel->limpiarArray($datos);
        $filtro= ['auxiliar_id' => $auxiliar_id];

        $resultado = $this->MainModel->actualizar('auxiliares',$datos,$filtro);
        // Verificar si la inserción fue exitosa
        if ($resultado != '') {
            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "Auxiliar modificado",
                "texto" => "El auxiliar $nombre se ha modificado correctamente.",
                "icono" => "success",
                "url" => BASE_URL . 'auxiliares'
            ];
        }

        return json_encode($alerta); // Retornar alerta en formato JSON



    

    }


    
}
