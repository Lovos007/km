<div style="text-align: center;">
    <form action="reporte-u-d" target="_blank" method="post">
        <input type="hidden">
        <div>
            <h3 style="
            margin-bottom: 10px;
            ">REPORTE DE USO DIARIO</h3>
        </div>
        <label for="tipo_de_uso">Tipo de uso</label>
        <select name="tipo_de_uso" id="tipo_de_uso">
            <option value="0" selected>TODOS</option>
            <option value="RUTA DE VENTA">RUTA DE VENTA</option>
            <option value="RUTA DE DESPACHO">RUTA DE DESPACHO</option>
            <option value="ENTREGA ESPECIAL">ENTREGA ESPECIAL</option>
            <option value="AREA DE MANTENIMIENTO">AREA DE MANTENIMIENTO</option>
            <option value="EN TALLER">EN TALLER</option>
            <option value="OTRO">OTRO</option>
        </select>
        <?= selectVehiculosActivos("vehiculo_id", "Vehiculos activos",
         0, 0, "NO") ?>
        <?= selectConductoresActivos("conductor_id", "Motoristas activos",
         0, 0, "NO") ?>
        <label for="fecha_inicial"> Fecha inicial</label>
        <input type="date" value="<?= FECHA_ACTUAL_CORTA ?>" name="fecha1">
        <label for="fecha_final"> Fecha final</label>
        <input type="date" value="<?= FECHA_ACTUAL_CORTA ?>" name="fecha2">
        <input type="submit" name="accion" value="Consultar" style="
        margin-top: 5px;
        background-color: #007BFF;
        color: white;
        ">
        <input type="submit" name="accion" value="Exportar" style="
        margin-top: 5px;
        background-color: #007BFF;
        color: white;
        ">
    </form>
</div>