<?php

use App\Controllers\VehiculoController;
use App\Controllers\PermisoController;

// Obtener el término de búsqueda desde la URL
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Obtener el número de página desde la URL
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$registrosPorPagina = 5;

// Obtener los datos paginados
$vehiculos = new VehiculoController();
$datos = $vehiculos->getVehiculos($search, [], $pagina, $registrosPorPagina);

$lista = $datos['resultados'];
$totalRegistros = $datos['totalRegistros'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

// INICIO DE PERMISO
$permiso = new PermisoController();

// PERMISO ID 10 VER VEHICULOS
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
        <form action="" method="GET" id="searchForm">
            <label for="search">Buscar:</label>
            <input type="text" name="search" id="search" placeholder="Escribe para buscar..." value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
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
                    <td data-label="" colspan="7" style="text-align: center;">No hay resultados</td>
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
                            <a href="<?= $vehiculo['ruta_fotos'] != "" ? $vehiculo['ruta_fotos'] . '" target="_blank' : '#' ?>">Fotos</a>
                            <a href="<?= BASE_URL . 'editarV?datos=' . base64_encode($vehiculo['vehiculo_id']) ?>">Ver/Editar</a>
                            <?php if ($vehiculo['estado'] > 0): ?>
                                <a href="<?= BASE_URL . 'src/Views/ajax/vehiculoAjax.php?datos=' . base64_encode($vehiculo['vehiculo_id']) ?>">Desactivar</a>
                            <?php else: ?>
                                <a href="<?= BASE_URL . 'src/Views/ajax/vehiculoAjax.php?datos=' . base64_encode($vehiculo['vehiculo_id']) ?>">Activar</a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Paginación -->
    <div class="paginacion">
        <?php if ($pagina > 1): ?>
            <a href="?pagina=<?= $pagina - 1 ?>&search=<?= urlencode($search) ?>">Anterior</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $totalPaginas; $i++): ?>
            <a href="?pagina=<?= $i ?>&search=<?= urlencode($search) ?>" class="<?= ($pagina == $i) ? 'active' : '' ?>"><?= $i ?></a>
        <?php endfor; ?>

        <?php if ($pagina < $totalPaginas): ?>
            <a href="?pagina=<?= $pagina + 1 ?>&search=<?= urlencode($search) ?>">Siguiente</a>
        <?php endif; ?>
    </div>
</div>