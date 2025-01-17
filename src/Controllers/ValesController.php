<?php

namespace App\Controllers;
use App\Models\Database;
use App\Models\MainModel;
use App\Controllers\VehiculoController;



class ValesController
{
    private $MainModel;

    public function __construct()
    {
        // Crear una instancia de la clase Database y obtener la conexión PDO
        $conexion = (new Database())->getConnection();
        $this->MainModel = new MainModel($conexion);
    }

    public function getVales($search = '')
    {
        if ($search == '') {
            $vales = $this->MainModel->consultar('vales', [], " AND ", " ORDER BY fecha_c desc");
            return $vales ? $vales : [];

        } else {
            $datos =
                [
                    'numero' => '%' . $search . '%',
                    'serie' => '%' . $search . '%'
                ];
            $datos = $this->MainModel->limpiarArray($datos);
            $vales = $this->MainModel->consultar('vales', $datos, " OR ");
            return $vales ? $vales : [];

        }
    }
    public function getValesDetalles($search = '')
    {
        if ($search == '') {
            $vales = $this->MainModel->consultar('vales', ["estado" => 1], " AND ", " ORDER BY fecha_c desc ");
            return $vales ? $vales : [];

        } else {
            $datos =
                [
                    'numero' => '%' . $search . '%',
                    'serie' => '%' . $search . '%',
                    'estado' => 1
                ];
            $datos = $this->MainModel->limpiarArray($datos);
            $vales = $this->MainModel->consultar('vales', $datos, " OR ");
            return $vales ? $vales : [];

        }
    }
    public function getVale($vale_id)
    {
        return $vale = $this->MainModel->consultar('vales', ['vale_id' => $vale_id]);

    }

    public function getDetallesVale($vale_id)
    {
        return $detalle = $this->MainModel->consultar('vale_detalle', ['vale_id' => $vale_id]);

    }

    public function getDetalleVale($vale_detalle_id)
    {
        return $detalle = $this->MainModel->consultar('vale_detalle', ['vale_detalle_id' => $vale_detalle_id]);

    }

    public function crearVale()
    {
        if (empty($_POST['numero']) || empty($_POST['serie']) || empty($_POST['responsable'])) {

            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios del vale",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        // Recibir datos del formulario
        $numero = $_POST['numero'];
        $serie = $_POST['serie'] ?? null;
        $responsable = $_POST['responsable'];
        $usuario_c = usuario_session();
        $estado = 1;


        // Datos a insertar
        $datos = [
            'numero' => $numero,
            'serie' => $serie,
            'responsable_id' => $responsable,
            'estado' => $estado,
            'usuario_c' => $usuario_c
        ];
        $datos = $this->MainModel->limpiarArray($datos);

        // Insertar en la base de datos
        $resultado = $this->MainModel->insertar("vales", $datos);

        if ($resultado > 0) {
            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "Registro exitoso",
                "texto" => "El vale  $numero se registró correctamente.",
                "icono" => "success",
                "url" => BASE_URL . 'vales'
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "No se pudo registrar el vale.",
                "icono" => "error"
            ];
        }
        return json_encode($alerta);
    }

    public function modificarVale()
    {
        if (empty($_POST['numero']) || empty($_POST['serie']) || empty($_POST['responsable']) || empty($_POST['vale_id'])) {

            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        // Recibir datos del formulario
        $vale_id = $_POST['vale_id'];
        $numero = $_POST['numero'];
        $serie = $_POST['serie'];
        $responsable = $_POST['responsable'];
        $usuario_c = usuario_session();
        $estado = 1;


        // Datos a actualizar
        $datos = [
            'numero' => $numero,
            'serie' => $serie,
            'responsable_id' => $responsable,
            'usuario_u' => $usuario_c
        ];
        $filtro = [
            "vale_id" => $vale_id
        ];
        $datos = $this->MainModel->limpiarArray($datos);
        $filtro = $this->MainModel->limpiarArray($filtro);

        // actualizar en la base de datos
        $resultado = $this->MainModel->actualizar("vales", $datos, $filtro);

        if ($resultado != "") {
            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "Registro modificado",
                "texto" => "El vale  $numero se modifico correctamente.",
                "icono" => "success",
                "url" => BASE_URL . 'vales'
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "No se pudo modificar el vale.",
                "icono" => "error"
            ];
        }
        return json_encode($alerta);
    }


    public function modificarEstadovale($vale_id)
    {
        $vale = $this->getVale($vale_id);

        $estado = $vale[0]['estado'];

        $tabla = 'vales';

        $filtro = ['vale_id' => $vale_id];

        if ($estado == 1) {
            $datos = [
                'estado' => 2,
                'usuario_u' => usuario_session()
            ];
            $datos = $this->MainModel->limpiarArray($datos);

            $resultado = $this->MainModel->actualizar($tabla, $datos, $filtro);
            if ($resultado > 0) {
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "vale modificado",
                    "texto" => "El vale ahora esta cerrado",
                    "icono" => "success",
                    "url" => BASE_URL . 'vales'
                ];

            } else {
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "Ocurrio un error",
                    "texto" => "No se pudo modificar el vale, intente nuevamente.",
                    "icono" => "error",
                    "url" => BASE_URL . 'vales'
                ];
            }

        } else {
            $datos = [
                'estado' => 1,
                'usuario_u' => usuario_session()
            ];
            $datos = $this->MainModel->limpiarArray($datos);

            $resultado = $this->MainModel->actualizar($tabla, $datos, $filtro);
            if ($resultado > 0) {
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "vale modificado",
                    "texto" => "El vale se activo nuevamente",
                    "icono" => "success",
                    "url" => BASE_URL . 'vales'
                ];

            } else {
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "Ocurrio un error",
                    "texto" => "No se pudo modificar el vale, intente nuevamente.",
                    "icono" => "error",
                    "url" => BASE_URL . 'vales'
                ];
            }
        }
        return json_encode($alerta); // Retornar alerta en formato JSON
    }

    public function CrearDetalleVale()
    {
        if (
            empty($_POST['vale_id']) || empty($_POST['numero']) || empty($_POST['tipo_vehiculo_vale'])
            || empty($_POST['kilometraje']) || empty($_POST['tipo_gasto']) || empty($_POST['cantidad'])
            || empty($_POST['monto']) || empty($_POST['fecha'])
            || empty($_POST['vehiculo']) || empty($_POST['conductor'])
        ) {

            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios del vale ",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        // Recibir datos del formulario
        $vale_id = $_POST['vale_id'];
        $numero = $_POST['numero'];
        $tipo_vehiculo_vale = $_POST['tipo_vehiculo_vale'];
        $vehiculo_id = $_POST['vehiculo'];
        $conductor_id = $_POST['conductor'];
        $auxiliar1 = $_POST['auxiliar1'] ?? null;
        $auxiliar2 = $_POST['auxiliar2'] ?? null;
        $kilometraje = $_POST['kilometraje'];
        $tipo_gasto = $_POST['tipo_gasto'];
        $cantidad = $_POST['cantidad'];
        $monto = $_POST['monto'] ?? null;
        $fecha = $_POST['fecha'];
        $comentario = $_POST['comentario'] ?? null;
        $usuario_c = usuario_session();
        $estado = 1;

        $VehiculoController = new VehiculoController();
        $vehiculo = $VehiculoController->getVehiculo($vehiculo_id);
        $placa = $vehiculo[0]["placa"];
        $km_actual = $vehiculo[0]["km_actual"];

        if ($km_actual > $kilometraje) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "Kilometraje ingresado menor al km actual $km_actual km",
                "icono" => "error"
            ];
            return json_encode($alerta);

        }

        if (isset($_FILES['fotografia']) && $_FILES['fotografia']["name"] != "") {
            $mimeType = mime_content_type($_FILES['fotografia']['tmp_name']);
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

            if (!in_array($mimeType, $allowedTypes)) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Error: Solo se permiten imágenes JPEG, PNG o GIF.",
                    "icono" => "error"
                ];
                return json_encode($alerta);

            }
            $maxSize = 2 * 1024 * 1024; // 2 MB
            if ($_FILES['fotografia']['size'] > $maxSize) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Error: La imagen es demasiado grande. Tamaño máximo: 2 MB.",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = pathinfo($_FILES['fotografia']['name'], PATHINFO_EXTENSION);

            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Error: Extensión de archivo no permitida.",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }

            if (!getimagesize($_FILES['fotografia']['tmp_name'])) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Error: El archivo no es una imagen válida.",
                    "icono" => "error"
                ];
                return json_encode($alerta);

            }

        }

        $url_base_foto = "";

        if (isset($_FILES['fotografia']) && $_FILES['fotografia']["name"] != "") {

            $uploadDir = BASE_URL_ARCHIVOS;
            $newFileName = $fecha . "_" . $placa . "_" . $numero ."_".$tipo_gasto. "_img_." . $fileExtension;
            $uploadPath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['fotografia']['tmp_name'], $uploadPath)) {
                $url_base_foto = $newFileName;
            } else {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "La imagen no se guardo",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
        }


        $datos = [
            'vale_id' => $vale_id,
            'numero' => $numero,
            'tipo_vehiculo' => $tipo_vehiculo_vale,
            'vehiculo_id' => $vehiculo_id,
            'conductor_id' => $conductor_id,
            'kilometraje' => $kilometraje,
            'kilometraje_anterior' => $km_actual,
            'tipo_gasto' => $tipo_gasto,
            'cantidad_gasto' => $cantidad,
            'monto_gasto' => $monto,
            'auxiliar_id1' => $auxiliar1,
            'auxiliar_id2' => $auxiliar2,
            'ruta_foto' => $url_base_foto,
            'fecha' => $fecha,
            'comentario' => $comentario,
            'usuario_c' => usuario_session()
        ];

        $resultado = $this->MainModel->insertar("vale_detalle", $datos);


        if ($resultado > 0) {
           
            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "Detalle de vale",
                "texto" => "se ingreso el detalle de vale correctamente.",
                "icono" => "success",
                "url" => BASE_URL . 'ingresoVale?d=' . base64_encode($vale_id).'&n=n'
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "No se pudo registrar el detalle del vale.",
                "icono" => "error"
            ];
        }

        return json_encode($alerta);

    }
    public function borrarDetalleVale($vale_detalle_id)
    {
        $valeDetalle = $this->getDetalleVale($vale_detalle_id);

        $vale_id = $valeDetalle[0]['vale_id'];
        $vehiculo_id = $valeDetalle[0]['vehiculo_id'];
        $kilometraje_vale = $valeDetalle[0]['kilometraje'];
        $kilometraje_anterior = $valeDetalle[0]['kilometraje_anterior'];
        $foto = $valeDetalle[0]['ruta_foto'];
        $diferencia_km = $kilometraje_vale - $kilometraje_anterior;

        $vale = $this->getVale($vale_id);
        $estado = $vale[0]['estado'];
        if ($estado > 1) {
            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "Ha ocurrido un error",
                "texto" => "No se puede borrar, el vale esta cerrado",
                "icono" => "error",
                "url" => BASE_URL . 'valesDetalle'
            ];
            return json_encode($alerta); // Retornar alerta en formato JSON
        }

        $VehiculoController = new VehiculoController();           
        

        $condiciones = [
            "vale_detalle_id" => $vale_detalle_id
        ];


        $resultado = $this->MainModel->eliminar("vale_detalle", $condiciones);
        if ($resultado > 0) {
            if ($foto != "") {
                $imagePath = BASE_URL_ARCHIVOS . $foto; // Ruta completa de la imagen
                if (file_exists($imagePath)) { // Verifica si el archivo existe
                    if (unlink($imagePath)) { // Intenta eliminar el archivo

                    }
                }
            }           

            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "vale modificado",
                "texto" => "El detalle se borro",
                "icono" => "success",
                "url" => BASE_URL . 'ingresoVale?d=' . base64_encode($vale_id).'&n=n'
            ];
            return json_encode($alerta); // Retornar alerta en formato JSON    


        } else {
            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "Error inesperado",
                "texto" => "No se pudo eliminar el detalle",
                "icono" => "error",
                "url" => BASE_URL . 'detallesV?d=' . base64_encode($vale_id)
            ];
            return json_encode($alerta); // Retornar alerta en formato JSON      


        }
    }

    public function borrarTodosLosDetalles($vale_id){
        $filtro=[
            "vale_id"=> $vale_id
        ];
     $detalles_vale = $this->MainModel->consultar('vale_detalle',$filtro);

     foreach ($detalles_vale as $detalle) {
        $this->borrarDetalleVale($detalle["vale_detalle_id"]);
     }
      
    }
    public function TablaResumenVale($vale_id){

        return $this->MainModel->TablaResumenVale($vale_id);
        
    }

    public function moverDetallesVale()
    {
        if (empty($_POST['numero'])|| empty($_POST['nuevovale']) || $_POST['nuevovale']<1 ||empty($_POST['vale_id'])) {

            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }
        $viejovaleid = $_POST['vale_id'];
        $nuevovaleid = $_POST['nuevovale'];

        $datos=[
            "vale_id"=> $nuevovaleid
        ];
        $filtro=[
            "vale_id"=> $viejovaleid
        ];

       $resultado= $this->MainModel->actualizar("vale_detalle",$datos,$filtro);
       if ($resultado > 0) {
        $alerta = [
            "tipo" => "simpleRedireccion",
            "titulo" => "vale modificado",
            "texto" => "Los detalles se movieron",
            "icono" => "success",
            "url" => BASE_URL . 'valesDetalle2'
        ];

    } else {
        $alerta = [
            "tipo" => "simpleRedireccion",
            "titulo" => "Ocurrio un error",
            "texto" => "No se pudo modificar el vale, intente nuevamente.",
            "icono" => "error",
            "url" => BASE_URL . 'valesDetalle2'
        ];
    }
    return json_encode($alerta); // Retornar alerta en formato JSON 
    
    }



}

