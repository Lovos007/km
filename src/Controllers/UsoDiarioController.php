<?php

namespace App\Controllers;
use App\Models\Database;
use App\Models\MainModel;
use App\Controllers\VehiculoController;



class UsoDiarioController
{
    private $MainModel;

    public function __construct()
    {
        // Crear una instancia de la clase Database y obtener la conexión PDO
        $conexion = (new Database())->getConnection();
        $this->MainModel = new MainModel($conexion);
    }

    public function getUsos($search = '')
    {

        if ($search == '') {
            $datos =
                [
                    "fecha_uso"=>FECHA_ACTUAL_CORTA
                ];
               
            $vales = $this->MainModel->consultar('km', $datos, " AND ", " ORDER BY fecha_uso desc");
            return $vales ? $vales : [];

        } else {
            
               
            $datos =
                [
                    'id_conductor' => '%' . $search . '%',
                    'id_vehiculo' => '%' . $search . '%'
                ];
            $datos = $this->MainModel->limpiarArray($datos);
            $vales = $this->MainModel->consultar('km', $datos, " OR ");
            return $vales ? $vales : [];

        }
    }
    public function getUso($km_id)
    {
        return $vale = $this->MainModel->consultar('km', ['km_id' => $km_id]);

    }

    public function CrearUso () {
        if (empty($_POST['tipo_de_uso']) || empty($_POST['tipo_vehiculo_vale']) || empty($_POST['fecha']) 
            || empty($_POST['vehiculo']) || empty($_POST['conductor']) || empty($_POST['kilometraje'])) {

            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        //recibir formulario
        $tipo_uso = $_POST['tipo_de_uso'];
        $tipo_vehiculo_vale = $_POST['tipo_vehiculo_vale'];      
        $ruta = $_POST['ruta'] ?? null;
        $equipo = $_POST['equipo'] ?? null;
        $comentario = $_POST['comentario'] ?? null;
        $vehiculo_id = $_POST['vehiculo'];
        $conductor_id = $_POST['conductor'];
        $auxiliar1 = $_POST['auxiliar1'] ?? null;
        $auxiliar2 = $_POST['auxiliar2'] ?? null;
        $kilometraje = $_POST['kilometraje'];
        $fecha = $_POST['fecha'];

        $VehiculoController = new VehiculoController();
        $vehiculo = $VehiculoController->getVehiculo($vehiculo_id);
        $km_actual = $vehiculo[0]["km_actual"];
        $km_anterior = $vehiculo[0]["km_anterior"];

        if ($kilometraje < $km_actual) {

        $alerta = [
            "tipo" => "simple",
            "titulo" => "Ocurrió un error inesperado",
            "texto" => "Kilometraje ingresado menor al actual, km actual $km_actual",
            "icono" => "error"
        ];
        return json_encode($alerta);
    }
    $nuevo_km_actual = $kilometraje;
    $nuevo_km_anterior = $km_actual;
    $datos=
    [
        "km_actual" => $nuevo_km_actual,
        "km_anterior" => $nuevo_km_anterior
        
    ];
    $filtro=[
        "vehiculo_id" => $vehiculo_id 
    ];
    $resultado = $this->MainModel->actualizar("vehiculos",$datos,$filtro);
    if ($resultado > 0) {
        $datos=[
            "vehiculo_id" => $vehiculo_id,
            "nuevo_km" => $kilometraje,
            "km_actual" => $km_actual,
            "km_anterior" => $km_anterior,
            "tipo_uso" => $tipo_uso,
            "conductor_id" =>$conductor_id,
            "numero_equipo" => $equipo,
            "ruta" => $ruta,
            "auxiliar_id1"=>$auxiliar1,
            "auxiliar_id2"=>$auxiliar2,
            "fecha_uso"=> $fecha,
            "comentario"=> $comentario,
            "usuario_c" => usuario_session()
        ];

        $resultado = $this->MainModel->insertar("km",$datos);
        if ($resultado > 0) {
            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "Detalle de vale",
                "texto" => "Se el uso diario correctamente.",
                "icono" => "success",
                "url" => BASE_URL . 'uso-diario' 
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "no se ingreso el nuevo km en la tabla km",
                "icono" => "error"
            ];
            
            
        }

    }else{
        $alerta = [
            "tipo" => "simple",
            "titulo" => "Ocurrió un error inesperado",
            "texto" => "no se modifico el km de la tabla vehiculo",
            "icono" => "error"
        ];
    }
    return json_encode($alerta);
    

    }

}