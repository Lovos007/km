<?php
use App\Controllers\PermisoController;
use App\Controllers\usuarioController;

$permiso = new PermisoController();

// PERMISO ID 2: VER LISTA DE USUARIOS
if (!$permiso->getPermiso($_SESSION['user_id'], 2, "N/A")) {
    die("No tiene permisos para ver esta página.");
}

// Obtener el término de búsqueda desde la URL
$search = isset($_GET['search_encrypted']) ? base64_decode($_GET['search_encrypted']) : ''; // Decodificar



$usuarios = new usuarioController();
$lista = $usuarios->getUsuarios($search);

?>

<div class="table-container">
    <a href="<?= BASE_URL . 'nuevoUsuario' ?>" class="enlace">Nuevo Usuario</a>

    <div class="h2-estandar">
        <h2>LISTADO DE USUARIOS</h2>
    </div>

    <!-- Formulario de búsqueda -->
    <div class="search-container">
        <form action="" method="GET" id="searchForm">
            <label for="search">Buscar:</label>
            <input type="text" name="search" id="search" placeholder="Escribe para buscar..."
                value="<?= isset($_GET['search_encrypted']) ? htmlspecialchars($search, ENT_QUOTES, 'UTF-8') : '' ?>">
            <input type="hidden" name="search_encrypted" id="search_encrypted">
            <button type="submit">Buscar</button>
        </form>
    </div>

    <!-- FIN Formulario de búsqueda -->



    <table id="userTable">
        <thead>
            <tr>
                <th>Usuario</th>
                <th>Nombre</th>
                <th>Perfil</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($lista as $usuario): ?>
                <tr>
                    <td data-label="Usuario"><?= htmlspecialchars($usuario['nombre_usuario'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="Nombre"><?= htmlspecialchars($usuario['nombre_apellido'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="Perfil"><?= htmlspecialchars($usuario['nombre_perfil'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td data-label="Acciones" class="actions">
                        <a href="<?= BASE_URL . 'editarU?datos=' . base64_encode($usuario['usuario_id']) ?>">Editar</a>
                        <a href="#">Eliminar</a>
                        <?php if ($usuario['estado'] > 0): ?>
                            <a href="<?= BASE_URL . 'src/Views/ajax/usuarioAjax.php?datos=' . base64_encode($usuario['usuario_id']) ?>">Desactivar</a>
                        <?php else: ?>
                            <a href="<?= BASE_URL . 'src/Views/ajax/usuarioAjax.php?datos=' . base64_encode($usuario['usuario_id']) ?>">Activar</a>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>