<?php
sleep(1);
include('config.php');

$fechaIn = isset($_POST['f_ingreso']) ? date("Y-m-d", strtotime($_POST['f_ingreso'])) : null;
$fechaFin = isset($_POST['f_fin']) ? date("Y-m-d", strtotime($_POST['f_fin'])) : null;

// Validar las fechas
if ($fechaIn && $fechaFin) {
    // Consulta para filtrar por fechas
    $sqlHongos = "SELECT * FROM hongos WHERE fecha BETWEEN '$fechaIn' AND '$fechaFin' ORDER BY fecha ASC";
} else {
    // Consulta sin filtro
    $sqlHongos = "SELECT * FROM hongos ORDER BY fecha ASC";
}

$query = mysqli_query($con, $sqlHongos);

// Verificar si hay errores en la consulta
if (!$query) {
    die('Error en la consulta: ' . mysqli_error($con));
}

$total = mysqli_num_rows($query);
echo '<strong>Total: </strong> (' . $total . ')';
?>

<table class="table table-hover">
    <thead>
        <tr>
            <th scope="col">FECHA</th>
            <th scope="col">HORA</th>
            <th scope="col">TEMPERATURA 1</th>
            <th scope="col">TEMPERATURA 2</th>
            <th scope="col">TEMPERATURA 3</th>
            <th scope="col">TEMPERATURA 4</th>
            <th scope="col">TEMPERATURA 5</th>
            <th scope="col">TEMPERATURA EXTERNA</th>
            <th scope="col">HUMEDAD EXTERNA</th>
            <th scope="col">TEMPERATURA EXTERNA</th>
            <th scope="col">HUMEDAD EXTERNA</th>
        </tr>
    </thead>
    <tbody>
        <?php
        // Mostrar los resultados
        while ($dataRow = mysqli_fetch_array($query)) { ?>
            <tr>
                <td><?php echo htmlspecialchars($dataRow['fecha']); ?></td>
                <td><?php echo htmlspecialchars($dataRow['hora']); ?></td>
                <td><?php echo htmlspecialchars($dataRow['temp1']); ?></td>
                <td><?php echo htmlspecialchars($dataRow['temp2']); ?></td>
                <td><?php echo htmlspecialchars($dataRow['temp3']); ?></td>
                <td><?php echo htmlspecialchars($dataRow['temp4']); ?></td>
                <td><?php echo htmlspecialchars($dataRow['temp5']); ?></td>
                <td><?php echo htmlspecialchars($dataRow['t_ext']); ?></td>
                <td><?php echo htmlspecialchars($dataRow['hum_ext']); ?></td>
                <td><?php echo htmlspecialchars($dataRow['tempAvg']); ?></td>
                <td><?php echo htmlspecialchars($dataRow['humAvg']); ?></td>
            </tr>
        <?php } ?>
    </tbody>
</table>
