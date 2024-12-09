<?php

use App\Controllers\PermisoController;
$permiso = new PermisoController();
//PERMISO ID 1 VER CONFIGURACION
$v_permiso = $permiso->getPermiso($_SESSION['user_id'], 1, "N/A");

?>

<h2>configuraciones</h2>

<ul>
    <li><a href="<?= BASE_URL . 'usuarioList' ?>">Usuarios</a></li>
    <li>Permisos</li>
    <li>Niveles de usuario</li>    
</ul>