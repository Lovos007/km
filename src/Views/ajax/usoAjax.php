<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../vendor/autoload.php';


use App\Controllers\UsoDiarioController;


if (isset($_POST['modulo_uso_diario'])) {

	$uso = new UsoDiarioController();


	if($_POST['modulo_uso_diario']=="registrar"){
		echo $uso->CrearUso();
	}

	 if($_POST['modulo_uso_diario']=="modificar"){
	 	//echo $uso->modificarUso();

	 }

}

if (isset($_GET['b'])) {
	$km_id =base64_decode($_GET["b"]);
	$uso = new UsoDiarioController();
	echo php_alerta_redireccionar($uso->borrarUso($km_id));
}

if (isset($_GET['f'])) {
	$km_id =base64_decode($_GET["f"]);
	$uso = new UsoDiarioController();
	echo php_alerta_redireccionar($uso->FinalizarUso($km_id));
}