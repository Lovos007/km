<?php
use App\Controllers\ResponsablesController;

// Obtener el término de búsqueda desde la URL o el formulario
$search = isset($_POST['search']) ? $_POST['search'] : '';

// Obtener la página actual
$pagina = isset($_GET['pagina']) ? (int) $_GET['pagina'] : 1;
$registrosPorPagina = 10; // Puedes ajustar este número según sea necesario

// Instanciar el controlador y obtener los resultados
$responsablesController = new ResponsablesController();
$resultado = $responsablesController->getResponsables($search, [], $pagina, $registrosPorPagina);

$lista = $resultado['resultados'];
$totalRegistros = $resultado['totalRegistros'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);
?>

<div class="table-container">
    <a href="<?= BASE_URL . 'nuevoResponsable' ?>" class="enlace">Nuevo responsable</a>

    <div class="h2-estandar">
        <h2>LISTADO DE RESPONSABLES</h2>
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
                <th>Nombre</th>
                <th>Cargo</th>
                <th>Empresa</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($lista)): ?>
                <tr>
                    <td colspan="4" style="text-align: center;">No hay resultados</td>
                </tr>
            <?php else: ?>
                <?php foreach ($lista as $responsable): ?>
                    <tr>
                        <td><?= htmlspecialchars($responsable['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($responsable['cargo'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td><?= htmlspecialchars($responsable['empresa'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td class="actions">
                            <a href="<?= BASE_URL . 'editarR?datos=' . base64_encode($responsable['responsable_id']) ?>">Ver/Editar</a>

                            <?php if ($responsable['estado'] > 0): ?>
                                <a href="<?= BASE_URL . 'src/Views/ajax/responsableAjax.php?datos=' . base64_encode($responsable['responsable_id']) ?>">Desactivar</a>
                            <?php else: ?>
                                <a href="<?= BASE_URL . 'src/Views/ajax/responsableAjax.php?datos=' . base64_encode($responsable['responsable_id']) ?>">Activar</a>
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
