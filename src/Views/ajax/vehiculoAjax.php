<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../vendor/autoload.php';


use App\Controllers\VehiculoController;


if (isset($_POST['modulo_vehiculo'])) {

	$vehiculo = new VehiculoController();


	if($_POST['modulo_vehiculo']=="registrar"){
		echo $vehiculo->crearVehiculo();
	}

	if($_POST['modulo_vehiculo']=="modificar"){
		echo $vehiculo->modificarVehiculo();

	}

}
//recibo peticion de tipo de vehiculo
if (isset($_POST['tipo_vehiculo_vale'])) {
    $tipo_vehiculo = intval($_POST['tipo_vehiculo_vale']);
    echo '
	<div class="form-group">
	';
	echo selectVehiculosActivos("vehiculo", "Vehículo", 0, $tipo_vehiculo);
	echo '
	</div>
	';
	echo '
	<div class="form-group" >
	';
	echo selectConductoresActivos("conductor", "Conductor", 0, $tipo_vehiculo);
	echo '
	</div>
	';
	if ($tipo_vehiculo>1) {
		echo '
	<div class="form-group">
	';
		echo selectAuxiliaresActivos("auxiliar1", "Auxiliar 1",);
		echo '
	</div>
	';

		echo '
	<div class="form-group">
	';

		echo selectAuxiliaresActivos("auxiliar2", "Auxiliar 2",);
		echo '
	</div>
	';
	}
} 

if (isset($_GET['datos'])) {

	$id_vehiculo =base64_decode($_GET["datos"]);
	$vehiculo = new VehiculoController();

	echo php_alerta_redireccionar($vehiculo->modificarEstadoVehiculo($id_vehiculo));

}