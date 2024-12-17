<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../vendor/autoload.php';


use App\Controllers\VehiculoController;


if (isset($_POST['modulo_vehiculo'])) {

	$vechiculo = new VehiculoController();


	if($_POST['modulo_vehiculo']=="registrar"){
		echo $vechiculo->crearVehiculo();
	}

	// if($_POST['modulo_usuario']=="editar"){
	// 	echo $user->editarUsuario();

	// }

}

// if (isset($_GET['datos'])) {

// 	$id_user =base64_decode($_GET["datos"]);
// 	$user = new usuarioController();

// 	echo php_alerta_redireccionar($user->modificarEstadoUsuario($id_user));

// }