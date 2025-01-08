<?php
use App\Controllers\AuxiliaresController;
use App\Controllers\MainController;
use App\Controllers\perfilController;
use App\Controllers\ResponsablesController;
use App\Controllers\VehiculoController;
use App\Controllers\ConductoresController;


define('BASE_URL', 'http://localhost/km/');
define('BASE_URL_VISTA', 'http://localhost/');
define('BASE_URL_ARCHIVOS', __DIR__.'/../src/Views/detalle_vales/'); 
define('BASE_URL_ARCHIVOS_LICENCIAS', __DIR__.'/../src/Views/licencias/'); 


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
function selectTipoLicencia($name, $vista, $tipo_licencia_id =0)
{

    $licencias = new ConductoresController();
    $lista = $licencias->getTipoLicencia();
    $seleccionado="";
    if ($tipo_licencia_id >0 ) {
        $default = "";
    } else {
        $default = "selected";
    }

    echo " <label for='$name'>$vista</label>
            <select id='$name' name='$name' >
                <option value='0'  $default>Selecciona </option>
                ";
    foreach ($lista as $licencia) {
        if ($tipo_licencia_id>0 ) {
            if ($tipo_licencia_id == $licencia['tipo_licencia_id']) {

                $seleccionado = "selected";

            } else {
                $seleccionado = "";
            }
        }
        echo "<option value='{$licencia['tipo_licencia_id']}' $seleccionado >{$licencia['tipo_licencia']} </option>";
    }
    echo '</select>';

}

function obtenerTipoLicencia($id_tipo_licencia){
    if ($id_tipo_licencia==0) {
        return "";
    }
    
    $licencia = new ConductoresController();
    $resultado = $licencia->getTipoLicencia($id_tipo_licencia);
    return $resultado[0]['tipo_licencia'];

}
function obtenerPlaca($vehiculo_id){
    if ($vehiculo_id==0) {
        return "";
    }    
    $vehiculo = new VehiculoController();
    $resultado = $vehiculo->getVehiculo($vehiculo_id);
    return $resultado[0]['placa'];
}
function obtenerNombreConductor($conductor_id){
    if ($conductor_id==0) {
        return "";
    }    
    $conductor = new ConductoresController();
    $resultado = $conductor->getConductor($conductor_id);
    return $resultado[0]['nombre'];
}

function obtenerNombreResponsable($reponsable_id){
    if ($reponsable_id==0 || $reponsable_id== ""  ) {
        return "";
    }    
    $responsable = new ResponsablesController();
    $resultado = $responsable->getResponsable($reponsable_id);
    return $resultado[0]['nombre'];
}

function obtenerEstadoVale($estado){
    if ($estado==1) {
        return "ACTIVO";
    }
    return "CERRADO";

} 


// retornar id usuario de la sesion actual

function usuario_session()
{
    return $_SESSION['user_id'];
}

function selectResponsables($name, $vista, $reponsable_id =0)
{

    $responsables = new ResponsablesController();
    $lista = $responsables->getResponsables();
    $seleccionado="";
    if ($reponsable_id >0 ) {
        $default = "";
    } else {
        $default = "selected";
    }

    echo " <label for='$name'>$vista</label>
            <select id='$name' name='$name' >
                <option value='0'  $default>Selecciona </option>
                ";
    foreach ($lista as $responsable) {
        if ($reponsable_id>0 ) {
            if ($reponsable_id == $responsable['responsable_id']) {

                $seleccionado = "selected";

            } else {
                $seleccionado = "";
            }
        }
        echo "<option value='{$responsable['responsable_id']}' $seleccionado >{$responsable['nombre']} </option>";
    }
    echo '</select>';

}

function selectVehiculosActivos($name, $vista, $vehiculo_id =0,$tipo_vehiculo=0)
{
    $condiciones = "estado = 1"; // Inicia con estado activo

    if ($tipo_vehiculo == 1) {
        $condiciones = "tipo= 1  AND estado= 1"; // Solo tipo 1 (MOTO) y estado 1
    } else if ($tipo_vehiculo == 2) {
        // Tipos mayores a 1 y estado 1
        $condiciones = "tipo>1  AND estado= 1";  // Usamos la clave 'tipo >' explícitamente
    }
    $vehiculos = new VehiculoController();
    $lista = $vehiculos->getVehiculosCondicion("",$condiciones);
    $seleccionado="";
    if ($vehiculo_id >0 ) {
        $default = "";
    } else {
        $default = "selected";
    }

    echo " <label for='$name'>$vista</label>
            <select id='$name' name='$name' required >
                <option value='0'  $default>Selecciona </option>
                ";
    foreach ($lista as $vehiculo) {
        if ($vehiculo_id>0 ) {
            if ($vehiculo_id == $vehiculo_id['vehiculo_id']) {

                $seleccionado = "selected";

            } else {
                $seleccionado = "";
            }
        }
        echo "<option value='{$vehiculo['vehiculo_id']}' $seleccionado >{$vehiculo['placa']} </option>";
    }
    echo '</select>';

}

function selectConductoresActivos($name, $vista, $conductor_id =0,$tipo_licencia1=0)
{
    $condiciones = "estado = 1"; // Inicia con estado activo
    if ($tipo_licencia1 == 1) {
        $condiciones = "tipo_licencia1= 1  AND estado= 1"; // Solo tipo 1 (MOTO) y estado 1
    } else if ($tipo_licencia1 == 2) {
        // Tipos mayores a 1 y estado 1
        $condiciones = "tipo_licencia1>1  AND estado= 1";  // Usamos la clave 'tipo >' explícitamente
    }
    $conductores = new ConductoresController();
    $lista = $conductores->getConductoresCondicion("",$condiciones);
    $seleccionado="";
    if ($conductor_id >0 ) {
        $default = "";
    } else {
        $default = "selected";
    }

    echo " <label for='$name'>$vista</label>
            <select id='$name' name='$name' required>
                <option value='0'  $default>Selecciona </option>
                ";
    foreach ($lista as $conductor) {
        if ($conductor_id>0 ) {
            if ($conductor_id == $conductor['conductor_id']) {

                $seleccionado = "selected";

            } else {
                $seleccionado = "";
            }
        }
        echo "<option value='{$conductor['conductor_id']}' $seleccionado >{$conductor['nombre']} </option>";
    }
    echo '</select>';

}

function selectAuxiliaresActivos($name, $vista, $auxiliar_id =0)
{
    $condiciones = ["estado" => 1]; // Inicia con estado activo
   
    $auxiliares = new AuxiliaresController();
    $lista = $auxiliares->getAuxiliares("",$condiciones);
    $seleccionado="";
    if ($auxiliar_id >0 ) {
        $default = "";
    } else {
        $default = "selected";
    }

    echo " <label for='$name'>$vista</label>
            <select id='$name' name='$name' >
                <option value='0'  $default>Selecciona </option>
                ";
    foreach ($lista as $auxliar) {
        if ($auxiliar_id>0 ) {
            if ($auxiliar_id == $auxliar['auxliar_id']) {

                $seleccionado = "selected";

            } else {
                $seleccionado = "";
            }
        }
        echo "<option value='{$auxliar['auxliar_id']}' $seleccionado >{$auxliar['nombre']} </option>";
    }
    echo '</select>';

}