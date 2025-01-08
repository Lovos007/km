<?php
use App\Controllers\ConductoresController;
?>

<?php if (isset($_GET["datos"])): ?>
    <?php

    $conductor_id = base64_decode($_GET["datos"]);

    $conductor = new conductoresController();
    $conductor = $conductor->getConductor($conductor_id);


    $nombre = $conductor[0]["nombre"];
    $apellido = $conductor[0]["apellido"];
    $dui = $conductor[0]["dui"];
    $tipo_licencia1 = $conductor[0]["tipo_licencia1"];
    $tipo_licencia2 = $conductor[0]["tipo_licencia2"];
    $licencia1 = $conductor[0]["licencia1"];
    $licencia2 = $conductor[0]["licencia2"];
    $numero_contacto = $conductor[0]["numero_contacto"];
    $correo = $conductor[0]["correo"];
    $empresa = $conductor[0]["empresa"];
    $fecha_licencia1 = $conductor[0]["fecha_licencia1"];
    $fecha_licencia2 = $conductor[0]["fecha_licencia2"];
    $ruta_licencia1 = $conductor[0]["ruta_licencia1"];
    $ruta_licencia2 = $conductor[0]["ruta_licencia2"];

    $ruta1 = BASE_URL . "src/Views/licencias/" . $ruta_licencia1;
    $ruta2 = BASE_URL . "src/Views/licencias/" . $ruta_licencia2;
    

    if ($ruta_licencia1!="") {
        $imagen1= "<img class ='styled-image' src='$ruta1' alt='$ruta_licencia1'>";          
    }else{
        $imagen1= "<p>No hay imagen</p>";         
    }
    if ($ruta_licencia2!="") {
        $imagen2= "<img  class ='styled-image'src='$ruta2' alt='$ruta_licencia2'>";   
              
    }else{
        $imagen2= "<p>No hay imagen</p>";         
    }
    


    ?>

    <h2>Modificar Motorista</h2>
    <div class="">
        <form class="form-container FormularioAjax" id="userForm" enctype="multipart/form-data"
            action="<?= BASE_URL . 'src/Views/ajax/conductorAjax.php' ?>" method="POST">

            <input type="hidden" name="modulo_conductor" value="modificar">
            <input type="hidden" name="conductor_id" value="<?= $conductor_id ?>">

            <div class="form-group">
                <label for="nombre">Nombres</label>
                <input type="text" id="nombre" name="nombre" placeholder="Nombres" value="<?= $nombre ?>" required>
            </div>
            <div class="form-group">
                <label for="nombre">Apellidos</label>
                <input type="text" id="apellido" name="apellido" placeholder="Apellidos" value="<?= $apellido ?>" required>
            </div>
            <div class="form-group">
                <label for="dui">Dui</label>
                <input type="text" id="dui" name="dui" placeholder="# de dui" value="<?= $dui ?>" required>
            </div>
            <div class="form-group">
                <label for="numero">Numero de contacto</label>
                <input type="text" id="numero" name="numero" placeholder="# de telefono" value="<?= $numero_contacto ?>">
            </div>
            <div class="form-group">
                <?= selectTipoLicencia("tipo_licencia1", "Tipo licencia 1", $tipo_licencia1) ?>
            </div>
            <div class="form-group">
                <label for="licencia1">licencia 1</label>
                <input type="text" id="licencia1" name="licencia1" placeholder="# de licencia 1" value="<?= $licencia1 ?>">
            </div>
            <div class="form-group">
                <?= selectTipoLicencia("tipo_licencia2", "Tipo licencia 2", $tipo_licencia2) ?>
            </div>
            <div class="form-group">
                <label for="licencia2">licencia 2</label>
                <input type="text" id="licencia2" name="licencia2" placeholder="# de licencia 2" value="<?= $licencia2 ?>">
            </div>
            <div class="form-group">
                <label for="foto1">Foto licencia 1</label>
                <input type="file" id="foto1" name="foto1">
            </div>
            <div class="form-group">
                <label for="foto2">Foto licencia 2</label>
                <input type="file" id="foto2" name="foto2">
            </div>
            <div class="form-group">
                <label for="refrenda1">Fecha de refrenda 1</label>
                <input type="date" id="refrenda1" name="refrenda1" value="<?= $fecha_licencia1 ?>">
            </div>
            <div class="form-group">
                <label for="refrenda2">Fecha de refrenda 2</label>
                <input type="date" id="refrenda2" name="refrenda2" value="<?= $fecha_licencia2 ?>">
            </div>
            <div class="form-group">
                <?= selectEmpresas("empresa", "Empresa", $empresa) ?>
            </div>

            <div class="form-group">
                <label for="correo">Correo electronico</label>
                <input type="email" id="correo" name="correo" placeholder="Correo electronico" value="<?= $correo ?>">
            </div>

            <div class="form-group-submit">
                <button type="submit">Actualizar</button>
            </div>
            <div class="image-container">
                <p>Licencia 1</p>
                <?= $imagen1?>
            </div>
            <div class="image-container">
                <p>Licencia 2</p>
                <?= $imagen2?>
            </div>
        </form>
    </div>


<?php else: ?>

    <h2>Conductor no encontrado</h2>

<?php endif; ?>