<?php

use App\Controllers\ValesController;
// Obtener el término de búsqueda desde la URL
$search = isset($_POST['search']) ? $_POST['search'] : '';

$vales = new ValesController();
$lista = $vales->getValesDetalles($search);


?>
<div class="table-container">
    <div class="h2-estandar">
        <h2>LISTADO DE VALES ACTIVOS</h2>
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
                <th># Vale</th>
                <th>Serie</th>
                <th>Responsable</th>
                <th>Estado</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($lista)): ?>
                <tr>
                    <td data-label="" colspan="2" style="text-align: center;">No hay resultados</td>
                </tr>
            <?php else: ?>

                <?php foreach ($lista as $vale): ?>
                    <tr>
                        <td data-label="# Vale"><?= htmlspecialchars($vale['numero'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Serie"><?= htmlspecialchars($vale['serie'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Responsable">
                            <?= htmlspecialchars(obtenerNombreResponsable($vale['responsable_id']), ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Estado"><?= htmlspecialchars(obtenerEstadoVale($vale['estado']), ENT_QUOTES, 'UTF-8') ?>
                        </td>



                        <td data-label="Acciones" class="actions">
                            <?php if ($vale['estado'] == 1): ?>
                                <a href="<?= BASE_URL . 'moverVale?d=' . base64_encode($vale['vale_id']).'&n=y' ?>">Mover detalles</a>
                                
                            <?php else: ?>
                                <a
                                    href="<?= BASE_URL . 'src/Views/ajax/valeAjax.php?datos=' . base64_encode($vale['vale_id']) ?>">Activar
                                </a>
                            <?php endif; ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>