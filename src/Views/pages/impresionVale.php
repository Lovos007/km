<?php
use App\Controllers\ValesController;
?>

<?php if (isset($_GET["d"])): ?>
    <?php

    $vale_id = base64_decode($_GET["d"]);

    $vale = new ValesController();
    $vales = new ValesController();

    $vale = $vale->getVale($vale_id);

    $lista = $vales->getDetallesVale($vale_id);

    $resumen =$vales->TablaResumenVale($vale_id);



    $total = 0;

    $numero = $vale[0]["numero"];
    $serie = $vale[0]["serie"];
    $responsable_id = $vale[0]["responsable_id"];
    $estado = $vale[0]["estado"];


    ?>


    <div class="form-group-detalles">
        <table id="userTable">
            <thead>
                <tr>
                    <th colspan="6" style="text-align: center;">
                        Numero de vale: <?= $numero ?>
                    </th>
                </tr>
                <tr>
                    <th>Placa</th>
                    <th>Motorista</th>
                    <th>kilometraje</th>
                    <th>Tipo de gasto</th>
                    <th>Cantidad</th>
                    <th>Sumas ($)</th>

                </tr>
            </thead>
            <tbody>
                <?php if (empty($lista)): ?>
                    <tr>
                        <td data-label="" colspan="8" style="text-align: center;">No hay detalles aun</td>
                    </tr>
                <?php else: ?>

                    <?php foreach ($lista as $valeDetalle): ?>
                        <?php $suma = $valeDetalle["monto_gasto"];
                        $total = $total + $suma;

                        ?>
                        <tr>
                            <td data-label="Placa">
                                <?= htmlspecialchars(obtenerPlaca($valeDetalle['vehiculo_id']), ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td data-label="Conductor">
                                <?= htmlspecialchars(obtenerNombreConductor($valeDetalle['conductor_id']), ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td data-label="kilometraje"><?= htmlspecialchars($valeDetalle['kilometraje'], ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td data-label="Tipo de gasto"><?= htmlspecialchars($valeDetalle['tipo_gasto'], ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td data-label="Cantidad"><?= htmlspecialchars($valeDetalle['cantidad_gasto'], ENT_QUOTES, 'UTF-8') ?>
                            </td>

                            <td data-label="Sumas ($)"><?= htmlspecialchars("$ " . $suma, ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            </td>


                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                        <td>Total</td>
                        <td>($) <?= $total ?></td>
                        <td> </td>

                    </tr>

                <?php endif; ?>
            </tbody>
        </table>
        <br>

        <table>
            <thead>
                <tr>
                    <th colspan="4" style="text-align: center;">
                        Resumen de vale: <?= $numero ?>
                    </th>
                </tr>
                <tr>
                    <th style="text-align: center;">Cantidad</th>
                    <th style="text-align: center;">Tipo de gasto</th>                    
                    <th style="text-align: center;">Precio de unitario</th>
                    <th style="text-align: center;">Monto</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($lista)): ?>
                    <tr>
                        <td data-label="" colspan="8" style="text-align: center;">No hay detalles aun</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($resumen as $tipo): ?>
                        <tr>
                            <td style="text-align: center;" data-label="Cantidad"><?= $tipo["cantidad"] ?></td>
                            <td style="text-align: center;" data-label="Tipo de gasto"><?= $tipo["tipo_gasto"] ?></td>                           
                            <td style="text-align: center;" data-label="precio unitario"><?= number_format($tipo["precio_unitario"],2) ?></td>
                            <td style="text-align: center;" data-label="Total"><?= number_format($tipo["monto"],2) ?></td>
                        </tr>
                        
                    <?php endforeach; ?>
               
                <?php endif; ?>
            </tbody>
        </table>

    </div>
    </form>
    </div>
<?php else: ?>

    <h2>vale no encontrado</h2>

<?php endif; ?>