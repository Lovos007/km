<?php

use App\Controllers\ValesController;

// Parámetros de búsqueda y paginación
$search = isset($_POST['search']) ? $_POST['search'] : '';
$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$registrosPorPagina = 10;

$vales = new ValesController();
$resultado = $vales->getVales($search, $pagina, $registrosPorPagina);

$lista = $resultado['resultados'];
$totalRegistros = $resultado['totalRegistros'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

?>

<div class="table-container">
    <a href="<?= BASE_URL . 'nuevoVale' ?>" class="enlace">Nuevo vale</a>

    <div class="h2-estandar">
        <h2>LISTADO DE VALES</h2>
    </div>

    <!-- Formulario de búsqueda -->
    <div class="search-container">
        <form action="" method="POST" id="searchForm">
            <label for="search">Buscar:</label>
            <input type="text" name="search" id="search" placeholder="Escribe para buscar..." value="<?= htmlspecialchars($search, ENT_QUOTES, 'UTF-8') ?>">
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
                    <td colspan="5" style="text-align: center;">No hay resultados</td>
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
                                <a href="<?= BASE_URL . 'editarVale?datos=' . base64_encode($vale['vale_id']) ?>">Ver/Editar</a>
                                <a href="<?= BASE_URL . 'src/Views/ajax/valeAjax.php?datos=' . base64_encode($vale['vale_id']) ?>">Cerrar Vale</a>
                            <?php else: ?>
                                <a href="<?= BASE_URL . 'src/Views/ajax/valeAjax.php?datos=' . base64_encode($vale['vale_id']) ?>">Activar</a>
                            <?php endif; ?>
                            <a href="<?= BASE_URL . 'editarVale?b=' . base64_encode($vale['vale_id']) ?>">Borrar vale y detalles</a>
                            <a href="<?= BASE_URL . 'impresionVale?d=' . base64_encode($vale['vale_id']) ?>">Impresión</a>
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