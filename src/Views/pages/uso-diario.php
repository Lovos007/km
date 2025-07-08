<?php

use App\Controllers\UsoDiarioController;

// Obtener datos de búsqueda y paginación
$search = isset($_POST['search']) ? $_POST['search'] : '';
$pagina = isset($_GET['pagina']) ? max(1, intval($_GET['pagina'])) : 1; // Asegura que sea al menos 1
$registrosPorPagina = 10; // Puedes ajustarlo

$UsoController = new UsoDiarioController();
$lista = $UsoController->getUsos($search, [], $pagina, $registrosPorPagina);

// Datos para la paginación
$totalRegistros = $lista['totalRegistros'];
$totalPaginas = ceil($totalRegistros / $registrosPorPagina);
?>

<div class="table-container">
    <a href="<?= BASE_URL . 'nuevo-uso-diario' ?>" class="enlace">Nuevo uso diario</a>

    <div class="h2-estandar">
        <h2>USO DIARIO DE VEHICULOS</h2>
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
                <th>Placa</th>
                <th>Motorista</th>
                <th>Tipo de uso</th>
                <th>Fecha</th>
                <th colspan="2">Acciones</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($lista['resultados'])): ?>
                <tr>
                    <td data-label="" colspan="5" style="text-align: center;">No hay resultados</td>
                </tr>
            <?php else: ?>
                <?php foreach ($lista['resultados'] as $uso): ?>
                    <tr>
                        <td data-label="Placa"><?= htmlspecialchars(obtenerPlaca($uso['vehiculo_id']), ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Motorista"><?= htmlspecialchars(obtenerNombreConductor($uso['conductor_id']), ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Tipo de Uso"><?= htmlspecialchars($uso['tipo_uso'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Fecha"><?= htmlspecialchars($uso['fecha_uso'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Acciones" class="actions">
                            <?php if ($uso['finalizado'] == 0): ?>
                                <a href="<?= BASE_URL . 'src/Views/ajax/usoAjax.php?b=' . base64_encode($uso['km_id']) ?>">Borrar</a>
                            <?php endif; ?>
                        </td>
                        <td data-label="Acciones" class="actions">
                            <?php if ($uso['finalizado'] == 0): ?>
                                <a href="<?= BASE_URL . 'src/Views/ajax/usoAjax.php?f=' . base64_encode($uso['km_id']) ?>">Finalizar</a>
                            <?php else: ?>
                                <a href="#">Finalizado</a>
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