


<div class="">
    <form class="form-container-mini FormularioAjax" id="userForm"
        action="<?= BASE_URL . 'src/Views/ajax/responsableAjax.php' ?>" method="POST">
        <h2>Registrar responsable</h2>

        <input type="hidden" name="modulo_responsable" value="registrar">

        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" placeholder="Nombre completo" required>
        </div>
        <div class="form-group">
            <label for="apellido">Apellido</label>
            <input type="text" id="apellido" name="apellido" placeholder="Apellido" required>
        </div>
        <div class="form-group">
            <label for="dui">Dui</label>
            <input type="text" id="dui" name="dui" placeholder="# dui" required>
        </div>
        <div class="form-group">
            <label for="numero">Numero</label>
            <input type="number" id="numero" name="numero" placeholder="Numero de contacto" >
        </div>
        <div class="form-group">
            <label for="correo">Corre electronico</label>
            <input type="email" id="correo" name="correo" placeholder="correo@correo.com" >
        </div>
        <div class="form-group">
            <label for="cargo">Cargo</label>
            <input type="text" id="cargo" name="cargo" placeholder="Cargo en la empresa" >
        </div>
        <div class="form-group">
            <?= selectEmpresas("empresa", "Empresa") ?>
        </div>               
        <div class="form-group-submit">
            <button type="submit">Registrar</button>
        </div>
    </form>
</div>