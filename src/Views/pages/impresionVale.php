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
                    <th>Placa</th>
                    <th>Motorista</th>
                    <th>kilometraje</th>
                    <th>Gasolina</th>
                    <th>Power</th>
                    <th>Aceite</th>
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
                        <?php $suma = $valeDetalle["monto_gasolina"] + $valeDetalle["monto_power"] + $valeDetalle["monto_aceite"];
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
                            <td data-label="Gasolina">($)<?= htmlspecialchars($valeDetalle['monto_gasolina'], ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td data-label="Power">($)<?= htmlspecialchars($valeDetalle['monto_power'], ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td data-label="Aceite">($)<?= htmlspecialchars($valeDetalle['monto_aceite'], ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            <td data-label="Sumas ($)"><?= htmlspecialchars("$ " . $suma, ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            </td>
                           
                            
                        </tr>
                    <?php endforeach; ?>
                    <tr>
                        <td> </td>
                        <td> </td>

                        <td>Total</td>
                        <td>($) <?= $total ?></td>
                        <td> </td>
                        <td> </td>
                        <td> </td>
                    </tr>

                <?php endif; ?>
            </tbody>
        </table>

    </div>
    </form>
    </div>
<?php else: ?>

    <h2>vale no encontrado</h2>

<?php endif; ?>