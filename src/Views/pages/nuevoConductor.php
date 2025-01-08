<h2>Registrar Motorista</h2>
<div class="">
    <form class="form-container FormularioAjax" id="userForm" enctype="multipart/form-data"
        action="<?= BASE_URL . 'src/Views/ajax/conductorAjax.php' ?>" method="POST">

        <input type="hidden" name="modulo_conductor" value="registrar">

        <div class="form-group">
            <label for="nombre">Nombres</label>
            <input type="text" id="nombre" name="nombre" placeholder="Nombres" required>
        </div>
        <div class="form-group">
            <label for="nombre">Apellidos</label>
            <input type="text" id="apellido" name="apellido" placeholder="Apellidos" required>
        </div>
        <div class="form-group">
            <label for="dui">Dui</label>
            <input type="text" id="dui" name="dui" placeholder="# de dui" required>
        </div>
        <div class="form-group">
            <label for="numero">Numero de contacto</label>
            <input type="text" id="numero" name="numero" placeholder="# de telefono">
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
            <label for="foto1">Foto licencia 1</label>
            <input type="file" id="foto1" name="foto1"  >
        </div>
        <div class="form-group">
            <label for="foto2">Foto licencia 2</label>
            <input type="file" id="foto2" name="foto2"  >
        </div>
        <div class="form-group">
            <label for="refrenda1">Fecha de refrenda 1</label>
            <input type="date" id="refrenda1" name="refrenda1"  >
        </div>
        <div class="form-group">
            <label for="refrenda2">Fecha de refrenda 2</label>
            <input type="date" id="refrenda2" name="refrenda2"  >
        </div>
        <div class="form-group">
            <?= selectEmpresas("empresa", "Empresa") ?>
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