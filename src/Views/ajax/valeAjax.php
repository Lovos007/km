<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../vendor/autoload.php';
use App\Controllers\ValesController;
if (isset($_POST['modulo_vale'])) {
	$vale = new ValesController();
	if($_POST['modulo_vale']=="registrar"){
		echo $vale->crearVale();
	}
	 if($_POST['modulo_vale']=="modificar"){
	 	echo $vale->modificarVale();
	 }
	 if($_POST['modulo_vale']=="mover_detalles"){
		echo $vale->moverDetallesVale();
	}
}
if (isset($_GET['datos'])) {
	$id_vale =base64_decode($_GET["datos"]);
	$vale = new ValesController();
	echo php_alerta_redireccionar($vale->modificarEstadovale($id_vale));
}

if (isset($_GET['bdv'])) {
	$id_detalle_vale =base64_decode($_GET["bdv"]);
	$vale = new ValesController();
	//echo $vale->borrarDetalleVale($id_detalle_vale);
	echo php_alerta_redireccionar($vale->borrarDetalleVale($id_detalle_vale));
}


if (isset($_POST['modulo_detalle_vale'])) {
	$vale = new ValesController();
	if($_POST['modulo_detalle_vale']=="registrar"){
		echo $vale->crearDetalleVale();
	}
	 if($_POST['modulo_detalle_vale']=="modificar"){
	 	//echo $vale->modificarVale();
	 }
}
