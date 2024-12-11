<?php
session_start();
require_once '../../../config/config.php';
require_once '../../../vendor/autoload.php';


use App\Controllers\perfilController;


if (isset($_POST['modulo_perfil'])) {

	$perfil = new perfilController();


	if($_POST['modulo_perfil']=="registrar"){
		echo $perfil->crearPerfilNuevo();
	}

	if($_POST['modulo_perfil']=="editar"){
		echo $perfil->modificarPerfil();

	}

}

if (isset($_GET['dato1']) && isset($_GET['dato2'])) {

	
	$perfil = new perfilController();

	php_alerta_redireccionar($perfil->modificarEstadoPermiso());

	 

}