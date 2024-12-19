<?php

namespace App\Controllers;

use App\Models\Database;
use App\Models\MainModel;

final class VehiculoController 
{
    private $MainModel;

    public function __construct()
    {
        // Crear una instancia de la clase Database y obtener la conexión PDO
        $conexion = (new Database())->getConnection();
        $this->MainModel = new MainModel($conexion);
    }

    public function getVehiculos($search = '')
    {
        if ($search == '') {
            // Si se encontró el perfiles, lo devuelve, de lo contrario, retorna un array vacío.
            $vechiculos = $this->MainModel->consultar('vehiculos');
            return $vechiculos ? $vechiculos : [];

        } else {
            $datos =
                [
                    'placa' => '%' . $search . '%',
                    'marca' => '%' . $search . '%',
                    'modelo' => '%' . $search . '%',
                    'color' => '%' . $search . '%',
                    'anio' => '%' . $search . '%',
                    'empresa' => '%' . $search . '%'
                ];
                $datos = $this->MainModel->limpiarArray($datos);

            $vechiculos = $this->MainModel->consultar('vehiculos', $datos," OR ");
            return $vechiculos ? $vechiculos : [];

        }

    }
    public function getVehiculo($vehiculo_id)
    {
     return $vehiculo = $this->MainModel->consultar('vehiculos',['vehiculo_id' => $vehiculo_id]);
     
    }
    public function getTipoVehiculo($search = '')
    {
        if ($search == '') {
            // Si se encontró el perfiles, lo devuelve, de lo contrario, retorna un array vacío.
            $vechiculos = $this->MainModel->consultar('tipo_vehiculos');
            
            return $vechiculos ? $vechiculos : [];

        } else {
            $datos =
                [
                    'tipo' => '%' . $search . '%'
                ];
                $datos = $this->MainModel->limpiarArray($datos);

            $vechiculos = $this->MainModel->consultar('tipo_vehiculos', $datos);
            return $vechiculos ? $vechiculos : [];

        }

    }
    public function crearVehiculo()
    {
        if (empty($_POST['placa']) || empty($_POST['marca']) || empty($_POST['modelo']) || empty($_POST['año']) || empty($_POST['color']) || empty($_POST['tipo_vehiculo'])
        || empty($_POST['empresa'])) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
    
        $placa = $_POST['placa'];
        $usuario_id = usuario_session();
        $estado = 1;
    
        // Verificar que la placa no exista
        $placa_existe = $this->MainModel->consultar('vehiculos', ['placa' => $placa]);
        if (count($placa_existe) > 0) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Error al registrar",
                "texto" => "El vehículo con placa $placa ya existe.",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
    
        // Recibir datos del formulario
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $anio = $_POST['año']; // Cambio año -> anio
        $color = $_POST['color'];
        $tipo = $_POST['tipo_vehiculo'];
        $empresa = $_POST['empresa'];
        $capacidad_carga = $_POST['capacidad_carga'] ?? null;
        $n_chasis = $_POST['n_chasis'] ?? null;
        $n_motor = $_POST['n_motor'] ?? null;
        $n_vin = $_POST['n_vin'] ?? null;
        $clase = $_POST['clase'] ?? null;
        $ruta_fotos = $_POST['ruta_fotos'] ?? null;
        $targeta_vence = $_POST['targeta_vence'] ?? null;
        $usuario_c = $usuario_id;
    
        // Datos a insertar
        $datos = [
            'placa' => $placa,
            'marca' => $marca,
            'modelo' => $modelo,
            'anio' => $anio, // Corregido
            'color' => $color,
            'tipo' => $tipo,
            'empresa' => $empresa,
            'capacidad_carga' => $capacidad_carga,
            'chasis' => $n_chasis,
            'n_motor' => $n_motor,
            'n_vin' => $n_vin,
            'clase' => $clase,
            'estado' => $estado,
            'km_actual' => 0,
            'km_anterior' => 0,
            'ruta_fotos' => $ruta_fotos,
            'targeta_vence' => $targeta_vence,
            'usuario_c' => $usuario_c
        ];
        $datos = $this->MainModel->limpiarArray($datos);
    
        // Insertar en la base de datos
        $resultado = $this->MainModel->insertar("vehiculos", $datos);
    
        if ($resultado > 0) {
            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "Registro exitoso",
                "texto" => "El vehículo con placa $placa se registró correctamente.",
                "icono" => "success",
                "url" => BASE_URL . 'vehiculos'
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "No se pudo registrar el vehículo.",
                "icono" => "error"
            ];
        }
        return json_encode($alerta);
    }

    // MODIFICAR ESTADO DE VEHICULO
    public function modificarEstadoVehiculo($vehiculo_id){
        $vehiculo = $this->getVehiculo($vehiculo_id);
        
        $estado = $vehiculo[0]['estado'];
        $placa = $vehiculo[0]['placa'];
         
         $tabla='vehiculos';
         
         $filtro = ['vehiculo_id' => $vehiculo_id];

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
                    "titulo" => "Vehiculo modificado",
                    "texto" => "El Vehiculo se desactivo",
                    "icono" => "success",
                    "url" => BASE_URL . 'vehiculos'
                ];

            }else{
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "Ocurrio un error",
                    "texto" => "No se pudo modificar el vehiculo, intente nuevamente.",
                    "icono" => "error",
                    "url" => BASE_URL . 'vehiculos'
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
                    "titulo" => "Vehiculo modificado",
                    "texto" => "El vehiculo se activo",
                    "icono" => "success",
                    "url" => BASE_URL . 'vehiculos'
                ];

            }else{
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "Ocurrio un error",
                    "texto" => "No se pudo modificar el vehiculo, intente nuevamente.",
                    "icono" => "error",
                    "url" => BASE_URL . 'vehiculos'
                ];
            }
        }
        return json_encode($alerta); // Retornar alerta en formato JSON
    }

    public function modificarVehiculo(){
        if (empty($_POST['placa']) || empty($_POST['marca']) || empty($_POST['modelo']) || empty($_POST['año']) || empty($_POST['color']) || empty($_POST['tipo_vehiculo'])
        || empty($_POST['empresa']) || empty($_POST['vehiculo_id'])) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        // Recibir datos del formulario
        $placa= $_POST['placa']; 
        $vehiculo_id= $_POST['vehiculo_id'];       
        $marca = $_POST['marca'];
        $modelo = $_POST['modelo'];
        $anio = $_POST['año']; // Cambio año -> anio
        $color = $_POST['color'];
        $tipo = $_POST['tipo_vehiculo'];
        $empresa = $_POST['empresa'];
        $capacidad_carga = $_POST['capacidad_carga'] ?? null;
        $n_chasis = $_POST['n_chasis'] ?? null;
        $n_motor = $_POST['n_motor'] ?? null;
        $n_vin = $_POST['n_vin'] ?? null;
        $clase = $_POST['clase'] ?? null;
        $ruta_fotos = $_POST['ruta_fotos'] ?? null;
        $targeta_vence = $_POST['targeta_vence'] ?? null;
        $usuario_c = usuario_session();

         // Datos para actualizar
         $datos = [
            
            'marca' => $marca,
            'modelo' => $modelo,
            'anio' => $anio, // Corregido
            'color' => $color,
            'tipo' => $tipo,
            'empresa' => $empresa,
            'capacidad_carga' => $capacidad_carga,
            'chasis' => $n_chasis,
            'n_motor' => $n_motor,
            'n_vin' => $n_vin,
            'clase' => $clase,
            'ruta_fotos' => $ruta_fotos,
            'targeta_vence' => $targeta_vence,
            'usuario_u' => $usuario_c
        ];

        $datos = $this->MainModel->limpiarArray($datos);

        $filtro= ['vehiculo_id' => $vehiculo_id];
        $resultado = $this->MainModel->actualizar('vehiculos',$datos,$filtro);
        // Verificar si la inserción fue exitosa
        if ($resultado != '') {
            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "Vehiculo modificado",
                "texto" => "El vehiculo $placa se ha modificado correctamente.",
                "icono" => "success",
                "url" => BASE_URL . 'vehiculos'
            ];
        }

        return json_encode($alerta); // Retornar alerta en formato JSON



    

    }
}


