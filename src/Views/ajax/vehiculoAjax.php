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

if (isset($_GET['datos'])) {

	$id_vehiculo =base64_decode($_GET["datos"]);
	$vehiculo = new VehiculoController();

	echo php_alerta_redireccionar($vehiculo->modificarEstadoVehiculo($id_vehiculo));

}