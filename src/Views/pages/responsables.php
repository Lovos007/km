<?php


use App\Controllers\ResponsablesController;
// Obtener el término de búsqueda desde la URL
$search = isset($_POST['search']) ? $_POST['search'] : '';

$responsables = new ResponsablesController();
$lista = $responsables->getResponsables($search);


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
            <input type="text" name="search" id="search" placeholder="Escribe para buscar..." value="<?= $search ?>">
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
                    <td data-label="" colspan="2" style="text-align: center;">No hay resultados</td>
                </tr>
            <?php else: ?>

                <?php foreach ($lista as $responsable): ?>
                    <tr>
                        <td data-label="Placa"><?= htmlspecialchars($responsable['nombre'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Marca"><?= htmlspecialchars($responsable['cargo'], ENT_QUOTES, 'UTF-8') ?></td>
                        <td data-label="Empresa"><?= htmlspecialchars($responsable['empresa'], ENT_QUOTES, 'UTF-8') ?></td>


                        <td data-label="Acciones" class="actions">

                            <a
                                href="<?= BASE_URL . 'editarR?datos=' . base64_encode($responsable['responsable_id']) ?>">Ver/Editar</a>

                            <?php if ($responsable['estado'] > 0): ?>
                                <a
                                    href="<?= BASE_URL . 'src/Views/ajax/responsableAjax.php?datos=' . base64_encode($responsable['responsable_id']) ?>">Desactivar</a>
                            <?php else: ?>
                                <a
                                    href="<?= BASE_URL . 'src/Views/ajax/responsableAjax.php?datos=' . base64_encode($responsable['responsable_id']) ?>">Activar</a>
                            <?php endif; ?>
                        </td>

                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>