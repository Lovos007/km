<?php 
use App\Controllers\VehiculoController;
?>

<?php if(isset($_GET["datos"])): ?>
    <?php 
    
    $vehiculo_id =base64_decode($_GET["datos"]);

    $vehiculo= new VehiculoController();
    $vehiculo = $vehiculo->getVehiculo($vehiculo_id); 
       
    $placa =$vehiculo[0]["placa"];
    $marca =$vehiculo[0]["marca"];
    $modelo =$vehiculo[0]["modelo"];
    $anio =$vehiculo[0]["anio"];
    $color =$vehiculo[0]["color"];
    $tipo =$vehiculo[0]["tipo"];
    $empresa =$vehiculo[0]["empresa"];
    $capacidad_carga =$vehiculo[0]["capacidad_carga"];
    $chasis =$vehiculo[0]["chasis"];
    $n_motor =$vehiculo[0]["n_motor"];
    $n_vin =$vehiculo[0]["n_vin"];
    $clase =$vehiculo[0]["clase"];
    $km_actual =$vehiculo[0]["km_actual"];
    $km_anterior =$vehiculo[0]["km_anterior"];
    $ruta_fotos =$vehiculo[0]["ruta_fotos"];
    $targeta_vence =$vehiculo[0]["targeta_vence"];   
    
    ?>
    

<h2>Modificar vehiculo vehiculo</h2>
<div class="form-container">
    <p>Km actual: <?= $km_actual?></p>
    <p>Km anterior: <?=$km_anterior ?></p>
</div>

<div class="">
    <form class="form-container FormularioAjax" 
    id="userForm" action="<?= BASE_URL . 'src/Views/ajax/vehiculoAjax.php' ?>" method="POST" autocomplete="false">
     
        <input type="hidden" name="modulo_vehiculo" value="modificar">
        <input type="hidden" name="vehiculo_id" value="<?= $vehiculo_id?>">
        
        <div class="form-group">
            <label for="placa">Placa</label>
            <input type="text" id="placa" name="placa" placeholder="placa del vehiculo" value="<?=$placa ?>" readonly required >
        </div>
        <div class="form-group">
            <label for="marca">Marca</label>
            <input type="text" id="marca" name="marca" placeholder="marca del vehiculo" value="<?=$marca ?>" required >
        </div>
        <div class="form-group">
            <label for="modelo">Modelo</label>
            <input type="text" id="modelo" name="modelo" placeholder="modelo del vehiculo" value="<?=$modelo ?>" required >
        </div>
        <div class="form-group">
            <label for="año">Año</label>
            <input type="number" id="año" name="año" min="1950" max="2050" step="1" placeholder="año del vehiculo" value="<?=$anio ?>" required>
        </div>
        <div class="form-group">
            <label for="color">Color</label>
            <input type="text" id="color" name="color" placeholder="color del vehiculo" value="<?=$color ?>" required >
        </div>
        <div class="form-group">
          <?= selectEmpresas("empresa","Empresa",$empresa)?>
        </div>
        <div class="form-group">
          <?= selectTipoVehiculo("tipo_vehiculo","Tipo de vehiculo",$tipo)?>
        </div>
        <div class="form-group">
            <label for="capacidad_carga">Capacidad de carga</label>
            <input type="text" id="capacidad_carga" name="capacidad_carga" placeholder="capacidad de carga" value="<?=$capacidad_carga ?>">
        </div>
        <div class="form-group">
            <label for="n_chasis"># de chasis</label>
            <input type="text" id="n_chasis" name="n_chasis" placeholder="# de chasis" value="<?=$chasis ?>">
        </div>
        <div class="form-group">
            <label for="n_motor"># de motor</label>
            <input type="text" id="n_motor" name="n_motor" placeholder="# de motor" value="<?=$n_motor ?>">
        </div>
        <div class="form-group">
            <label for="n_vin"># de vin</label>
            <input type="text" id="n_vin" name="n_vin" placeholder="# de vin" value="<?=$n_vin ?>">
        </div>
        <div class="form-group">
            <label for="clase">Clase</label>
            <input type="text" id="clase" name="clase" placeholder="Clase" value="<?=$clase ?>">
        </div>
        <div class="form-group">
            <label for="ruta_fotos">Ruta de fotos</label>
            <input type="text" id="ruta_fotos" name="ruta_fotos" placeholder="ruta_fotos" value="<?=$ruta_fotos ?>">
        </div>
        <div class="form-group">
            <label for="targeta_vence">Fecha de refrenda </label>
            <input type="date" id="targeta_vence" name="targeta_vence" value="<?=$targeta_vence ?>">
        </div>
        <div class="form-group-submit">
            <button type="submit">Actualizar</button>
        </div>
    </form>
</div>

<?php else: ?>

<h2>Vehiculo no encontrado</h2>

<?php endif; ?>

