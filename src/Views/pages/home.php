<?php $title = 'Inicio';

use App\Controllers\UsoDiarioController;

$UsoController= new UsoDiarioController();
$resultados = $UsoController->reporteConsolidado();

//var_dump($resultados);

?>
<h2>Bienvenido a KM</h2>
<br>
<table border="1" >
<tr>
        <th colspan="2" style="text-align: center;" >Uso actual de vehiculos</th>
        
    </tr>
    <tr>
        <th style="text-align: center;">Tipo</th>
        <th style="text-align: center;">Cantidad</th>
    </tr>
    <tr>
        <td style="text-align: left;">RUTA DE VENTA</td style="text-align: center;">
        <td style="text-align: center;"><?= $resultados["ruta_venta"]?></td style="text-align: center;">
    </tr>
    <tr>
        <td style="text-align: left;">RUTA DE DESPACHO</td style="text-align: center;">
        <td style="text-align: center;"><?= $resultados["ruta_despacho"]?></td style="text-align: center;">
    </tr>
    <tr>
        <td style="text-align: left;">ENTREGA ESPECIAL</td style="text-align: center;">
        <td style="text-align: center;"><?= $resultados["entrega_especial"]?></td style="text-align: center;">
    </tr>
    <tr>
        <td style="text-align: left;">ÁREA DE MANTENIMIENTO</td style="text-align: center;">
        <td style="text-align: center;"><?= $resultados["area_de_mantenimiento"]?></td style="text-align: center;">
    </tr>
    <tr>
        <td style="text-align: left;">EN TALLER</td style="text-align: center;">
        <td style="text-align: center;"><?= $resultados["en_taller"]?></td style="text-align: center;">
    </tr>
    <tr>
        <td style="text-align: left;">SIN USO</td style="text-align: center;">
        <td style="text-align: center;"><?= $resultados["sin_uso"]?></td style="text-align: center;">
    </tr>
</table>

<p>Ultima actualizaion: <?= date('d-m-Y H:i') ?></p>

<?php 
?>