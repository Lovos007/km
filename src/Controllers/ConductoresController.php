<?php

namespace App\Controllers;
use App\Models\Database;
use App\Models\MainModel;

final class ConductoresController
{
    private $MainModel;

    public function __construct()
    {
        // Crear una instancia de la clase Database y obtener la conexión PDO
        $conexion = (new Database())->getConnection();
        $this->MainModel = new MainModel($conexion);
    }

    // public function getConductores($search = '')
    // {
    //     if ($search == '') {
    //         // Si se encontró el perfiles, lo devuelve, de lo contrario, retorna un array vacío.
    //         $conductores = $this->MainModel->consultar('conductores');
    //         return $conductores ? $conductores : [];

    //     } else {
    //         $datos =
    //             [
    //                 'nombre' => '%' . $search . '%',
    //                 'dui' => '%' . $search . '%',
    //                 'empresa' => '%' . $search . '%'
    //             ];
    //         $datos = $this->MainModel->limpiarArray($datos);

    //         $conductores = $this->MainModel->consultar('conductores', $datos, " OR ");
    //         return $conductores ? $conductores : [];

    //     }
    // }
    public function getConductores($search = '', $condiciones = [], $pagina = 1, $registrosPorPagina = 10)
{
    // Calcular el offset
    $offset = ($pagina - 1) * $registrosPorPagina;
    $limit = "LIMIT $offset, $registrosPorPagina";

    if ($search === '') {
        // Si no hay búsqueda, filtra solo por las condiciones adicionales.
        $conductores = $this->MainModel->consultar('conductores', $condiciones, " AND ", '', $limit);
        $totalRegistros = $this->MainModel->contarRegistros('conductores', $condiciones);
    } else {
        // Prepara los datos para la búsqueda con operadores LIKE
        $datos = [
            'nombre' => '%' . $search . '%',
            'dui' => '%' . $search . '%',
            'empresa' => '%' . $search . '%'
        ];

        // Limpia el array de datos
        $datos = $this->MainModel->limpiarArray($datos);

        // Realiza la consulta con el operador OR y paginación
        $conductores = $this->MainModel->consultar('conductores', $datos, " OR ", '', $limit);
        $totalRegistros = $this->MainModel->contarRegistros('conductores', $datos, " OR ");
    }

    // Retorna los resultados y el total de registros
    return [
        'resultados' => $conductores ?: [],
        'totalRegistros' => $totalRegistros
    ];
}
    public function getConductoresCondicion($search = '', $condiciones = "")
    {
        if ($search == '') {
            // Si se encontró el perfiles, lo devuelve, de lo contrario, retorna un array vacío.
            $vechiculos = $this->MainModel->consultarConCondiciones('conductores', $condiciones);
            return $vechiculos ? $vechiculos : [];

        } else {
            $datos =
                [
                    'nombre' => '%' . $search . '%',
                    'dui' => '%' . $search . '%'
                ];
            $datos = $this->MainModel->limpiarArray($datos);

            $vechiculos = $this->MainModel->consultar('vehiculos', $datos, " OR ");
            return $vechiculos ? $vechiculos : [];

        }

    }
    public function getConductor($conductor_id)
    {
        return $conductor = $this->MainModel->consultar('conductores', ['conductor_id' => $conductor_id]);

    }
    public function getTipoLicencia($search = '')
    {
        if ($search == '') {
            // Si se encontró el perfiles, lo devuelve, de lo contrario, retorna un array vacío.
            $licencias = $this->MainModel->consultar('tipo_licencias');

            return $licencias ? $licencias : [];

        } else {
            $datos =
                [
                    'tipo_licencia_id' => $search
                ];
            $datos = $this->MainModel->limpiarArray($datos);

            $licencias = $this->MainModel->consultar('tipo_licencias', $datos);
            return $licencias ? $licencias : [];

        }

    }


    public function crearConductor()
    {
        if (empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['dui']) || empty($_POST['empresa'])) {

            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        if ($_POST['tipo_licencia1'] == 0) {

            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "Selecciona un tipo de licencia 1",
                "icono" => "error"
            ];

            return json_encode($alerta);
        }

        $dui = $_POST['dui'];



        // INICIO DE PERMISO
 $permiso = new PermisoController();
 //PERMISO ID 15  modificar estado del vehiculo
 $numero_permiso = 15;
 $v_permiso = $permiso->getPermiso(usuario_session(), $numero_permiso, $dui, 1);
 // SI NO TIENE PERMISO


 if ($v_permiso == false) {
    $alerta = [
        "tipo" => "simpleRedireccion",
        "titulo" => "Error de permisos",
        "texto" => "Necesitas el permiso # " . $numero_permiso,
        "icono" => "error",
        "url" => BASE_URL . 'home'
    ];
    return json_encode($alerta); // Terminar ejecución con alerta de error

}
// FIN DE PERMISO



        // Verificar que la conductor no exista
        $conductor_existe = $this->MainModel->consultar('conductores', ['dui' => $dui]);
        if (count($conductor_existe) > 0) {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Error al registrar",
                "texto" => "El conductor con dui $dui ya existe.",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        // Recibir datos del formulario
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $tipo_licencia1 = $_POST['tipo_licencia1'];
        $licencia1 = $_POST['licencia1'] ?? null;
        $tipo_licencia2 = $_POST['tipo_licencia2'] ?? null;
        $licencia2 = $_POST['licencia2'] ?? null;
        $empresa = $_POST['empresa'];
        $numero_contacto = $_POST['numero'] ?? null;
        $correo = $_POST['correo'] ?? null;
        $refrenda1 = $_POST['refrenda1'] ?? null;
        $refrenda2 = $_POST['refrenda2'] ?? null;
        $usuario_c = usuario_session();
        $estado = 1;

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

            $uploadDir = BASE_URL_ARCHIVOS_LICENCIAS;
            $newFileName = $nombre . "_" . $dui . "_" . "L1" . "_img_." . $fileExtension;
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
        // FIN VALIDACION DE IMAGEN LICENCIA 1

        if (isset($_FILES['foto2']) && $_FILES['foto2']["name"] != "") {
            $mimeType = mime_content_type($_FILES['foto2']['tmp_name']);
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

            if (!in_array($mimeType, $allowedTypes)) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Error: Solo se permiten imágenes JPEG, PNG o GIF. -2",
                    "icono" => "error"
                ];
                return json_encode($alerta);

            }
            $maxSize = 2 * 1024 * 1024; // 2 MB
            if ($_FILES['foto2']['size'] > $maxSize) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Error: La imagen es demasiado grande. Tamaño máximo: 2 MB. -2",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = pathinfo($_FILES['foto2']['name'], PATHINFO_EXTENSION);

            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Error: Extensión de archivo no permitida. -2",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }

            if (!getimagesize($_FILES['foto2']['tmp_name'])) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Error: El archivo no es una imagen válida. -2",
                    "icono" => "error"
                ];
                return json_encode($alerta);

            }

        }

        $url_base_foto2 = "";

        if (isset($_FILES['foto2']) && $_FILES['foto2']["name"] != "") {

            $uploadDir = BASE_URL_ARCHIVOS_LICENCIAS;
            $newFileName = $nombre . "_" . $dui . "_" . "L2" . "_img_." . $fileExtension;
            $uploadPath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['foto2']['tmp_name'], $uploadPath)) {
                $url_base_foto2 = $newFileName;
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

        // Datos a insertar
        $datos = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'dui' => $dui,
            'tipo_licencia1' => $tipo_licencia1,
            'licencia1' => $licencia1,
            'tipo_licencia2' => $tipo_licencia2, // Corregido
            'licencia2' => $licencia2,
            'empresa' => $empresa,
            'numero_contacto' => $numero_contacto,
            'correo' => $correo,
            'fecha_licencia1' => $refrenda1,
            'fecha_licencia2' => $refrenda2,
            'ruta_licencia1' => $url_base_foto1,
            'ruta_licencia2' => $url_base_foto2,
            'estado' => $estado,
            'usuario_c' => $usuario_c
        ];
        $datos = $this->MainModel->limpiarArray($datos);

        // Insertar en la base de datos
        $resultado = $this->MainModel->insertar("conductores", $datos);

        if ($resultado > 0) {
            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "Registro exitoso",
                "texto" => "El conductor  $nombre se registró correctamente.",
                "icono" => "success",
                "url" => BASE_URL . 'conductores'
            ];
        } else {
            $alerta = [
                "tipo" => "simple",
                "titulo" => "Error",
                "texto" => "No se pudo registrar el conductor.",
                "icono" => "error"
            ];
        }
        return json_encode($alerta);
    }

    // MODIFICAR ESTADO DE conductor
    public function modificarEstadoConductor($conductor_id)
    {
        $conductor = $this->getConductor($conductor_id);

        $estado = $conductor[0]['estado'];
        $dui = $conductor[0]['dui'];

        $nombre = $conductor[0]['nombre'];
        $apellido = $conductor[0]['apellido'];

        $referencia = $dui ." ".$nombre." ".$apellido;
        $tabla = 'conductores';

        $filtro = ['conductor_id' => $conductor_id];



  // INICIO DE PERMISO
  $permiso = new PermisoController();
  //PERMISO ID 17  modificar estado del vehiculo
  $numero_permiso = 17;
  $v_permiso = $permiso->getPermiso(usuario_session(), $numero_permiso, $referencia, 1);
  // SI NO TIENE PERMISO
 
 
  if ($v_permiso == false) {
     $alerta = [
         "tipo" => "simpleRedireccion",
         "titulo" => "Error de permisos",
         "texto" => "Necesitas el permiso # " . $numero_permiso,
         "icono" => "error",
         "url" => BASE_URL . 'home'
     ];
     return json_encode($alerta); // Terminar ejecución con alerta de error
 
 }
 // FIN DE PERMISO





        if ($estado > 0) {
            $datos = [
                'estado' => 0,
                'usuario_u' => usuario_session()
            ];
            $datos = $this->MainModel->limpiarArray($datos);

            $resultado = $this->MainModel->actualizar($tabla, $datos, $filtro);
            if ($resultado > 0) {
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "conductor modificado",
                    "texto" => "El conductor se desactivo",
                    "icono" => "success",
                    "url" => BASE_URL . 'conductores'
                ];

            } else {
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "Ocurrio un error",
                    "texto" => "No se pudo modificar el conductor, intente nuevamente.",
                    "icono" => "error",
                    "url" => BASE_URL . 'conductores'
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
                    "titulo" => "conductor modificado",
                    "texto" => "El conductor se activo",
                    "icono" => "success",
                    "url" => BASE_URL . 'conductores'
                ];

            } else {
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "Ocurrio un error",
                    "texto" => "No se pudo modificar el conductor, intente nuevamente.",
                    "icono" => "error",
                    "url" => BASE_URL . 'conductores'
                ];
            }
        }
        return json_encode($alerta); // Retornar alerta en formato JSON
    }
    public function modificarCondcutor()
    {
        if (empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['dui']) || empty($_POST['empresa']) || empty($_POST['conductor_id'])) {

            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "No has llenado todos los campos que son obligatorios",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        if ($_POST['tipo_licencia1'] == 0) {

            $alerta = [
                "tipo" => "simple",
                "titulo" => "Ocurrió un error inesperado",
                "texto" => "Selecciona un tipo de licencia 1",
                "icono" => "error"
            ];
            return json_encode($alerta);
        }

        $dui = $_POST['dui'];



            // INICIO DE PERMISO
 $permiso = new PermisoController();
 //PERMISO ID 16  modificar estado del vehiculo
 $numero_permiso = 16;
 $v_permiso = $permiso->getPermiso(usuario_session(), $numero_permiso, $dui, 1);
 // SI NO TIENE PERMISO


 if ($v_permiso == false) {
    $alerta = [
        "tipo" => "simpleRedireccion",
        "titulo" => "Error de permisos",
        "texto" => "Necesitas el permiso # " . $numero_permiso,
        "icono" => "error",
        "url" => BASE_URL . 'home'
    ];
    return json_encode($alerta); // Terminar ejecución con alerta de error

}
// FIN DE PERMISO

        // Verificar que el dui del conductor no exista
        $conductor_existe = $this->MainModel->consultar('conductores', ['dui' => $dui]);
         //var_dump($conductor_existe);

        if (count($conductor_existe) > 0) {
            if ($conductor_existe[0]['conductor_id'] != $_POST['conductor_id']) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Error al registrar",
                    "texto" => "El conductor con dui $dui ya existe.",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
           $sql_imagen1 = $conductor_existe[0]['ruta_licencia1'];
           $sql_imagen2 = $conductor_existe[0]['ruta_licencia1'];


        }
        
        

        // Recibir datos del formulario

        $conductor_id = $_POST['conductor_id'];
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $tipo_licencia1 = $_POST['tipo_licencia1'];
        $licencia1 = $_POST['licencia1'] ?? null;
        $tipo_licencia2 = $_POST['tipo_licencia2'] ?? null;
        $licencia2 = $_POST['licencia2'] ?? null;
        $empresa = $_POST['empresa'];
        $numero_contacto = $_POST['numero'] ?? null;
        $correo = $_POST['correo'] ?? null;
        $usuario_u = usuario_session();
        $estado = 1;

        $refrenda1 = $_POST['refrenda1'] ?? null;
        $refrenda2 = $_POST['refrenda2'] ?? null;

        //fin de formulario 

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

            $uploadDir = BASE_URL_ARCHIVOS_LICENCIAS;
            $newFileName = $nombre . "_" . $dui . "_" . "L1" . "_img_." . $fileExtension;
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
        // FIN VALIDACION DE IMAGEN LICENCIA 1

        if (isset($_FILES['foto2']) && $_FILES['foto2']["name"] != "") {
            $mimeType = mime_content_type($_FILES['foto2']['tmp_name']);
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];

            if (!in_array($mimeType, $allowedTypes)) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Error: Solo se permiten imágenes JPEG, PNG o GIF. -2",
                    "icono" => "error"
                ];
                return json_encode($alerta);

            }
            $maxSize = 2 * 1024 * 1024; // 2 MB
            if ($_FILES['foto2']['size'] > $maxSize) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Error: La imagen es demasiado grande. Tamaño máximo: 2 MB. -2",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }

            $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
            $fileExtension = pathinfo($_FILES['foto2']['name'], PATHINFO_EXTENSION);

            if (!in_array(strtolower($fileExtension), $allowedExtensions)) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Error: Extensión de archivo no permitida. -2",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }

            if (!getimagesize($_FILES['foto2']['tmp_name'])) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Ocurrió un error inesperado",
                    "texto" => "Error: El archivo no es una imagen válida. -2",
                    "icono" => "error"
                ];
                return json_encode($alerta);

            }

        }

        $url_base_foto2 = "";

        if (isset($_FILES['foto2']) && $_FILES['foto2']["name"] != "") {

            $uploadDir = BASE_URL_ARCHIVOS_LICENCIAS;
            $newFileName = $nombre . "_" . $dui . "_" . "L2" . "_img_." . $fileExtension;
            $uploadPath = $uploadDir . $newFileName;

            if (move_uploaded_file($_FILES['foto2']['tmp_name'], $uploadPath)) {
                $url_base_foto2 = $newFileName;
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

        if ($url_base_foto1=="") {
            $url_base_foto1=$sql_imagen1;
        }
        if ($url_base_foto2=="") {
            $url_base_foto2=$sql_imagen2;
        }


        // Datos a modificar
        $datos = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'dui' => $dui,
            'tipo_licencia1' => $tipo_licencia1,
            'licencia1' => $licencia1,
            'tipo_licencia2' => $tipo_licencia2, // Corregido
            'licencia2' => $licencia2,
            'empresa' => $empresa,
            'numero_contacto' => $numero_contacto,
            'correo' => $correo,
            'estado' => $estado,
            'usuario_u' => $usuario_u,
            'fecha_licencia1' => $refrenda1,
            'fecha_licencia2' => $refrenda2,
            'ruta_licencia1' => $url_base_foto1,
            'ruta_licencia2' => $url_base_foto2
        ];
        $datos = $this->MainModel->limpiarArray($datos);
        $filtro = ['conductor_id' => $conductor_id];

        $resultado = $this->MainModel->actualizar('conductores', $datos, $filtro);
        // Verificar si la inserción fue exitosa
        if ($resultado != '') {
            $alerta = [
                "tipo" => "simpleRedireccion",
                "titulo" => "Conductor modificado",
                "texto" => "El conductor $nombre se ha modificado correctamente.",
                "icono" => "success",
                "url" => BASE_URL . 'conductores'
            ];
        }

        return json_encode($alerta); // Retornar alerta en formato JSON





    }

}

