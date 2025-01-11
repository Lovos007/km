<?php
use App\Controllers\ValesController;
?>
<?php if (isset($_GET["id"])): ?>
    <?php

    $vale_detalle_id = base64_decode($_GET["id"]);

    $ValeController = new ValesController();

    $vale = $ValeController->getDetalleVale($vale_detalle_id);
    $nombre_imagen = $vale[0]["ruta_foto"];
    $tipo_combustible = $vale[0]["tipo_gasto"];
    $monto_gasolina = $vale[0]["monto_gasto"];
    $placa = obtenerPlaca($vale[0]["vehiculo_id"]);
    $conductor = obtenerNombreConductor($vale[0]["conductor_id"]);

    //echo BASE_URL . "src/Views/detalle_vales/" . $nombre_imagen;

    $ruta = BASE_URL . "src/Views/detalle_vales/" . $nombre_imagen;
    ?>
    <style>
        .div-foto {
            width: 100%;
            max-width: 500px;
            margin: 0 auto;
            /* Centra el contenedor */
            text-align: center;
            /* Alinea el contenido dentro del div */
        }
        .div-foto img {
            max-width: 100%;
            /* Imagen responsiva */
            height: auto;
            /* Mantiene proporciones */
            display: block;
            border-radius: 8px; /* Ajusta el radio a tus necesidades */
        }
    </style>
    <div class="div-foto">
        <p># de placa: <?=$placa ?> </p>
        <p>Motorista: <?=$conductor ?></p>
        <p>Tipo de gasto: <?=$tipo_combustible ?> </p>
        <p>Monto de gasto: ($) <?=$monto_gasolina ?></p>
        <?php if($nombre_imagen!=""): ?>
        <img src="<?= $ruta ?>" alt="<?= $nombre_imagen ?>">            
        <?php else: ?>
        <h3>No hay imagen guardada</h3>            
        <?php endif; ?>      
    </div>
<?php else: ?>
    <h2>vale no encontrado</h2>
<?php endif; ?>