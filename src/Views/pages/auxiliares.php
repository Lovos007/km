<?php

use App\Controllers\AuxiliaresController;

// Obtener el término de búsqueda desde la URL
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Obtener el número de página desde la URL
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$registrosPorPagina = 5;

// Obtener los datos paginados
$auxiliares = new AuxiliaresController();
$datos = $auxiliares->getAuxiliares($search, [], $pagina, $registrosPorPagina);

$lista = $datos['resultados'];
$totalRegistros = $datos['totalRegistros'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

?>
<div class="table-container">
    <a href="<?= BASE_URL . 'nuevoauxiliar' ?>" class="enlace">Nuevo auxiliar</a>

    <div class="h2-estandar">
        <h2>LISTADO DE AUXILIARES</h2>
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
                <th>Nombre</th>
                <th>Cargo</th>
                <th>Empresa</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($lista)): ?>
                <tr>
                    <td data-label="" colspan="4" style="text-align: center;">No hay resultados</td>
                </tr>
            <?php else: ?>
                <?php foreach ($lista as $auxiliar): ?>
                    <tr>
                        <td data-label="Nombre"><?= htmlspecialchars($auxiliar['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Cargo"><?= htmlspecialchars($auxiliar['cargo'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Empresa"><?= htmlspecialchars($auxiliar['empresa'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Acciones" class="actions">
                            <a href="<?= BASE_URL . 'editarA?datos=' . base64_encode($auxiliar['auxiliar_id']) ?>">Ver/Editar</a>
                            <?php if ($auxiliar['estado'] > 0): ?>
                                <a href="<?= BASE_URL . 'src/Views/ajax/auxiliarAjax.php?datos=' . base64_encode($auxiliar['auxiliar_id']) ?>">Desactivar</a>
                            <?php else: ?>
                                <a href="<?= BASE_URL . 'src/Views/ajax/auxiliarAjax.php?datos=' . base64_encode($auxiliar['auxiliar_id']) ?>">Activar</a>
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