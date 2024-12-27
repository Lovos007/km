



<div class="">
    <form class="form-container-mini FormularioAjax" id="userForm"
        action="<?= BASE_URL . 'src/Views/ajax/valeAjax.php' ?>" method="POST">
        <h2>Registrar vale</h2>

        <input type="hidden" name="modulo_vale" value="registrar">

        <div class="form-group">
            <label for="numero"># Vale</label>
            <input type="text" id="numero" name="numero" placeholder="# vale"  required>
        </div>
        <div class="form-group">
            <label for="serie">Serie</label>
            <input type="text" id="serie" name="serie" placeholder="# serie"  required>
        </div>
        <div class="form-group">
            <?= selectResponsables("responsable", "Responsable") ?>
        </div>               
        <div class="form-group-submit">
            <button type="submit">Registrar</button>
        </div>
    </form>
</div>