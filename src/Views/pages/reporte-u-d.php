<?php

use App\Controllers\UsoDiarioController;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;


$UsoController = new UsoDiarioController();
$lista = $UsoController->reporteUsoDiario();
$total = 0;

?>

<style>
    body {
        font-family: Arial, sans-serif;
        margin: 20px;
        background-color: #f4f4f9;
    }

    h1 {
        text-align: center;
        color: #333;
    }

    .report-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 20px;
        background-color: #fff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .report-table th,
    .report-table td {
        padding: 10px 15px;
        text-align: left;
        border: 1px solid #ddd;
    }

    .report-table th {
        background-color: # #007BFF;
        color: white;
        font-weight: bold;
        text-transform: uppercase;
    }

    .report-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .report-table tr:hover {
        background-color: #f1f1f1;
    }

    .report-footer {
        margin-top: 20px;
        text-align: right;
        font-size: 0.9em;
        color: #666;
    }

    .report-table th:nth-child(7),
    .report-table td:nth-child(7) {
        width: 120px;
    }
</style>
</head>
<body>
    <h1>Reporte de uso diario</h1>
    <table class="report-table">
        <thead>
            <tr>
                <th>Fecha</th>
                <th>tipo de uso</th>
                <th>Placa</th>
                <th>Motorista</th>
                <th>Ruta</th>
                <th>Equipo</th>
                <th>km ingresado</th>
                <th>km de ultimo recorrido</th>
                <th>Cantidad de km</th>
            </tr>
        </thead>
        <tbody>
            <?php if ($lista != []): ?>
                <?php foreach ($lista as $uso): ?>
                    <?php
                    $diferencia = $uso["nuevo_km"]- $uso["km_actual"];
                    $total = $total+$diferencia;
                    ?>                  

                    <tr>
                        <td style="text-align: center;"><?= $uso["fecha_uso"] ?></td>
                        <td style="text-align: center;"><?= $uso["tipo_uso"] ?></td>
                        <td style="text-align: center;"><?= obtenerPlaca($uso["vehiculo_id"]) ?></td>
                        <td style="text-align: center;"><?= obtenerNombreConductor($uso["conductor_id"]) ?></td>
                        <td style="text-align: center;"><?= $uso["ruta"] ?></td>
                        <td style="text-align: center;"><?= $uso["numero_equipo"] ?></td>
                        <td style="text-align: center;"><?= $uso["nuevo_km"] ?></td>
                        <td style="text-align: center;"><?= $uso["km_actual"] ?></td>
                        <td style="text-align: center;"><?= $diferencia ?></td>
                    </tr>

                <?php endforeach; ?>
                <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td style="text-align: right;">TOTAL</td>
                        <td style="text-align: center;"><?= $total ?></td>
                    </tr>
            <?php else: ?>
                <tr>
                    <td colspan="9" style="text-align: center;">No se encontraro resultados en la busqueda</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    <div class="report-footer">
        Generado el: <?= FECHA_ACTUAL_VISTA ?>
    </div>
</body>