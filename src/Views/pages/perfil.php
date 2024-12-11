<?php

use App\Controllers\perfilController;


// Obtener el término de búsqueda desde la URL
$search = isset($_GET['search_encrypted']) ? base64_decode($_GET['search_encrypted']) : ''; // Decodificar



$perfiles = new perfilController();
$lista = $perfiles->getPerfiles($search);

?>

<div class="table-container">
    <a href="<?= BASE_URL . 'nuevoPerfil' ?>" class="enlace">Nuevo perfil</a>

    <div class="h2-estandar">
        <h2>LISTADO DE PERFILES</h2>
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
                
                <th>Nombre de perfil</th>
                
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach ($lista as $perfil): ?>
                <tr>
                    
                    <td data-label="Nombre"><?= htmlspecialchars($perfil['nombre_perfil'], ENT_QUOTES, 'UTF-8') ?></td>
                    
                    <td data-label="Acciones" class="actions">
                        <a href="<?= BASE_URL . 'editarP?datos=' . base64_encode($perfil['perfil_id']) ?>">Editar</a>
                        </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>