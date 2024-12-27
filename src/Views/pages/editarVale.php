<?php
use App\Controllers\ValesController;
?>

<?php if (isset($_GET["datos"])): ?>
    <?php

    $vale_id = base64_decode($_GET["datos"]);

    $vale = new ValesController();
    $vale = $vale->getVale($vale_id);


    $numero = $vale[0]["numero"];
    $serie = $vale[0]["serie"];
    $responsable_id = $vale[0]["responsable_id"];
    $estado = $vale[0]["estado"];
    
    if ($estado != 1) {
        
        $alerta = [
            "tipo" => "simpleRedireccion",
            "titulo" => "Infraccion de seguridad",
            "texto" => "El vale  $numero no se encuentra activo",
            "icono" => "error",
            "url" => BASE_URL . 'vales'
        ];

        $alerta = json_encode($alerta);
        echo php_alerta_redireccionar($alerta);
    }
    
   


    ?>
    <div class="">
    <form class="form-container-mini FormularioAjax" id="userForm"
        action="<?= BASE_URL . 'src/Views/ajax/valeAjax.php' ?>" method="POST">
        <h2>Modificar vale</h2>

        <input type="hidden" name="modulo_vale" value="modificar">
        <input type="hidden" name="vale_id" value="<?= $vale_id?>">

        <div class="form-group">
            <label for="numero"># Vale</label>
            <input type="text" id="numero" name="numero" placeholder="# vale" value="<?= $numero ?>"  required>
        </div>
        <div class="form-group">
            <label for="serie">Serie</label>
            <input type="text" id="serie" name="serie" placeholder="# serie" value="<?= $serie ?>"  required>
        </div>
        <div class="form-group">
            <?= selectResponsables("responsable", "Responsable" ,$responsable_id) ?>
        </div>               
        <div class="form-group-submit">
            <button type="submit">Actualizar</button>
        </div>
    </form>
</div>    

<?php else: ?>

    <h2>vale no encontrado</h2>

<?php endif; ?>