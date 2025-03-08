<?php

use App\Controllers\VehiculoController;
use App\Controllers\PermisoController;

// Obtener el término de búsqueda desde la URL
$search = isset($_POST['search']) ? $_POST['search'] : '';

$vechiculos = new VehiculoController();
$lista = $vechiculos->getVehiculos($search);
//var_dump($lista);

 // INICIO DE PERMISO
 $permiso = new PermisoController();

 //PERMISO ID 10  VER VEHICULOS
 $numero_permiso = 10;
 $v_permiso = $permiso->getPermiso(usuario_session(), $numero_permiso, 'N/A');
 // SI NO TIENE PERMISO

 if ($v_permiso == false) {
     $alerta = [
         "tipo" => "simpleRedireccion",
         "titulo" => "Error de permisos",
         "texto" => "Necesitas el permiso # " . $numero_permiso,
         "icono" => "error",
         "url" => BASE_URL . 'perfil'
     ];
     return json_encode($alerta); // Terminar ejecución con alerta de error

 }
 // FIN DE PERMISO


?>
<div class="table-container">
    <a href="<?= BASE_URL . 'nuevoVehiculo' ?>" class="enlace">Nuevo vehiculo</a>

    <div class="h2-estandar">
        <h2>LISTADO DE VEHICULOS</h2>
    </div>
    <!-- Formulario de búsqueda -->
    <div class="search-container">
        <form action="" method="POST" id="searchForm">
            <label for="search">Buscar:</label>
            <input type="text" name="search" id="search" placeholder="Escribe para buscar..." value="<?= $search ?>">
            <button type="submit">Buscar</button>
        </form>
    </div>
    <!-- FIN Formulario de búsqueda -->
    <table id="userTable">
        <thead>
            <tr>
                <th>Placa</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Color</th>
                <th>Año</th>
                <th>Empresa</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($lista)): ?>
                <tr>
                    <td data-label="" colspan="2" style="text-align: center;">No hay resultados</td>
                </tr>
            <?php else: ?>

                <?php foreach ($lista as $vehiculo): ?>
                    <tr>
                        <td data-label="Placa"><?= htmlspecialchars($vehiculo['placa'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Marca"><?= htmlspecialchars($vehiculo['marca'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Modelo"><?= htmlspecialchars($vehiculo['modelo'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Color"><?= htmlspecialchars($vehiculo['color'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Año"><?= htmlspecialchars($vehiculo['anio'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Empresa"><?= htmlspecialchars($vehiculo['empresa'], ENT_QUOTES, 'UTF-8') ?></td>


                        <td data-label="Acciones" class="actions">
                            <a href="<?= $vehiculo['ruta_fotos'] != "" ? $vehiculo['ruta_fotos'] . '" target="_blank' : '#' ?>">
                                Fotos</a>
                            <a
                                href="<?= BASE_URL . 'editarV?datos=' . base64_encode($vehiculo['vehiculo_id']) ?>">Ver/Editar</a>
                            
                            <?php if ($vehiculo['estado'] > 0): ?>
                                <a
                                    href="<?= BASE_URL . 'src/Views/ajax/vehiculoAjax.php?datos=' . base64_encode($vehiculo['vehiculo_id']) ?>">Desactivar</a>
                            <?php else: ?>
                                <a
                                    href="<?= BASE_URL . 'src/Views/ajax/vehiculoAjax.php?datos=' . base64_encode($vehiculo['vehiculo_id']) ?>">Activar</a>
                            <?php endif; ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>