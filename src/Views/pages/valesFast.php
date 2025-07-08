<?php

use App\Controllers\ValesController;
// Obtener el término de búsqueda desde la URL
$search = isset($_POST['search']) ? $_POST['search'] : '';

$vales = new ValesController();
$valesData = $vales->getValesDetalles($search);

$lista = $valesData['resultados'];

?>
<div class="table-container">
    <div class="h2-estandar">
        <h3>LISTADO DE VALES</h3>
    </div>

    <!-- Tabla de resultados -->
    <table id="userTable">
        <thead>
            <tr>
                <th>Ingresar datos</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($lista)): ?>
                <tr>
                    <td colspan="1" style="text-align: center;">No hay resultados</td>
                </tr>
            <?php else: ?>
                <?php foreach ($lista as $vale): ?>
                    <tr>
                        <td data-label="Acciones" class="actions" style="text-align: center;">
                            <?php if ($vale['estado'] == 1): ?>
                                <a href="<?= BASE_URL . 'ingresoVale?d=' . base64_encode($vale['vale_id']) . '&n=y' ?>">
                                    <?= htmlspecialchars($vale['numero'], ENT_QUOTES, 'UTF-8') ?>
                                </a>
                            <?php else: ?>
                                <!-- Aquí podrías añadir algo si el estado no es 1 -->
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>
