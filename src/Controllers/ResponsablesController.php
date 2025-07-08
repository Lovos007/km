<?php

namespace App\Controllers;
use App\Controllers\PermisoController;
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
    // public function getResponsables($search = '')
    // {
    //     if ($search == '') {
    //         // Si se encontró el perfiles, lo devuelve, de lo contrario, retorna un array vacío.
    //         $responsables = $this->MainModel->consultar('responsables');
    //         return $responsables ? $responsables : [];

    //     } else {
    //         $datos =
    //             [
    //                 'nombre' => '%' . $search . '%',
    //                 'cargo' => '%' . $search . '%',
    //                 'empresa' => '%' . $search . '%'

    //             ];
    //         $datos = $this->MainModel->limpiarArray($datos);

    //         $responsables = $this->MainModel->consultar('responsables', $datos, " OR ");
    //         return $responsables ? $responsables : [];

    //     }

    // }

    public function getResponsables($search = '', $filtro = [], $pagina = 1, $registrosPorPagina = 10)
{
    // Calcular el offset para la paginación
    $offset = ($pagina - 1) * $registrosPorPagina;
    $limit = "LIMIT $offset, $registrosPorPagina";

    if ($search === '') {
        // Si no hay búsqueda, filtra solo por las condiciones adicionales.
        $responsables = $this->MainModel->consultar('responsables', $filtro, " AND ", '', $limit);
        $totalRegistros = $this->MainModel->contarRegistros('responsables', $filtro);
    } else {
        // Prepara los datos para la búsqueda con operadores LIKE
        $datos = [
            'nombre' => '%' . $search . '%',
            'cargo' => '%' . $search . '%',
            'empresa' => '%' . $search . '%'
        ];

        // Limpia el array de datos
        $datos = $this->MainModel->limpiarArray($datos);

        // Realiza la consulta con el operador OR y paginación
        $responsables = $this->MainModel->consultar('responsables', $datos, " OR ", '', $limit);
        $totalRegistros = $this->MainModel->contarRegistros('responsables', $datos, " OR ");
    }

    // Retorna los resultados y el total de registros
    return [
        'resultados' => $responsables ?: [],
        'totalRegistros' => $totalRegistros
    ];
}

    public function getResponsable($responsable_id)
    {
        return $responsable = $this->MainModel->consultar(
            'responsables',
            ['responsable_id' => $responsable_id]
        );

    }

    public function crearResponsable()
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

        // Recibir datos del formulario
        $nombre = $_POST['nombre'];
        $apellido = $_POST['apellido'];
        $dui = $_POST['dui'];
        $numero = $_POST['numero'] ?? null;
        $correo = $_POST['correo'] ?? null;
        $cargo = $_POST['cargo'] ?? null;
        $empresa = $_POST['empresa'];
        $usuario_c = usuario_session();
        $estado = 1;


        // Datos a insertar
        $datos = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'dui' => $dui,
            'numero' => $numero,
            'correo' => $correo,
            'cargo' => $cargo,
            'empresa' => $empresa,
            'estado' => $estado,
            'usuario_c' => $usuario_c
        ];
        $datos = $this->MainModel->limpiarArray($datos);


        // INICIO DE PERMISO
        $permiso = new PermisoController();
        //PERMISO ID 21  modificar estado del vehiculo
        $numero_permiso = 21;
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

    // MODIFICAR RESPONSABLE

    public function modificarEstadoResponsable($responsable_id)
    {
        $responsable = $this->getResponsable($responsable_id);

        $estado = $responsable[0]['estado'];
        $dui = $responsable[0]['dui'];

        $tabla = 'responsables';

        $filtro = ['responsable_id' => $responsable_id];

        if ($estado > 0) {
            $datos = [
                'estado' => 0,
                'usuario_u' => usuario_session()
            ];
            $datos = $this->MainModel->limpiarArray($datos);

            // INICIO DE PERMISO
            $permiso = new PermisoController();
            //PERMISO ID 23  modificar estado responsable
            $numero_permiso = 23;
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

            $resultado = $this->MainModel->actualizar($tabla, $datos, $filtro);
            if ($resultado > 0) {
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "responsable modificado",
                    "texto" => "El responsable se desactivo",
                    "icono" => "success",
                    "url" => BASE_URL . 'responsables'
                ];

            } else {
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

            $resultado = $this->MainModel->actualizar($tabla, $datos, $filtro);
            if ($resultado > 0) {
                $alerta = [
                    "tipo" => "simpleRedireccion",
                    "titulo" => "responsable modificado",
                    "texto" => "El responsable se activo",
                    "icono" => "success",
                    "url" => BASE_URL . 'responsables'
                ];

            } else {
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




    // modificar responsable
    public function modificarResponsable()
    {
        if (empty($_POST['nombre']) || empty($_POST['apellido']) || empty($_POST['dui']) || empty($_POST['empresa']) || empty($_POST['responsable_id'])) {

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
        $apellido = $_POST['apellido'];
        $dui = $_POST['dui'];
        $numero = $_POST['numero'] ?? null;
        $correo = $_POST['correo'] ?? null;
        $cargo = $_POST['cargo'] ?? null;
        $empresa = $_POST['empresa'];
        $usuario_u = usuario_session();
        $estado = 1;


        // Datos a insertar
        $datos = [
            'nombre' => $nombre,
            'apellido' => $apellido,
            'dui' => $dui,
            'numero' => $numero,
            'correo' => $correo,
            'cargo' => $cargo,
            'empresa' => $empresa,
            'estado' => $estado,
            'usuario_u' => $usuario_u
        ];
        // INICIO DE PERMISO
        $permiso = new PermisoController();
        //PERMISO ID 22  modificar responsable
        $numero_permiso = 22;
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




        $datos = $this->MainModel->limpiarArray($datos);
        $filtro = ['responsable_id' => $responsable_id];

        $resultado = $this->MainModel->actualizar('responsables', $datos, $filtro);
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


