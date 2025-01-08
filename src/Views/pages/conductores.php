<?php

use App\Controllers\ConductoresController;
use App\Controllers\conductorController;
// Obtener el término de búsqueda desde la URL
$search = isset($_POST['search']) ? $_POST['search'] : '';

$conductores = new ConductoresController();
$lista = $conductores->getConductores($search);


?>
<div class="table-container">
    <a href="<?= BASE_URL . 'nuevoConductor' ?>" class="enlace">Nuevo motorista</a>

    <div class="h2-estandar">
        <h2>LISTADO DE MOTORISTAS</h2>
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
                    <td data-label="" colspan="2" style="text-align: center;">No hay resultados</td>
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
                                <a
                                    href="<?= BASE_URL . 'src/Views/ajax/conductorAjax.php?datos=' . base64_encode($conductor['conductor_id']) ?>">Desactivar</a>
                            <?php else: ?>
                                <a
                                    href="<?= BASE_URL . 'src/Views/ajax/conductorAjax.php?datos=' . base64_encode($conductor['conductor_id']) ?>">Activar</a>
                            <?php endif; ?>
                        </td>

                    </tr>
                <?php endforeach; ?> 
            <?php endif; ?>
        </tbody>
    </table>
</div>