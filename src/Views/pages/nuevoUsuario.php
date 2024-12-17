

<div class="form-container-mini">
    <form class="FormularioAjax" id="userForm" action="<?= BASE_URL . 'src/Views/ajax/usuarioAjax.php' ?>" method="POST">
        <h2>Registrar Usuario</h2>

        <input type="hidden" name="modulo_usuario" value="registrar">
        
        <div class="form-group">
            <label for="nombre_usuario">Nombre de usuario</label>
            <input type="text" id="nombre_usuario" name="nombre_usuario" placeholder="Ingresa el nombre" required >
        </div>

        <div class="form-group">
            <label for="nombre">Nombre y apellido</label>
            <input type="text" id="nombre" name="nombre" placeholder="Ingresa el nombre y apellido" required>
        </div>


        <div class="form-group">
            <label for="password">Contraseña</label>
            <input type="password" id="password" name="password" placeholder="Ingresa la contraseña" required>
        </div>

        <div class="form-group">
          
            <?= selectPerfiles("perfil", "Perfil");?>

        </div>

        <div class="form-group">
            <button type="submit">Registrar</button>
        </div>
    </form>
</div>
