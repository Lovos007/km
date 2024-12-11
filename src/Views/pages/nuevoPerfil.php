

<div class="form-container">
    <form class="FormularioAjax" id="userForm" action="<?= BASE_URL . 'src/Views/ajax/perfilAjax.php' ?>" method="POST">
        <h2>Registrar Perfil</h2>
        <input type="hidden" name="modulo_perfil" value="registrar">
        
        <div class="form-group">
            <label for="nombre_usuario">Nombre del perfil</label>
            <input type="text" id="nombre_perfil" name="nombre_perfil" placeholder="Ingresa el nombre del perfil" required >
        </div>
        <div class="form-group">
            <button type="submit">Registrar</button>
        </div>
    </form>
</div>