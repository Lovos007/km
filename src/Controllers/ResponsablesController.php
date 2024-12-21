<?php

namespace App\Controllers;
use App\Models\MainModel;
use App\Models\Database;



final class ResponsablesController
{
    
    private $MainModel;

    public function __construct()
    {
        // Crear una instancia de la clase Database y obtener la conexión PDO
        $conexion = (new Database())->getConnection();
        $this->MainModel = new MainModel($conexion);
    }
    public function getResponsables($search = '')
    {
        if ($search == '') {
            // Si se encontró el perfiles, lo devuelve, de lo contrario, retorna un array vacío.
            $responsables = $this->MainModel->consultar('responsables');
            return $responsables ? $responsables : [];

        } else {
            $datos =
                [
                    'nombre' => '%' . $search . '%',
                    'cargo' => '%' . $search . '%',
                    'empresa' => '%' . $search . '%'
                    
                ];
                $datos = $this->MainModel->limpiarArray($datos);

            $responsables = $this->MainModel->consultar('responsables', $datos," OR ");
            return $responsables ? $responsables : [];

        }

    }
    public function getResponsable($responsable_id)
    {
        return $responsable = $this->MainModel->consultar('responsables',
         ['responsable_id' => $responsable_id]);

    }

    public function crearResponsable()
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
        $resultado = $this->MainModel->insertar("responsables", $datos);
    
        if ($resultado > 0) {
            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "Registro exitoso",
                "texto" => "El responsable  $nombre se registró correctamente.",
                "icono" => "success",
                "url" => BASE_URL . 'responsables'
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "No se pudo registrar el responsable.",
                "icono" => "error"
            ];
        }
        return json_encode($alerta);
    }

    public function modificarEstadoResponsable($responsable_id){
        $responsable = $this->getResponsable($responsable_id);
        
        $estado = $responsable[0]['estado'];
         
         $tabla='responsables';
         
         $filtro = ['responsable_id' => $responsable_id];

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
                    "titulo" => "responsable modificado",
                    "texto" => "El responsable se desactivo",
                    "icono" => "success",
                    "url" => BASE_URL . 'responsables'
                ];

            }else{
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "Ocurrio un error",
                    "texto" => "No se pudo modificar el responsable, intente nuevamente.",
                    "icono" => "error",
                    "url" => BASE_URL . 'responsables'
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
                    "titulo" => "responsable modificado",
                    "texto" => "El responsable se activo",
                    "icono" => "success",
                    "url" => BASE_URL . 'responsables'
                ];

            }else{
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "Ocurrio un error",
                    "texto" => "No se pudo modificar el responsable, intente nuevamente.",
                    "icono" => "error",
                    "url" => BASE_URL . 'responsables'
                ];
            }
        }
        return json_encode($alerta); // Retornar alerta en formato JSON
    }

    public function modificarResponsable(){
        if (empty($_POST['nombre'])  || empty($_POST['empresa'])|| empty($_POST['responsable_id']))  {

            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        // Recibir datos del formulario

       $responsable_id = $_POST['responsable_id']; 
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
        $filtro= ['responsable_id' => $responsable_id];

        $resultado = $this->MainModel->actualizar('responsables',$datos,$filtro);
        // Verificar si la inserción fue exitosa
        if ($resultado != '') {
            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "responsable modificado",
                "texto" => "El responsable $nombre se ha modificado correctamente.",
                "icono" => "success",
                "url" => BASE_URL . 'responsables'
            ];
        }

        return json_encode($alerta); // Retornar alerta en formato JSON



    

    }


    
}
