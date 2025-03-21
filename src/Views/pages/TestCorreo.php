<?php 
use App\Controllers\ValesController;

$ValeController =  new ValesController();

$respuesta = $ValeController->enviarValeCorreo();

var_dump($respuesta);


