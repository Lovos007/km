<?php
use App\Controllers\ResponsablesController;
?>

<?php if (isset($_GET["datos"])): ?>
    <?php

    $responsable_id = base64_decode($_GET["datos"]);

    $responsable = new ResponsablesController();
    $responsable = $responsable->getResponsable($responsable_id);


    $nombre = $responsable[0]["nombre"];
    $cargo = $responsable[0]["cargo"];
    $empresa = $responsable[0]["empresa"];


    ?>

    <div class="">
        <form class="form-container-mini FormularioAjax" id="userForm"
            action="<?= BASE_URL . 'src/Views/ajax/responsableAjax.php' ?>" method="POST">
            <h2>Modificar responsable$responsable</h2>

            <input type="hidden" name="modulo_responsable" value="modificar">
        <input type="hidden" name="responsable_id" value="<?= $responsable_id?>">

            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" placeholder="Nombre completo" value="<?=$nombre ?>"  phrequired>
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