<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../vendor/autoload.php';


use App\Controllers\ConductoresController;


if (isset($_POST['modulo_conductor'])) {

	$conductor = new ConductoresController();


	if($_POST['modulo_conductor']=="registrar"){
		echo $conductor->crearConductor();
	}

	 if($_POST['modulo_conductor']=="modificar"){
	 	echo $conductor->modificarCondcutor();

	 }

}

if (isset($_GET['datos'])) {

	$id_conductor =base64_decode($_GET["datos"]);
	$conductor = new ConductoresController();

	echo php_alerta_redireccionar($conductor->modificarEstadoConductor($id_conductor));

}