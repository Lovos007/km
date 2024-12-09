<?php
use App\Controllers\perfilController;


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
    if ($perfil_id > 0) {
        $default = "";
    } else {
        $default = "selected";
    }

    echo " <label for='$name'>$vista</label>
            <select id='$name' name='$name' >
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
        echo "<option value='{$perfil['perfil_id']}' $seleccionado>{$perfil['nombre_perfil']} </option>";
    }
    echo '</select>';

}


// retornar id usuario de la sesion actual

function usuario_session()
{
    return $_SESSION['user_id'];
}
