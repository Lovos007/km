<?php

use App\Controllers\UsoDiarioController;

// Obtener el término de búsqueda desde la URL
$search = isset($_POST['search']) ? $_POST['search'] : '';

$UsoController = new UsoDiarioController();
$lista = $UsoController->getUsos($search);

?>
<div class="table-container">
    <a href="<?= BASE_URL . 'nuevo-uso-diario' ?>" class="enlace">Nuevo uso diario</a>

    <div class="h2-estandar">
        <h2>USO DIARIO DE VEHICULOS</h2>
    </div>
    <!-- Formulario de búsqueda -->
    <!-- <div class="search-container">
        <form action="" method="POST" id="searchForm">
            <label for="search">Buscar:</label>
            <input type="text" name="search" id="search" placeholder="Escribe para buscar..." value="<?= $search ?>">
            <button type="submit">Buscar</button>
        </form>
    </div> -->
    <!-- FIN Formulario de búsqueda -->
    <table id="userTable">
        <thead>
            <tr>
                <th>Placa</th>
                <th>Motorista</th>
                <th>Tipo de uso</th>
                <th>Fecha</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($lista)): ?>
                <tr>
                    <td data-label="" colspan="2" style="text-align: center;">No hay resultados</td>
                </tr>
            <?php else: ?>

                <?php foreach ($lista as $uso): ?>
                    <tr>
                        <td data-label="Placa"><?= htmlspecialchars(obtenerPlaca($uso['vehiculo_id']), ENT_QUOTES, 'UTF-8') ?>
                        </td>
                        <td data-label="Motorista">
                            <?= htmlspecialchars(
                                obtenerNombreConductor($uso['conductor_id']),
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>
                        <td data-label="Motorista">
                            <?= htmlspecialchars(
                                $uso['tipo_uso'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>
                        <td data-label="Motorista">
                            <?= htmlspecialchars(
                                $uso['fecha_uso'],
                                ENT_QUOTES,
                                'UTF-8'
                            ) ?>
                        </td>

                        <td data-label="Acciones" class="actions">
                            <a href="<?= BASE_URL . 'src/Views/ajax/usoAjax.php?b=' .
                                base64_encode($uso['km_id']) ?>">Borrar
                            </a>
                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>