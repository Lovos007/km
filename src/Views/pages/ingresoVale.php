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
    <div class="">
        <h2>ingresar detalles</h2>
        <form class="form-container FormularioAjax" id="userForm" enctype="multipart/form-data"
            action="<?= BASE_URL . 'src/Views/ajax/valeAjax.php' ?>" method="POST">


            <input type="hidden" name="modulo_detalle_vale" value="registrar">
            <input type="hidden" name="vale_id" value="<?= $vale_id ?>">

            <div class="form-group">
                <label for="numero"># Vale</label>
                <input type="text" id="numero" name="numero" placeholder="# vale" value="<?= $numero ?>" readonly required>
            </div>

            <div class="form-group">
                <label for="tipo_vehiculo">Tipo Vehículo</label>
                <select name="tipo_vehiculo_vale" id="tipo_vehiculo_vale" required>
                    <option value="" selected>Seleccione</option>
                    <option value="1">MOTOS</option>
                    <option value="2">AUTOS</option>
                </select>
            </div>

            <div class="form-group" id="vehiculos-container">

            </div>
            <div class="form-group">
                <label for="kilometraje">Kilometraje</label>
                <input type="number" id="kilometraje" name="kilometraje" placeholder="Kilometraje actual" required>
            </div>
            <div class="form-group">
                <label for="tipo_combustible">Tipo combustible</label>
                <select name="tipo_combustible" id="tipo_combustible" required>
                    <option value="" selected>Seleccione</option>
                    <option value="REGULAR">REGULAR</option>
                    <option value="REGULAR">SUPER</option>
                    <option value="DIESEL">DIESEL</option>
                </select>
            </div>
            <div class="form-group">
                <label for="cantidad_galones">Cantidad de galones</label>
                <input type="number" id="cantidad_galones" name="cantidad_galones" placeholder="Cantidad galones" required>
            </div>
            <div class="form-group-">
                <label for="gasolina">Monto de gasolina</label>
                <input type="number" id="gasolina" name="gasolina" placeholder="$ monto de gasolina" required>
            </div>
            <div class="form-group">
                <label for="power">Monto power</label>
                <input type="number" id="power" name="power" placeholder="$ monto de power">
            </div>
            <div class="form-group">
                <label for="aceite">Monto aceite</label>
                <input type="number" id="aceite" name="aceite" placeholder="$ monto de aceite">
            </div>
            <div class="form-group">
                <label for="fecha">Fecha</label>
                <input type="date" id="fecha" name="fecha" required value="<?= $fecha_actual_corta ?>">
            </div>
            <div class="file-input-container" id="foto">
                <label for="file-upload">Seleccionar imagen</label>
                <input type="file" id="file-upload" name="fotografia">
                <span>Sube la foto aquí (máx. 2 MB). </span>
                <span>Solo se permiten imágenes JPEG y PNG</span>
            </div>
            <div class="form-group-comentario #form-group-comentario" id="comentario">
                <label for="comentario">Comentarios:</label>
                <textarea name="comentario" placeholder="  Escribe tus comentarios aquí..."></textarea>

            </div>

            <div class="form-group-submit">
                <button type="submit">Guardar</button>
            </div>

            <div class="form-group-detalles">
                <table id="userTable">
                    <thead>
                        <tr>
                            <th>Placa</th>
                            <th>Conductor</th>
                            <th>kilometraje</th>
                            <th>Sumas ($)</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($lista)): ?>
                            <tr>
                                <td data-label="Informacion" colspan="5" style="text-align: center;">No hay detalles aun</td>
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
                                    <td data-label="kilometraje">
                                        <?= htmlspecialchars($valeDetalle['kilometraje'], ENT_QUOTES, 'UTF-8') ?>
                                    </td>
                                    <td data-label="Sumas ($)"><?= htmlspecialchars("$ " . $suma, ENT_QUOTES, 'UTF-8') ?>
                                    </td>
                                    <td data-label="Acciones" class="actions">
                                        <a
                                            href="<?= BASE_URL . 'src/Views/ajax/valeAjax.php?bdv=' . base64_encode($valeDetalle['vale_detalle_id']) ?>">Borrar
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <tr>
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

    <script>
        document.getElementById('tipo_vehiculo_vale').addEventListener('change', function () {
            const tipoVehiculo = this.value;
            const vehiculosContainer = document.getElementById('vehiculos-container');

            // Crear la solicitud AJAX
            const xhr = new XMLHttpRequest();
            xhr.open('POST', '<?= BASE_URL ?>src/Views/ajax/vehiculoAjax.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            // Manejar la respuesta
            xhr.onload = function () {
                if (xhr.status === 200) {
                    vehiculosContainer.innerHTML = xhr.responseText;
                } else {
                    vehiculosContainer.innerHTML = '<p>Error al cargar los vehículos.</p>';
                }
            };

            // Enviar la solicitud con el tipo de vehículo
            xhr.send('tipo_vehiculo_vale=' + encodeURIComponent(tipoVehiculo));
        });
    </script>


<?php else: ?>

    <h2>vale no encontrado</h2>

<?php endif; ?>