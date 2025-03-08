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

    // public function getVehiculos($search = '', $condiciones = [])
    // {
    //     if ($search === '') {
    //         // Si no hay búsqueda, filtra solo por las condiciones adicionales.
    //         $vehiculos = $this->MainModel->consultar('vehiculos', $condiciones);
    //         return $vehiculos ?: []; // Retorna el resultado o un array vacío.
    //     } else {
    //         // Prepara los datos para la búsqueda con operadores LIKE
    //         $datos = [
    //             'placa' => '%' . $search . '%',
    //             'marca' => '%' . $search . '%',
    //             'modelo' => '%' . $search . '%',
    //             'color' => '%' . $search . '%',
    //             'anio' => '%' . $search . '%',
    //             'empresa' => '%' . $search . '%'
    //         ];
    
    //         // Limpia el array de datos
    //         $datos = $this->MainModel->limpiarArray($datos);
    
    //         // Realiza la consulta con el operador OR
    //         $vehiculos = $this->MainModel->consultar('vehiculos', $datos, " OR ");
    //         return $vehiculos ?: []; // Retorna el resultado o un array vacío.
    //     }
    // }

    public function getVehiculos($search = '', $condiciones = [], $pagina = 1, $registrosPorPagina = 10)
    {
        // Calcular el offset
        $offset = ($pagina - 1) * $registrosPorPagina;
        $limit = "LIMIT $offset, $registrosPorPagina";
    
        if ($search === '') {
            // Si no hay búsqueda, filtra solo por las condiciones adicionales.
            $vehiculos = $this->MainModel->consultar('vehiculos', $condiciones, " AND ", '', $limit);
            $totalRegistros = $this->MainModel->contarRegistros('vehiculos', $condiciones);
        } else {
            // Prepara los datos para la búsqueda con operadores LIKE
            $datos = [
                'placa' => '%' . $search . '%',
                'marca' => '%' . $search . '%',
                'modelo' => '%' . $search . '%',
                'color' => '%' . $search . '%',
                'anio' => '%' . $search . '%',
                'empresa' => '%' . $search . '%'
            ];
    
            // Limpia el array de datos
            $datos = $this->MainModel->limpiarArray($datos);
    
            // Realiza la consulta con el operador OR y paginación
            $vehiculos = $this->MainModel->consultar('vehiculos', $datos, " OR ", '', $limit);
            $totalRegistros = $this->MainModel->contarRegistros('vehiculos', $datos, " OR ");
        }
    
        // Retorna los resultados y el total de registros
        return [
            'resultados' => $vehiculos ?: [],
            'totalRegistros' => $totalRegistros
        ];
    }

    
    public function getVehiculosCondicion($search = '',$condiciones="")
    {
        if ($search == '') {
            // Si se encontró el perfiles, lo devuelve, de lo contrario, retorna un array vacío.
            $vechiculos = $this->MainModel->consultarConCondiciones('vehiculos',$condiciones);
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

        if (isset($_FILES['foto1']) && $_FILES['foto1']["name"] != "") {
            $mimeType = mime_content_type($_FILES['foto1']['tmp_name']);
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
            if ($_FILES['foto1']['size'] > $maxSize) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Error: La imagen es demasiado grande. Tamaño máximo: 2 MB.",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = pathinfo($_FILES['foto1']['name'], PATHINFO_EXTENSION);

            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Error: Extensión de archivo no permitida.",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }

            if (!getimagesize($_FILES['foto1']['tmp_name'])) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Error: El archivo no es una imagen válida.",
                    "icono" => "error"
                ];
                return json_encode($alerta);

            }

        }

        $url_base_foto1 = "";

        if (isset($_FILES['foto1']) && $_FILES['foto1']["name"] != "") {

            $uploadDir = BASE_URL_ARCHIVOS_TARGETAS;
            $newFileName = $placa . "_img_." . $fileExtension;
            $uploadPath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['foto1']['tmp_name'], $uploadPath)) {
                $url_base_foto1 = $newFileName;
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
        // FIN VALIDACION DE IMAGEN  TARGETA DE CIRCULACION
    
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
            'foto_targeta' => $url_base_foto1,
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
        $placa= $_POST['placa']; 
        $vehiculo_id= $_POST['vehiculo_id'];  

        // Verificar que la placa no exista
        $placa_existe = $this->MainModel->consultar('vehiculos', ['placa' => $placa]);
        if (count($placa_existe) > 0) {
            if ($placa_existe[0]["vehiculo_id"]!=$vehiculo_id) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Error al registrar",
                    "texto" => "El vehículo con placa $placa ya existe.",
                    "icono" => "error"
                ];
                return json_encode($alerta);
                
            }
            $sql_imagen1 = $placa_existe[0]['foto_targeta'];
      
        }
        
        if (isset($_FILES['foto1']) && $_FILES['foto1']["name"] != "") {
            $mimeType = mime_content_type($_FILES['foto1']['tmp_name']);
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
            if ($_FILES['foto1']['size'] > $maxSize) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Error: La imagen es demasiado grande. Tamaño máximo: 2 MB.",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = pathinfo($_FILES['foto1']['name'], PATHINFO_EXTENSION);

            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Error: Extensión de archivo no permitida.",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }

            if (!getimagesize($_FILES['foto1']['tmp_name'])) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Error: El archivo no es una imagen válida.",
                    "icono" => "error"
                ];
                return json_encode($alerta);

            }

        }

        $url_base_foto1 = "";

        if (isset($_FILES['foto1']) && $_FILES['foto1']["name"] != "") {

            $uploadDir = BASE_URL_ARCHIVOS_TARGETAS;
            $newFileName = $placa . "_img_." . $fileExtension;
            $uploadPath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['foto1']['tmp_name'], $uploadPath)) {
                $url_base_foto1 = $newFileName;
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
        // FIN VALIDACION DE IMAGEN  TARGETA DE CIRCULACION
        if ($url_base_foto1=="") {
            $url_base_foto1=$sql_imagen1;
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
        $usuario_c = usuario_session();

         // Datos para actualizar
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
            'ruta_fotos' => $ruta_fotos,
            'foto_targeta' => $url_base_foto1,
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


