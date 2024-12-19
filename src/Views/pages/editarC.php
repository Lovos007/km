<?php 
use App\Controllers\ConductoresController;
?>

<?php if(isset($_GET["datos"])): ?>
    <?php 
    
    $conductor_id =base64_decode($_GET["datos"]);

    $conductor= new conductoresController();
    $conductor = $conductor->getConductor($conductor_id); 
       
    
    $nombre =$conductor[0]["nombre"];
    $dui =$conductor[0]["dui"];
    $tipo_licencia1 =$conductor[0]["tipo_licencia1"];
    $tipo_licencia2 =$conductor[0]["tipo_licencia2"];
    $licencia1 =$conductor[0]["licencia1"];
    $licencia2 =$conductor[0]["licencia2"];
    $numero_contacto =$conductor[0]["numero_contacto"];
    $correo =$conductor[0]["correo"];
    $empresa =$conductor[0]["empresa"];
   
    
    ?>
    
    <h2>Registrar conductor</h2>
<div class="">
    <form class="form-container FormularioAjax" id="userForm"
        action="<?= BASE_URL . 'src/Views/ajax/conductorAjax.php' ?>" method="POST">

        <input type="hidden" name="modulo_conductor" value="modificar">
        <input type="hidden" name="conductor_id" value="<?= $conductor_id?>">

        <div class="form-group">
            <label for="nombre">Nombre</label>
            <input type="text" id="nombre" name="nombre" placeholder="Nombre completo" value="<?= $nombre ?>" required>
        </div>
        <div class="form-group">
            <label for="dui">Dui</label>
            <input type="text" id="dui" name="dui" placeholder="# de dui" value="<?= $dui ?>" required>
        </div>
        <div class="form-group">
            <?= selectTipoLicencia("tipo_licencia1", "Tipo licencia 1",$tipo_licencia1) ?>
        </div>        
        <div class="form-group">
            <label for="licencia1">licencia 1</label>
            <input type="text" id="licencia1" name="licencia1" placeholder="# de licencia 1" value="<?= $licencia1 ?>" >
        </div>
        <div class="form-group">
            <?= selectTipoLicencia("tipo_licencia2", "Tipo licencia 2",$tipo_licencia2) ?>
        </div>        
        <div class="form-group">
            <label for="licencia2">licencia 2</label>
            <input type="text" id="licencia2" name="licencia2" placeholder="# de licencia 2" value="<?= $licencia2 ?>">
        </div>
        <div class="form-group">
            <?= selectEmpresas("empresa", "Empresa",$empresa) ?>
        </div>        
        <div class="form-group">
            <label for="numero">Numero de contacto</label>
            <input type="text" id="numero" name="numero" placeholder="# de telefono"  value="<?= $numero_contacto ?>">
        </div>
        <div class="form-group">
            <label for="correo">Correo electronico</label>
            <input type="email" id="correo" name="correo" placeholder="Correo electronico"  value="<?= $correo ?>">
        </div>
       
        <div class="form-group-submit">
            <button type="submit">Actualizar</button>
        </div>
    </form>
</div>


<?php else: ?>

<h2>conductor$conductor no encontrado</h2>

<?php endif; ?>

