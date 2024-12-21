<?php
use App\Controllers\PermisoController;
use App\Controllers\perfilController;

// Obtener el término de búsqueda desde la URL o input
$perfil_id = isset($_GET["datos"]) ? base64_decode($_GET["datos"]) : '';
$query = isset($_GET['query']) ? strtolower($_GET['query']) : '';

// Controladores y datos iniciales
$perfiles = new perfilController();
$perfil = $perfiles->getPerfil($perfil_id);

// Obtener todos los permisos inicialmente
$lista = $perfiles->mostrarPermisosPerfil($perfil_id);

// Filtrar resultados solo si el input no está vacío
if (!empty($query)) {
    $lista = $perfiles->obtenerPermisos($perfil_id, $query);
}

// Obtener nombre del perfil
$nombre_perfil = $perfil[0]['nombre_perfil'];

?>

<div class="table-container">
    <form class="FormularioAjax" id="userForm" action="<?= BASE_URL . 'src/Views/ajax/perfilAjax.php' ?>" method="POST">
        <h2>Modificar Perfil</h2>
        <input type="hidden" name="modulo_perfil" value="editar">
        <input type="hidden" name="perfil_id" value="<?= $perfil_id ?>">

        <div class="form-group">
            <label for="nombre_usuario">Nombre del perfil</label>
            <input type="text" id="nombre_perfil" name="nombre_perfil" value="<?= $nombre_perfil ?>" required>
        </div>
        <div class="form-group">
            <button type="submit">Modificar nombre de perfil</button>
        </div>
    </form>

    <div class="h2-estandar">
        <h3>PERMISOS DEL PERFIL</h3>
    </div>

    <!-- Buscador Dinámico -->
    <div class="form-group">
        <input 
            type="text" 
            id="buscador" 
            placeholder="Digita el nombre o numero de permiso" 
            autocomplete="off"
            style="width: 100%; padding: 10px; font-size: 16px; border: 1px solid #ccc; border-radius: 4px;"
        />
    </div>

    <table id="userTable">
        <thead>
            <tr>
                <th>Numero de permiso</th>
                <th>Permiso</th>
                <th>Activar/desactivar</th>
            </tr>
        </thead>

        <tbody id="resultados">
            <?php foreach ($lista as $perfil): ?>
                <tr>
                    <td data-label="Numero de permiso" style="text-align: center;">
                        <?= htmlspecialchars($perfil['permiso_id'], ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td data-label="Permiso" title="<?= $perfil['descripcion'] ?>">
                        <?= htmlspecialchars($perfil['permiso'], ENT_QUOTES, 'UTF-8') ?>
                    </td>

                    <td data-label="Acciones" class="actions">
                        <a class="a-icono"
                            href="<?= BASE_URL . 'src/Views/ajax/perfilAjax.php?dato1=' . base64_encode($perfil_id) . '&dato2=' . base64_encode($perfil['permiso_id']) ?>">
                            <img src="<?= BASE_URL . 'src/Views/icon/' . ($perfil['estado'] > 0 ? 'activo' : 'inactivo') . '.png' ?>"
                                alt="">
                        </a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>



<script>
    document.addEventListener('DOMContentLoaded', () => {
        iniciarBuscador(
            'buscador', 
            'resultados', 
            '<?= BASE_URL . 'editarP?datos=' . base64_encode($perfil_id) ?>', 
            ''
            
        );
    });
</script>

