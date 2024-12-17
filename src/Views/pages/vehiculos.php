<?php

use App\Controllers\VehiculoController;
// Obtener el término de búsqueda desde la URL
$search = isset($_POST['search']) ? $_POST['search'] : '';

$vechiculos = new VehiculoController();
$lista = $vechiculos->getVehiculos($search);


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
                        <td data-label="placa"><?= htmlspecialchars($vehiculo['placa'], ENT_QUOTES, 'UTF-8') ?></td>

                        <td data-label="Acciones" class="actions">
                            <a href="<?= BASE_URL . 'editarP?datos=' . base64_encode($vehiculo['vehiculo_id']) ?>">Editar</a>
                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>