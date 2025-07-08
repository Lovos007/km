
<?php 
use App\Controllers\PermisoController;
      // INICIO DE PERMISO
    //   $permiso = new PermisoController();
    //   //PERMISO ID 33  REPORTE DE USO DIARIO
    //   $numero_permiso = 33;
    //   $v_permiso = $permiso->getPermiso(usuario_session(), $numero_permiso,"");
    //   // SI NO TIENE PERMISO


    //   if ($v_permiso == false) {
    //       $alerta = [
    //           "tipo" => "simpleRedireccion",
    //           "titulo" => "Error de permisos",
    //           "texto" => "Necesitas el permiso # " . $numero_permiso,
    //           "icono" => "error",
    //           "url" => BASE_URL . 'home'
    //       ];
    //       return json_encode($alerta); // Terminar ejecución con alerta de error

    //   }
      // FIN DE PERMISO

?>

<div style="text-align: center;">
    <form action="reporte-u-d" target="_blank" method="post">
        <input type="hidden">
        <div>
            <h3 style="
            margin-bottom: 10px;
            ">REPORTE DE CONSUMO</h3>
        </div>
        <label for="categoria_consumo">Categoria de consumo</label>
         <select name="tipo_gasto" id="tipo_gasto" required>
                    <option value="" selected>Seleccione</option>
                    <option value="REGULAR">REGULAR</option>
                    <option value="SUPER">SUPER</option>
                    <option value="DIESEL">DIESEL</option>
                    <option value="POWER">POWER</option>
                    <option value="ACEITE">ACEITE</option>
                </select>
        <?= selectEmpresas("empresa","Empresa")?>
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