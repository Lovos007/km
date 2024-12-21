<?php
use App\Controllers\auxiliaresController;
?>

<?php if (isset($_GET["datos"])): ?>
    <?php

    $auxiliar_id = base64_decode($_GET["datos"]);

    $auxiliar = new AuxiliaresController();
    $auxiliar = $auxiliar->getAuxiliar($auxiliar_id);


    $nombre = $auxiliar[0]["nombre"];
    $cargo = $auxiliar[0]["cargo"];
    $empresa = $auxiliar[0]["empresa"];


    ?>

    <div class="">
        <form class="form-container-mini FormularioAjax" id="userForm"
            action="<?= BASE_URL . 'src/Views/ajax/auxiliarAjax.php' ?>" method="POST">
            <h2>Modificar auxiliar</h2>

            <input type="hidden" name="modulo_auxiliar" value="modificar">
        <input type="hidden" name="auxiliar_id" value="<?= $auxiliar_id?>">

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

    <h2>auxiliar no encontrado</h2>

<?php endif; ?>