<div class="">
    <h2>Ingresar uso diario de vehiculo</h2>
    <form class="form-container FormularioAjax" id="userForm" 
        action="<?= BASE_URL . 'src/Views/ajax/usoAjax.php' ?>" method="POST">

        <input type="hidden" name="modulo_uso_diario" value="registrar">
        <div class="form-group">
            <label for="tipo_de_uso">Tipo de uso</label>
            <select name="tipo_de_uso" id="tipo_de_uso" required>
                <option value="" selected>Seleccione</option>
                <option value="RUTA DE VENTA">RUTA DE VENTA</option>
                <option value="RUTA DE DESPACHO">RUTA DE DESPACHO</option>
                <option value="ENTREGA ESPECIAL">ENTREGA ESPECIAL</option>
                <option value="AREA DE MANTENIMIENTO">AREA DE MANTENIMIENTO</option>
                <option value="EN TALLER">EN TALLER</option>
                <option value="OTRO">OTRO</option>

            </select>
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
            <label for="ruta">Ruta de venta</label>
            <input type="text" id="ruta" name="ruta" placeholder="# de ruta" >
        </div>
        <div class="form-group-">
            <label for="equipo">Equipo de despacho</label>
            <input type="text" id="equipo" name="equipo" placeholder="# de equipo " >
        </div>
        <div class="form-group">
            <label for="fecha">Fecha</label>
            <input type="date" id="fecha" name="fecha" required value="<?= $fecha_actual_corta ?>">
        </div>
        <div class="form-group">
            <label for="kilometraje">Kilometraje</label>
            <input type="number" id="kilometraje" name="kilometraje" placeholder="Kilometraje actual" required>
        </div>
       
        <div class="form-group-comentario #form-group-comentario" id="comentario">
            <label for="comentario">Comentarios:</label>
            <textarea name="comentario" placeholder="  Escribe tus comentarios aquí..."></textarea>
        </div>
        <div class="form-group-submit">
            <button type="submit">Guardar</button>
        </div>

</div>
</form>

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