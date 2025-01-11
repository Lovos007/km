<?php
use App\Controllers\ResponsablesController;
?>

<?php if (isset($_GET["datos"])): ?>
    <?php

    $responsable_id = base64_decode($_GET["datos"]);

    $responsable = new ResponsablesController();
    $responsable = $responsable->getResponsable($responsable_id);


    $nombre = $responsable[0]["nombre"];
    $apellido = $responsable[0]["apellido"];
    $dui = $responsable[0]["dui"];
    $numero = $responsable[0]["numero"];
    $correo = $responsable[0]["correo"]; 
    $cargo = $responsable[0]["cargo"];
    $empresa = $responsable[0]["empresa"];


    ?>

    <div class="">
        <form class="form-container-mini FormularioAjax" id="userForm"
            action="<?= BASE_URL . 'src/Views/ajax/responsableAjax.php' ?>" method="POST">
            <h2>Modificar responsable</h2>

            <input type="hidden" name="modulo_responsable" value="modificar">
        <input type="hidden" name="responsable_id" value="<?= $responsable_id?>">

            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" placeholder="Nombre completo" value="<?=$nombre ?>"  phrequired>
            </div>
            <div class="form-group">
                <label for="apellido">Apellido</label>
                <input type="text" id="apellido" name="apellido" placeholder="Apellido" value="<?= $apellido ?>" required>
            </div>
            <div class="form-group">
                <label for="dui">Dui</label>
                <input type="text" id="dui" name="dui" placeholder="# dui" value="<?= $dui ?>"required>
            </div>
            <div class="form-group">
                <label for="numero">Numero</label>
                <input type="number" id="numero" name="numero" value="<?= $numero ?>" placeholder="Numero de contacto">
            </div>
            <div class="form-group">
                <label for="correo">Corre electronico</label>
                <input type="email" id="correo" name="correo" value="<?= $correo ?>" placeholder="correo@correo.com">
            </div>
            <div class="form-group">
                <label for="cargo">Cargo</label>
                <input type="text" id="cargo" name="cargo" placeholder="Cargo en la empresa" value="<?=$cargo ?>">
            </div>
            <div class="form-group">
                <?= selectEmpresas("empresa", "Empresa",$empresa) ?>
            </div>
            <div class="form-group-submit">
                <button type="submit">Actualizar</button>
            </div>
        </form>
    </div>

<?php else: ?>

    <h2>responsable$responsable no encontrado</h2>

<?php endif; ?>