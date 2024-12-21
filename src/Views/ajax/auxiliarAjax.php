<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../vendor/autoload.php';


use App\Controllers\AuxiliaresController;


if (isset($_POST['modulo_auxiliar'])) {

	$auxiliar = new AuxiliaresController();


	if($_POST['modulo_auxiliar']=="registrar"){
		echo $auxiliar->crearAuxiliar();
	}

	 if($_POST['modulo_auxiliar']=="modificar"){
	 	echo $auxiliar->modificarAuxiliar();

	 }

}

if (isset($_GET['datos'])) {

	$id_auxiliar =base64_decode($_GET["datos"]);
	$auxiliar = new AuxiliaresController();

	echo php_alerta_redireccionar($auxiliar->modificarEstadoAuxiliar($id_auxiliar));

}