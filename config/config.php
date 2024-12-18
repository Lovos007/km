<?php
use App\Controllers\MainController;
use App\Controllers\perfilController;
use App\Controllers\VehiculoController;


define('BASE_URL', 'http://localhost/km/');
define('BASE_URL_VISTA', 'http://localhost/');

date_default_timezone_set('America/El_Salvador');

define('JS', '
 <script src="' . BASE_URL . 'src/Views/js/menu.js"></script> 
 <script src="' . BASE_URL . 'src/Views/js/sweetalert2.all.min.js"></script>
 <script src="' . BASE_URL . 'src/Views/js/ajax.js"></script>
');




function alerta_redireccionar($mensaje, $url)
{
    echo JS;
    $alerta = [
        "tipo" => "simpleRedireccion",
        "titulo" => "Error de permisos",
        "texto" => $mensaje,
        "icono" => "error",
        "url" => BASE_URL . $url
    ];

    // Convertimos el array a JSON
    $alerta_json = json_encode($alerta, JSON_UNESCAPED_UNICODE);


    echo "
    <body></body>
    ";


    // Insertamos el JSON en el script
    echo "<script>
        alertas_ajax($alerta_json);
    </script>";


    exit; // Detenemos el script después de generar la redirección
}

function php_alerta_redireccionar($alerta_json)
{
    echo JS;


    echo "
    <body></body>
    ";


    // Insertamos el JSON en el script
    echo "<script>
        alertas_ajax($alerta_json);
    </script>";


    exit; // Detenemos el script después de generar la redirección
}



//lista desplegable de perfiles activos
function selectPerfiles($name, $vista, $perfil_id = 0)
{

    $perfiles = new perfilController();
    $lista = $perfiles->getPerfiles();
    $seleccionado="";
    if ($perfil_id > 0) {
        $default = "";
    } else {
        $default = "selected";
    }

    echo " <label for='$name'>$vista</label>
            <select id='$name' name='$name'  required>
                <option value='' disabled $default>Selecciona </option>
                ";
    foreach ($lista as $perfil) {
        if ($perfil_id > 0) {
            if ($perfil_id == $perfil['perfil_id']) {

                $seleccionado = "selected";

            } else {
                $seleccionado = "";
            }
        }
        echo "<option value='{$perfil['perfil_id']}' $seleccionado >{$perfil['nombre_perfil']} </option>";
    }
    echo '</select>';

}
//lista desplegable de vehiculo activos
function selectTipoVehiculo($name, $vista, $tipo_vehiculo_id = 0)
{

    $tipos = new VehiculoController();
    $lista = $tipos->getTipoVehiculo();
    $seleccionado="";
    if ($tipo_vehiculo_id > 0) {
        $default = "";
    } else {
        $default = "selected";
    }

    echo " <label for='$name'>$vista</label>
            <select id='$name' name='$name' >
                <option value='' disabled $default>Selecciona </option>
                ";
    foreach ($lista as $tipo) {
        if ($tipo_vehiculo_id > 0) {
            if ($tipo_vehiculo_id == $tipo['tipo_vehiculo_id']) {

                $seleccionado = "selected";

            } else {
                $seleccionado = "";
            }
        }
        echo "<option value='{$tipo['tipo_vehiculo_id']}' $seleccionado >{$tipo['tipo']} </option>";
    }
    echo '</select>';

}


//lista desplegable de perfiles activos
function selectEmpresas($name, $vista, $nombre_empresa = "")
{

    $empresas = new MainController();
    $lista = $empresas->getEmpresas();
    $seleccionado="";
    if ($nombre_empresa !="" ) {
        $default = "";
    } else {
        $default = "selected";
    }

    echo " <label for='$name'>$vista</label>
            <select id='$name' name='$name' required >
                <option value='' disabled $default>Selecciona </option>
                ";
    foreach ($lista as $empresa) {
        if ($nombre_empresa !="" ) {
            if ($nombre_empresa == $empresa['nombre_empresa']) {

                $seleccionado = "selected";

            } else {
                $seleccionado = "";
            }
        }
        echo "<option value='{$empresa['nombre_empresa']}' $seleccionado >{$empresa['nombre_empresa']} </option>";
    }
    echo '</select>';

}


// retornar id usuario de la sesion actual

function usuario_session()
{
    return $_SESSION['user_id'];
}
