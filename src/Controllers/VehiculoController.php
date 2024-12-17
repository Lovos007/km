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
                    'placa' => '%' . $search . '%'
                ];

            $vechiculos = $this->MainModel->consultar('vehiculos', $datos);
            return $vechiculos ? $vechiculos : [];

        }

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

            $vechiculos = $this->MainModel->consultar('tipo_vehiculos', $datos);
            return $vechiculos ? $vechiculos : [];

        }

    }
    public function crearVehiculo()
    {
        if (empty($_POST['placa']) || empty($_POST['marca']) || empty($_POST['modelo']) || empty($_POST['año']) || empty($_POST['color']) || empty($_POST['tipo_vehiculo'])) {
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
    
}


