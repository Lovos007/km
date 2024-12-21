


<div class="">
    <form class="form-container-mini FormularioAjax" id="userForm"
        action="<?= BASE_URL . 'src/Views/ajax/auxiliarAjax.php' ?>" method="POST">
        <h2>Registrar auxiliar</h2>

        <input type="hidden" name="modulo_auxiliar" value="registrar">

        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" placeholder="Nombre completo" required>
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