<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../vendor/autoload.php';


use App\Controllers\ResponsablesController;


if (isset($_POST['modulo_responsable'])) {

	$responsable = new ResponsablesController();


	if($_POST['modulo_responsable']=="registrar"){
		echo $responsable->crearResponsable();
	}

	 if($_POST['modulo_responsable']=="modificar"){
	 	echo $responsable->modificarResponsable();

	 }

}

if (isset($_GET['datos'])) {

	$id_responsable =base64_decode($_GET["datos"]);
	$responsable = new ResponsablesController();

	echo php_alerta_redireccionar($responsable->modificarEstadoResponsable($id_responsable));

}