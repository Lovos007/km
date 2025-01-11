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


    <div class="form-group-detalles">
        <table id="userTable">
            <thead>
                <tr>
                    <th>Placa</th>
                    <th>Motorista</th>
                    <th>kilometraje</th>
                    <th>Tipo de gasto</th>
                    <th>cantidad</th>
                    
                    
                    <th>Sumas ($)</th>
                    <th>Acciones</th>
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
                            <td data-label="Gasolina">($) <?= htmlspecialchars($valeDetalle['monto_gasto'], ENT_QUOTES, 'UTF-8') ?>
                            </td>
                            
                            
                            </td>
                            <td data-label="Acciones" class="actions">
                              
                                <a
                                    href="<?= BASE_URL . 'imagenDetalle?id=' . base64_encode($valeDetalle['vale_detalle_id']) ?>">Imagen
                                </a>
                                
                             
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

    </div>
    </form>
    </div>
<?php else: ?>

    <h2>vale no encontrado</h2>

<?php endif; ?>