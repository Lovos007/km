<?php

use App\Controllers\ValesController;

// Obtener el término de búsqueda y la página desde la URL
$search = isset($_POST['search']) ? $_POST['search'] : '';
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$registrosPorPagina = 10; // Puedes modificar esta cantidad si deseas más o menos registros por página

$vales = new ValesController();
$valesData = $vales->getValesDetalles($search, $pagina, $registrosPorPagina);

$lista = $valesData['resultados'];
$totalRegistros = $valesData['totalRegistros'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);
?>

<div class="table-container">
    <div class="h2-estandar">
        <h2>LISTADO DE VALES</h2>
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
                    <td data-label="" colspan="5" style="text-align: center;">No hay resultados</td>
                </tr>
            <?php else: ?>
                <?php foreach ($lista as $vale): ?>
                    <tr>
                        <td data-label="# Vale"><?= htmlspecialchars($vale['numero'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Serie"><?= htmlspecialchars($vale['serie'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Responsable"><?= htmlspecialchars(obtenerNombreResponsable($vale['responsable_id']), ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Estado"><?= htmlspecialchars(obtenerEstadoVale($vale['estado']), ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Acciones" class="actions">
                            <?php if ($vale['estado'] == 1): ?>
                                <a href="<?= BASE_URL . 'ingresoVale?d=' . base64_encode($vale['vale_id']).'&n=y' ?>">Ingresar datos</a>
                                <a href="<?= BASE_URL . 'detallesV?d=' . base64_encode($vale['vale_id']) ?>">Detalles</a>
                                <a href="<?= BASE_URL . 'src/Views/ajax/valeAjax.php?datos=' . base64_encode($vale['vale_id']) ?>">Cerrar Vale</a>
                                <a href="<?= BASE_URL . 'impresionVale?d=' . base64_encode($vale['vale_id']) ?>" target="_blank">Impresión</a>
                            <?php else: ?>
                                <a href="<?= BASE_URL . 'src/Views/ajax/valeAjax.php?datos=' . base64_encode($vale['vale_id']) ?>">Activar</a>
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