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

    public function getConductores($search = '')
    {
        if ($search == '') {
            // Si se encontró el perfiles, lo devuelve, de lo contrario, retorna un array vacío.
            $conductores = $this->MainModel->consultar('conductores');
            return $conductores ? $conductores : [];

        } else {
            $datos =
                [
                    'nombre' => '%' . $search . '%',
                    'dui' => '%' . $search . '%',
                    'empresa' => '%' . $search . '%'
                ];
            $datos = $this->MainModel->limpiarArray($datos);

            $conductores = $this->MainModel->consultar('conductores', $datos, " OR ");
            return $conductores ? $conductores : [];

        }
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
        if (empty($_POST['nombre']) || empty($_POST['dui']) || empty($_POST['empresa'])) {

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
        $tipo_licencia1 = $_POST['tipo_licencia1'];
        $licencia1 = $_POST['licencia1'] ?? null;
        $tipo_licencia2 = $_POST['tipo_licencia2'] ?? null;
        $licencia2 = $_POST['licencia2'] ?? null;
        $empresa = $_POST['empresa'];
        $numero_contacto = $_POST['numero'] ?? null;
        $correo = $_POST['correo'] ?? null;
        $usuario_c = usuario_session();
        $estado = 1;


        // Datos a insertar
        $datos = [
            'nombre' => $nombre,
            'dui' => $dui,
            'tipo_licencia1' => $tipo_licencia1,
            'licencia1' => $licencia1,
            'tipo_licencia2' => $tipo_licencia2, // Corregido
            'licencia2' => $licencia2,
            'empresa' => $empresa,
            'numero_contacto' => $numero_contacto,
            'correo' => $correo,
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
        $placa = $conductor[0]['dui'];

        $tabla = 'conductores';

        $filtro = ['conductor_id' => $conductor_id];

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
        if (empty($_POST['nombre']) || empty($_POST['dui']) || empty($_POST['empresa']) || empty($_POST['conductor_id'])) {

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

        // Verificar que el dui del conductor no exista
        $conductor_existe = $this->MainModel->consultar('conductores', ['dui' => $dui]);
        // var_dump($conductor_existe);

        if (count($conductor_existe) > 0) {
            if (!$conductor_existe[0]['conductor_id'] == $_POST['conductor_id']) {
                $alerta = [
                    "tipo" => "simple",
                    "titulo" => "Error al registrar",
                    "texto" => "El conductor con dui $dui ya existe.",
                    "icono" => "error"
                ];
                return json_encode($alerta);
            }
        }

        // Recibir datos del formulario

        $conductor_id = $_POST['conductor_id'];
        $nombre = $_POST['nombre'];
        $tipo_licencia1 = $_POST['tipo_licencia1'];
        $licencia1 = $_POST['licencia1'] ?? null;
        $tipo_licencia2 = $_POST['tipo_licencia2'] ?? null;
        $licencia2 = $_POST['licencia2'] ?? null;
        $empresa = $_POST['empresa'];
        $numero_contacto = $_POST['numero'] ?? null;
        $correo = $_POST['correo'] ?? null;
        $usuario_u = usuario_session();
        $estado = 1;


        // Datos a insertar
        $datos = [
            'nombre' => $nombre,
            'dui' => $dui,
            'tipo_licencia1' => $tipo_licencia1,
            'licencia1' => $licencia1,
            'tipo_licencia2' => $tipo_licencia2, // Corregido
            'licencia2' => $licencia2,
            'empresa' => $empresa,
            'numero_contacto' => $numero_contacto,
            'correo' => $correo,
            'estado' => $estado,
            'usuario_u' => $usuario_u
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

