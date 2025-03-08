<style>
    .paginacion {
    margin-top: 20px;
    text-align: center;
}
.paginacion a {
    padding: 8px 16px;
    margin: 0 4px;
    border: 1px solid #ddd;
    text-decoration: none;
    color: #333;
}
.paginacion a.active {
    background-color: #007bff;
    color: white;
    border-color: #007bff;
}
.paginacion a:hover:not(.active) {
    background-color: #f1f1f1;
}
</style>

<?php

use App\Controllers\ConductoresController;

// Obtener el término de búsqueda desde la URL
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Obtener el número de página desde la URL
$pagina = isset($_GET['pagina']) ? (int)$_GET['pagina'] : 1;
$registrosPorPagina = 5;

// Obtener los datos paginados
$conductores = new ConductoresController();
$datos = $conductores->getConductores($search, [], $pagina, $registrosPorPagina);

$lista = $datos['resultados'];
$totalRegistros = $datos['totalRegistros'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);

?>
<div class="table-container">
    <a href="<?= BASE_URL . 'nuevoConductor' ?>" class="enlace">Nuevo motorista</a>

    <div class="h2-estandar">
        <h2>LISTADO DE MOTORISTAS</h2>
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
                <th>Dui</th>
                <th>Tipo licencia 1</th>
                <th>Telefono</th>
                <th>Empresa</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($lista)): ?>
                <tr>
                    <td data-label="" colspan="6" style="text-align: center;">No hay resultados</td>
                </tr>
            <?php else: ?>
                <?php foreach ($lista as $conductor): ?>
                    <tr>
                        <td data-label="Nombre"><?= htmlspecialchars($conductor['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Dui"><?= htmlspecialchars($conductor['dui'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Tipo licencia 1"><?= htmlspecialchars(obtenerTipoLicencia($conductor['tipo_licencia1']), ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Numero de contacto"><?= htmlspecialchars($conductor['numero_contacto'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Empresa"><?= htmlspecialchars($conductor['empresa'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Acciones" class="actions">
                            <a href="<?= BASE_URL . 'editarC?datos=' . base64_encode($conductor['conductor_id']) ?>">Ver/Editar</a>
                            <?php if ($conductor['estado'] > 0): ?>
                                <a href="<?= BASE_URL . 'src/Views/ajax/conductorAjax.php?datos=' . base64_encode($conductor['conductor_id']) ?>">Desactivar</a>
                            <?php else: ?>
                                <a href="<?= BASE_URL . 'src/Views/ajax/conductorAjax.php?datos=' . base64_encode($conductor['conductor_id']) ?>">Activar</a>
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