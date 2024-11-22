<?php
    include_once('db.php');
    $fecha=$_POST['fecha'];
    $hora=$_POST['hora'];
    $temp1=$_POST['temp1'];
    $temp2=$_POST['temp2'];
    $temp3=$_POST['temp3'];
    $temp4=$_POST['temp4'];
    $temp5=$_POST['temp5'];
    $t_ext=$_POST['t_ext'];
    $hum_ext=$_POST['hum_ext'];
    $tempAvg=$_POST['tempAvg'];
    $humAvg=$_POST['humAvg'];


// Preparar la consulta
/*$sql = "SELECT * FROM hongos_integral WHERE fecha = ? ";
$stmt = $conn->prepare($sql);
$stmt->bind_param( $fecha);

// Ejecutar la consulta
$stmt->execute();
$result = $stmt->get_result();
*/
// Mostrar resultados
echo "<h1>Resultados</h1>";
if ($result->num_rows > 0) {
    echo "<table border='1'>
            <tr>
                <th>Fecha</th>
                <th>Hora</th>
                <th>Temp1</th>
                <th>Temp2</th>
                <th>Temp3</th>
                <th>Temp4</th>
                <th>Temp5</th>
                <th>T_ext</th>
                <th>Hum_ext</th>
                <th>TempAvg</th>
                <th>HumAvg</th>
            </tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["fecha"] . "</td>
                <td>" . $row["hora"] . "</td>
                <td>" . $row["temp1"] . "</td>
                <td>" . $row["temp2"] . "</td>
                <td>" . $row["temp3"] . "</td>
                <td>" . $row["temp4"] . "</td>
                <td>" . $row["temp5"] . "</td>
                <td>" . $row["t_ext"] . "</td>
                <td>" . $row["hum_ext"] . "</td>
                <td>" . $row["tempAvg"] . "</td>
                <td>" . $row["humAvg"] . "</td>
              </tr>";
    }
    echo "</table>";
} else {
    echo "0 resultados";
}

// Cerrar conexión
$stmt->close();
$conn->close();
?>
