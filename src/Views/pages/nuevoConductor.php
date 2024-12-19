<h2>Registrar conductor</h2>
<div class="">
    <form class="form-container FormularioAjax" id="userForm"
        action="<?= BASE_URL . 'src/Views/ajax/conductorAjax.php' ?>" method="POST">

        <input type="hidden" name="modulo_conductor" value="registrar">

        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" placeholder="Nombre completo" required>
        </div>
        <div class="form-group">
            <label for="dui">Dui</label>
            <input type="text" id="dui" name="dui" placeholder="# de dui" required>
        </div>
        <div class="form-group">
            <?= selectTipoLicencia("tipo_licencia1", "Tipo licencia 1") ?>
        </div>        
        <div class="form-group">
            <label for="licencia1">licencia 1</label>
            <input type="text" id="licencia1" name="licencia1" placeholder="# de licencia 1" >
        </div>
        <div class="form-group">
            <?= selectTipoLicencia("tipo_licencia2", "Tipo licencia 2") ?>
        </div>        
        <div class="form-group">
            <label for="licencia2">licencia 2</label>
            <input type="text" id="licencia2" name="licencia2" placeholder="# de licencia 2" >
        </div>
        <div class="form-group">
            <?= selectEmpresas("empresa", "Empresa") ?>
        </div>        
        <div class="form-group">
            <label for="numero">Numero de contacto</label>
            <input type="text" id="numero" name="numero" placeholder="# de telefono">
        </div>
        <div class="form-group">
            <label for="correo">Correo electronico</label>
            <input type="email" id="correo" name="correo" placeholder="Correo electronico">
        </div>
       
        <div class="form-group-submit">
            <button type="submit">Registrar</button>
        </div>
    </form>
</div>