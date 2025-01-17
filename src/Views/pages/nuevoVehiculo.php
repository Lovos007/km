<h2>Registrar vehiculo</h2>
<div class="">
    <form class="form-container FormularioAjax" id="userForm"
     action="<?= BASE_URL . 'src/Views/ajax/vehiculoAjax.php' ?>" method="POST"
     enctype="multipart/form-data">
     
        <input type="hidden" name="modulo_vehiculo" value="registrar">
        
        <div class="form-group">
            <label for="placa">Placa</label>
            <input type="text" id="placa" name="placa" placeholder="placa del vehiculo" oninput="this.value = this.value.toUpperCase();" required >
        </div>
        <div class="form-group">
            <label for="marca">Marca</label>
            <input type="text" id="marca" name="marca" placeholder="marca del vehiculo" required >
        </div>
        <div class="form-group">
            <label for="modelo">Modelo</label>
            <input type="text" id="modelo" name="modelo" placeholder="modelo del vehiculo" required >
        </div>
        <div class="form-group">
            <label for="año">Año</label>
            <input type="number" id="año" name="año" min="1950" max="2050" step="1" placeholder="año del vehiculo" required>
        </div>
        <div class="form-group">
            <label for="color">Color</label>
            <input type="text" id="color" name="color" placeholder="color del vehiculo" required >
        </div>
        <div class="form-group">
          <?= selectEmpresas("empresa","Empresa")?>
        </div>
        <div class="form-group">
          <?= selectTipoVehiculo("tipo_vehiculo","Tipo de vehiculo")?>
        </div>
        <div class="form-group">
            <label for="capacidad_carga">Capacidad de carga</label>
            <input type="text" id="capacidad_carga" name="capacidad_carga" placeholder="capacidad de carga">
        </div>
        <div class="form-group">
            <label for="n_chasis"># de chasis</label>
            <input type="text" id="n_chasis" name="n_chasis" placeholder="# de chasis">
        </div>
        <div class="form-group">
            <label for="n_motor"># de motor</label>
            <input type="text" id="n_motor" name="n_motor" placeholder="# de motor">
        </div>
        <div class="form-group">
            <label for="n_vin"># de vin</label>
            <input type="text" id="n_vin" name="n_vin" placeholder="# de vin">
        </div>
        <div class="form-group">
            <label for="clase">Clase</label>
            <input type="text" id="clase" name="clase" placeholder="Clase">
        </div>
        <div class="form-group">
            <label for="ruta_fotos">Ruta de fotos</label>
            <input type="text" id="ruta_fotos" name="ruta_fotos" placeholder="ruta_fotos">
        </div>
        <div class="form-group">
            <label for="targeta_vence">Imagen de targeta de circulacion </label>
            <input type="file" id="foto1" name="foto1" >
        </div>
        <div class="form-group">
            <label for="targeta_vence">Fecha de proxima refrenda </label>
            <input type="date" id="targeta_vence" name="targeta_vence" >
        </div>
        <div class="form-group-submit">
            <button type="submit">Registrar</button>
        </div>
    </form>
</div>