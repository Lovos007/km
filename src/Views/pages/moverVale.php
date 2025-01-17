<?php
use App\Controllers\ValesController;
?>

<?php if (isset($_GET["d"])): ?>
    <?php
    $vale_id = base64_decode($_GET["d"]);
    $nuevo =$_GET["n"];
    $vale = new ValesController();
    $vales = new ValesController();
    $vale = $vale->getVale($vale_id);
    $lista = $vales->getDetallesVale($vale_id);
    $total = 0;
    $numero = $vale[0]["numero"];
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
        <h2>Mover detalles</h2>
        <input type="hidden" name="modulo_vale" value="mover_detalles">
        <input type="hidden" name="vale_id" value="<?= $vale_id?>">
        <div class="form-group">
            <label for="numero"># Vale</label>
            <input type="text" id="numero" name="numero" value="<?= $numero?>"  placeholder="# vale"  readonly required>
        </div>
        <div class="form-group">
            <?= selectValesActivos("nuevovale","Listadod de vales activos",$vale_id,1) ?>
        </div>                    
        <div class="form-group-submit">
            <button type="submit">Mover detalles</button>
        </div>
    </form>
</div>
<?php else: ?>

<h2>vale no encontrado</h2>

<?php endif; ?>