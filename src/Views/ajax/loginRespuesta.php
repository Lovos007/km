<?php
session_start();
require_once  '../../../config/config.php';
require_once '../../../vendor/autoload.php';
 use App\Controllers\LoginController;
 $loginController = new LoginController();
 $loginController->authenticate();


