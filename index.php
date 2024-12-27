<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

require_once __DIR__ . '/config/config.php';


use App\Controllers\HomeController;




$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
// La URL que quieres descomponer
$url = $requestUri;
// Eliminar el carácter '/' al principio de la URL si lo hay
$url = ltrim($url, '/');
// Descomponer la URL en un array usando '/' como delimitador
$parts = explode('/', $url);
$vista = $parts[1];


if (empty($_SESSION['user_id'])) {
    // Redirigir al login si no está autenticado
    $controller = new HomeController("/login");    
    $controller->index();

    //echo $_SESSION['user_id'];
   
    
    // exit;
} else {
    if($vista=='login'){
        $vista='home';
    }

    if($vista==''){
        $vista='home';
    }
    

    $controller = new HomeController($vista);
    $controller->index();
    
}

