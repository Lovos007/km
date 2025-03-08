<?php

namespace App\Controllers;
use App\Models\Database;
use App\Models\MainModel;
use App\Controllers\VehiculoController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



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
                    "fecha_uso" => FECHA_ACTUAL_CORTA
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

    public function CrearUso()
    {
        if (
            empty($_POST['tipo_de_uso']) || empty($_POST['tipo_vehiculo_vale']) || empty($_POST['fecha'])
            || empty($_POST['vehiculo']) || empty($_POST['conductor']) || empty($_POST['kilometraje'])
        ) {

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

        if ($kilometraje == $km_actual) {
            $nuevo_km_anterior = $km_anterior;
        }

        $datos =
            [
                "km_actual" => $nuevo_km_actual,
                "km_anterior" => $nuevo_km_anterior

            ];
        $filtro = [
            "vehiculo_id" => $vehiculo_id
        ];
        $resultado = $this->MainModel->actualizar("vehiculos", $datos, $filtro);
        if ($resultado > 0) {
            $datos = [
                "vehiculo_id" => $vehiculo_id,
                "nuevo_km" => $kilometraje,
                "km_actual" => $km_actual,
                "km_anterior" => $km_anterior,
                "tipo_uso" => $tipo_uso,
                "conductor_id" => $conductor_id,
                "numero_equipo" => $equipo,
                "ruta" => $ruta,
                "auxiliar_id1" => $auxiliar1,
                "auxiliar_id2" => $auxiliar2,
                "fecha_uso" => $fecha,
                "comentario" => $comentario,
                "usuario_c" => usuario_session(),
                "finalizado" => 0
            ];

            $resultado = $this->MainModel->insertar("km", $datos);
            if ($resultado > 0) {
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "Uso diario",
                    "texto" => "Se ingreso el uso diario correctamente.",
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

        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "no se modifico el km de la tabla vehiculo",
                "icono" => "error"
            ];
        }
        return json_encode($alerta);


    }

    public function borrarUso($km_id)
    {
        $uso = $this->getUso($km_id);
        $nuevo_km = $uso[0]["nuevo_km"];
        $actual_km = $uso[0]["km_actual"];

        $vehiculo_id = $uso[0]["vehiculo_id"];
        $diferencia_km = $nuevo_km - $actual_km;
        $vehiculo = new VehiculoController();
        $vehiculo = $vehiculo->getVehiculo($vehiculo_id);
        $km_actual_vehiculo = $vehiculo[0]["km_actual"];
        $km_anterior_vehiculo = $vehiculo[0]["km_anterior"];

        $nuevo_km_actual_vehiculo = $km_actual_vehiculo - $diferencia_km;
        $nuevo_km_anterior_vehiculo = $km_anterior_vehiculo - $diferencia_km;

        $datos =
            [
                "km_actual" => $nuevo_km_actual_vehiculo,
                "km_anterior" => $nuevo_km_anterior_vehiculo
            ];


        $filtro = [
            "vehiculo_id" => $vehiculo_id,
           
        ];
        $resultado = $this->MainModel->actualizar("vehiculos", $datos, $filtro);

        if ($resultado > 0) {
            $filtro = [
                "km_id" => $km_id,
                "finalizado" => 0
            ];
            $resultado = $this->MainModel->eliminar("km", $filtro);
            if ($resultado > 0) {
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "Uso diario",
                    "texto" => "Se elimino uso diario correctamente.",
                    "icono" => "success",
                    "url" => BASE_URL . 'uso-diario'
                ];
            } else {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "no se elimino el registro en la tabla km",
                    "icono" => "error"
                ];

            }
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "no se modifico el km de la tabla vehiculo",
                "icono" => "error"
            ];
        }
        return json_encode($alerta);
    }

    public function reporteUsoDiario()
    {
        if (!empty($_POST['fecha1']) || !empty($_POST['fecha2'])) {

            $tipo_uso_sql = "";
            if ($tipo_uso = $_POST["tipo_de_uso"] != "0") {
                $tipo_uso = $_POST["tipo_de_uso"];
                $tipo_uso_sql = " tipo_uso = '$tipo_uso' AND ";
            }

            $vehiculo_id_sql = "";
            if ($vehiculo_id = $_POST["vehiculo_id"] > 0) {
                $vehiculo_id = $_POST["vehiculo_id"];
                $vehiculo_id_sql = " vehiculo_id = $vehiculo_id AND ";
            }

            $conductor_id_sql = "";
            if ($conductor_id = $_POST["conductor_id"] > 0) {
                $conductor_id = $_POST["conductor_id"];
                $conductor_id_sql = " conductor_id = $conductor_id AND ";
            }


            $conductor_id = $_POST["conductor_id"];
            $fecha1 = $_POST["fecha1"];
            $fecha2 = $_POST["fecha2"];
            $condiciones = $tipo_uso_sql . $vehiculo_id_sql . $conductor_id_sql . "fecha_uso between '$fecha1' AND '$fecha2'";



            $lista = $this->MainModel->consultarConCondiciones("km", $condiciones);
            $accion = $_POST["accion"];

            if ($accion == "Exportar") {
                // Si hay resultados, generamos el archivo Excel (xlsx)
                if (!empty($lista)) {
                    // Crear una nueva hoja de cálculo
                    $spreadsheet = new Spreadsheet();
                    $sheet = $spreadsheet->getActiveSheet();

                    // Agregar encabezados
                    $sheet->setCellValue('A1', 'Fecha');
                    $sheet->setCellValue('B1', 'tipo de uso');
                    $sheet->setCellValue('C1', 'Placa');
                    $sheet->setCellValue('D1', 'Motorista');
                    $sheet->setCellValue('E1', 'Ruta');
                    $sheet->setCellValue('F1', 'Equipo');
                    $sheet->setCellValue('G1', 'km ingresado');
                    $sheet->setCellValue('H1', 'km de ultimo recorrido');
                    $sheet->setCellValue('I1', 'Cantidad de km');


                    // Escribir los datos de la consulta en las filas
                    $row = 2; // Empezamos desde la fila 2 (debajo de los encabezados)
                    foreach ($lista as $resultado) {
                        $diferencia = $resultado["nuevo_km"] - $resultado["km_actual"];

                        $sheet->setCellValue('A' . $row, $resultado['fecha_uso']);
                        $sheet->setCellValue('B' . $row, $resultado['tipo_uso']);
                        $sheet->setCellValue('C' . $row, obtenerPlaca($resultado['vehiculo_id']));
                        $sheet->setCellValue('D' . $row, obtenerNombreConductor($resultado['conductor_id']));
                        $sheet->setCellValue('E' . $row, $resultado['ruta']);
                        $sheet->setCellValue('F' . $row, $resultado['numero_equipo']);
                        $sheet->setCellValue('G' . $row, $resultado['nuevo_km']);
                        $sheet->setCellValue('H' . $row, $resultado['km_actual']);
                        $sheet->setCellValue('I' . $row, $diferencia);
                        $row++;
                    }

                    // Definir el nombre del archivo
                    $nombreArchivo = 'resultados_consulta_' . date('Y-m-d_H-i-s') . '.xlsx';

                    // Configurar las cabeceras para la descarga del archivo Excel
                    header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; charset=utf-8');
                    header('Content-Disposition: attachment;filename="' . $nombreArchivo . '"');
                    header('Cache-Control: max-age=0');

                    // Crear el escritor de Excel
                    $writer = new Xlsx($spreadsheet);

                    // Enviar el archivo al navegador
                    $writer->save('php://output');
                    exit;  // Detener la ejecución después de la descarga
                } else {
                    echo "<h2>No se encontraron resultados para exportar.</h2>";
                }
            } else {
                return $lista;
            }

        } else {
            return [];
        }
    }

    public function reporteConsolidado()
    {
        $condiciones = "fecha_uso ='" . FECHA_ACTUAL_CORTA . "'";
        $lista = $this->MainModel->consultarConCondiciones("km", $condiciones);

        $condiciones2 = [
            'estado' => 1
        ];

        $cantidad_vehiculos = count($this->MainModel->consultar('vehiculos', $condiciones2));
        $ruta_venta = 0;
        $ruta_despacho = 0;
        $entrega_especial = 0;
        $area_de_mantenimiento = 0;
        $en_taller = 0;

        foreach ($lista as $uso) {
            // switch ($uso["tipo_uso"]) {
            //     case "RUTA DE VENTA":
            //         $ruta_venta++;
            //         break;
            //     case "RUTA DE DESPACHO":
            //         $ruta_despacho++;
            //         break;
            //     case "ENTREGA ESPECIAL":
            //         $entrega_especial++;
            //         break;
            //     case "AREA DE MANTENIMIENTO":
            //         $area_de_mantenimiento++;
            //         break;
            //     case "EN TALLER":
            //         $en_taller++;
            //         break;
            // }

            if ($uso["tipo_uso"] == "RUTA DE VENTA" && $uso["finalizado"] == 0) {
                $ruta_venta++;
            } elseif ($uso["tipo_uso"] == "RUTA DE DESPACHO" && $uso["finalizado"] == 0) {
                $ruta_despacho++;
            } elseif ($uso["tipo_uso"] == "ENTREGA ESPECIAL" && $uso["finalizado"] == 0) {
                $entrega_especial++;
            } elseif ($uso["tipo_uso"] == "AREA DE MANTENIMIENTO" && $uso["finalizado"] == 0) {
                $area_de_mantenimiento++;
            } elseif ($uso["tipo_uso"] == "EN TALLER" && $uso["finalizado"] == 0) {
                $en_taller++;
            }
        }



        $cantidad_vehiculos_sin_uso = $cantidad_vehiculos - $ruta_venta - $ruta_despacho - $entrega_especial - $area_de_mantenimiento - $en_taller;

        // Crear el array con los resultados
        $resultado = [
            "vehiculos_en_uso" => $cantidad_vehiculos,
            "ruta_venta" => $ruta_venta,
            "ruta_despacho" => $ruta_despacho,
            "entrega_especial" => $entrega_especial,
            "area_de_mantenimiento" => $area_de_mantenimiento,
            "en_taller" => $en_taller,
            "sin_uso" => $cantidad_vehiculos_sin_uso
        ];

        return $resultado;
    }

    public function FinalizarUso($km_id)
    {
        $resultado = $this->MainModel-> actualizar('km',['finalizado' => 1],['km_id' => $km_id]);

        if ($resultado > 0) {
            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "Uso diario",
                "texto" => "Se Finalizo uso diario correctamente.",
                "icono" => "success",
                "url" => BASE_URL . 'uso-diario'
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "no se finalizo el registro en la tabla km",
                "icono" => "error"
            ];
        }
        return json_encode($alerta);

    }



}